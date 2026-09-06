<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverBankAccount;
use App\Services\BankU\BankUIdentityService;
use App\Services\BankU\Exceptions\BankUConnectionException;
use App\Services\Wallet\Exceptions\ApiCallDisabledException;
use App\Services\Wallet\Exceptions\InsufficientWalletBalanceException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class DriverBankAccountController extends Controller implements HasMiddleware
{
    public function __construct(private readonly BankUIdentityService $bankU)
    {
    }

    public static function middleware(): array
    {
        return [new Middleware('permission:Driver Create')];
    }

    private function authorizeDriver(Driver $driver): void
    {
        if ($companyId = auth()->user()->companyId()) {
            abort_unless((int) $driver->company_id === (int) $companyId, 404);
        }
    }

    public function edit(Driver $driver)
    {
        $this->authorizeDriver($driver);
        return view('admin.driver.bank', [
            'driver' => $driver->load('user'),
            'bank' => $driver->bankAccount,
        ]);
    }

    public function checkStatus(Driver $driver)
    {
        $this->authorizeDriver($driver);
        $lock = Cache::lock('driver-bank-submit:' . $driver->id, 300);
        if (! $lock->get()) {
            return back()->withErrors(['bank' => 'A bank verification request is already in progress.']);
        }

        try {
            $bank = $driver->bankAccount()->first();
            if (! $bank || ! in_array($bank->status, ['pending', 'unknown', 'submitting'], true)) {
                return back()->withErrors(['bank' => 'There is no pending bank verification to check.']);
            }

            try {
                $result = $this->bankU->bankAccountStatus($bank->verification_reference);
            } catch (RequestException $e) {
                $result = \App\Services\BankU\DataTransferObjects\BankUResponse::fromArray(
                    $e->response->status(), (array) $e->response->json(),
                );
            } catch (BankUConnectionException $e) {
                return back()->withErrors(['bank' => 'BankU is currently unavailable. Please check again shortly.']);
            }

            if (! $result->success) {
                return back()->withErrors(['bank' => $result->message]);
            }

            // API success means the lookup succeeded; only an explicit account
            // validation result can mark the account verified.
            $status = $result->bankAccountVerificationStatus();
            if ($status === null) {
                return back()->withErrors(['bank' => 'BankU has not returned a recognised account verification result. Please check again later.']);
            }

            $bank->update([
                'status' => $status,
                'bank_response' => $result->raw,
                'verified_at' => $status === 'verified' ? now() : null,
            ]);

            return back()->with('success', match ($status) {
                'verified' => 'Bank account verified successfully.',
                'failed' => 'BankU reported that the bank account verification failed.',
                default => 'Bank verification is still pending. Please check again later.',
            });
        } finally {
            $lock->release();
        }
    }

    public function store(Request $request, Driver $driver)
    {
        $this->authorizeDriver($driver);
        $request->merge(['ifsc' => strtoupper(trim((string) $request->input('ifsc')))]);
        $data = $request->validate([
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'account_number' => ['required', 'string', 'regex:/^[0-9]{6,34}$/'],
            'account_holder_name' => 'required|string|max:255',
            'ifsc' => ['required', 'string', 'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/'],
        ]);

        // Serialize submissions per driver without holding a DB transaction during HTTP calls.
        $lock = Cache::lock('driver-bank-submit:' . $driver->id, 300);
        if (! $lock->get()) {
            return back()->withErrors(['bank' => 'Bank verification is already being submitted. Please wait.']);
        }

        try {
            $bank = $driver->bankAccount()->first();
            if ($bank && in_array($bank->status, ['submitting', 'pending', 'verified'], true)) {
                return back()->with('success', 'This account has already been submitted. Current status: ' . $bank->status . '.');
            }

            $sameDetails = $bank && collect($data)->every(fn ($value, $key) => $bank->$key === $value);
            if ($bank && $bank->status === 'unknown' && ! $sameDetails) {
                return back()->withErrors(['bank' => 'The previous request has an unknown result. Retry the saved details before changing the account.']);
            }

            if (! $sameDetails || $bank?->status === 'failed') {
                $bank ??= new DriverBankAccount(['driver_id' => $driver->id]);
                $bank->fill($data + [
                    'status' => 'draft',
                    'verification_reference' => (string) Str::uuid(),
                    'ifsc_idempotency_key' => (string) Str::uuid(),
                    'bank_idempotency_key' => (string) Str::uuid(),
                    'ifsc_response' => null,
                    'bank_response' => null,
                    'submitted_at' => null,
                    'verified_at' => null,
                ]);
                $bank->save();
            }

            $previousStatus = $bank->status;
            try {
                if (! $bank->ifsc_response) {
                    $result = $this->bankU->verifyIfsc(
                        $bank->ifsc, $bank->verification_reference, $bank->ifsc_idempotency_key,
                        auth()->user()->companyId(), auth()->id(),
                    );
                    if (! $result->success) {
                        $bank->update(['status' => ($result->statusCode >= 500 || in_array($result->statusCode, [408, 429], true)) ? 'unknown' : 'failed']);
                        return back()->withErrors(['bank' => 'IFSC verification failed. ' . $result->message]);
                    }
                    $bank->update(['ifsc_response' => $result->raw]);
                }

                $bank->update(['status' => 'submitting']);
                $result = $this->bankU->verifyBankAccount(
                    $bank->account_number, $bank->ifsc, $bank->account_holder_name,
                    $bank->verification_reference, $bank->bank_idempotency_key,
                    auth()->user()->companyId(), auth()->id(),
                );
                $bank->update([
                    // An async acknowledgement is not proof that the bank account is valid.
                    'status' => $result->success ? 'pending' : (($result->statusCode >= 500 || in_array($result->statusCode, [408, 429], true)) ? 'unknown' : 'failed'),
                    'bank_response' => $result->raw,
                    'submitted_at' => $result->success ? now() : null,
                    'verified_at' => null,
                ]);
                if (! $result->success) {
                    return back()->withErrors(['bank' => 'Bank account verification failed. ' . $result->message]);
                }
            } catch (ApiCallDisabledException | InsufficientWalletBalanceException $e) {
                $bank->update(['status' => $previousStatus === 'unknown' ? 'unknown' : 'draft']);
                return back()->withErrors(['bank' => $e->getMessage()]);
            } catch (BankUConnectionException | RequestException $e) {
                if ($e instanceof RequestException && $e->response->clientError()
                    && ! in_array($e->response->status(), [408, 429], true)) {
                    $bank->update(['status' => 'failed']);
                    return back()->withErrors(['bank' => 'BankU rejected the verification request. Check the account details and API configuration.']);
                }
                // Retain the exact payload and operation keys for a safe retry.
                $bank->update(['status' => 'unknown']);
                return back()->withErrors(['bank' => 'BankU could not confirm the result. Retry the saved details to check the same request.']);
            }

            return redirect()->route('driver.bank.edit', $driver)
                ->with('success', 'Bank details saved and submitted for verification. Final confirmation is pending.');
        } finally {
            $lock->release();
        }
    }
}
