<?php

namespace App\Services\BankU;

use App\Services\BankU\DataTransferObjects\BankUResponse;
use App\Services\BankU\Exceptions\BankUConnectionException;
use App\Services\Wallet\CompanyWalletService;
use App\Models\CompanyWalletTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Closure;

/**
 * Domain-facing wrapper around BankU's reseller identity verification APIs.
 *
 * @see https://app.banku.co.in/api/reseller/v1/ (BankU Reseller Identity Verification APIs)
 */
class BankUIdentityService
{
    public function __construct(
        private readonly BankUClient $client,
        private readonly CompanyWalletService $wallet,
    ) {
    }

    /**
     * Aadhaar is charged once, on a successful send-OTP -- verify-OTP (this
     * method) confirms the code and is free, so it takes no $companyId.
     */
    public function sendAadhaarOtp(string $aadhaar, ?int $companyId, ?int $actorUserId = null): BankUResponse
    {
        return $this->chargeThenCall($companyId, 'aadhaar', $actorUserId, fn () => $this->client->post(
            '/api/reseller/v1/aadhaar/send-otp',
            ['aadhaar' => $aadhaar],
        ));
    }

    public function verifyAadhaarOtp(string $otp, string $refId): BankUResponse
    {
        return $this->client->post('/api/reseller/v1/aadhaar/verify-otp', [
            'otp' => $otp,
            'ref_id' => $refId,
        ]);
    }

    public function verifyPan(string $pan, ?int $companyId, ?int $actorUserId = null): BankUResponse
    {
        return $this->chargeThenCall($companyId, 'pan', $actorUserId, fn () => $this->client->post(
            '/api/reseller/v1/pan/verify',
            ['pan' => $pan],
        ));
    }

    public function verifyRc(string $vehicleRegistrationNumber, ?int $companyId, ?int $actorUserId = null, ?string $verificationId = null): BankUResponse
    {
        $verificationId ??= 'rc_' . Str::uuid();

        return $this->chargeThenCall($companyId, 'rc', $actorUserId, fn () => $this->client->post(
            '/api/reseller/v1/identity/rc/verify',
            [
                'vehicleRegistrationNumber' => $vehicleRegistrationNumber,
                'verification_id' => $verificationId,
            ],
        ));
    }

    public function verifyDrivingLicense(string $drivingLicenseNumber, string $dob, ?int $companyId, ?int $actorUserId = null, ?string $verificationId = null): BankUResponse
    {
        $verificationId ??= 'dl_' . Str::uuid();

        return $this->chargeThenCall($companyId, 'driving_licence', $actorUserId, fn () => $this->client->post(
            '/api/reseller/v1/identity/driving-license/verify',
            [
                'drivingLicenseNumber' => $drivingLicenseNumber,
                'dob' => $dob,
                'verification_id' => $verificationId,
            ],
        ));
    }

    public function verifyIfsc(string $ifsc, string $reference, string $idempotencyKey, ?int $companyId, ?int $actorUserId = null): BankUResponse
    {
        return $this->chargeThenCall($companyId, 'ifsc', $actorUserId, fn () => $this->client->post(
            '/api/reseller/v1/ifsc/verify',
            ['ifsc' => $ifsc, 'verification_id' => $reference],
            $idempotencyKey,
        ));
    }

    public function verifyBankAccount(string $account, string $ifsc, string $name, string $reference, string $idempotencyKey, ?int $companyId, ?int $actorUserId = null): BankUResponse
    {
        return $this->chargeThenCall($companyId, 'bank', $actorUserId, fn () => $this->client->post(
            '/api/reseller/v1/bank-account/verify-async',
            ['bank_account' => $account, 'ifsc' => $ifsc, 'name' => $name, 'user_id' => str_replace('-', '_', $reference)],
            $idempotencyKey,
        ));
    }

    public function bankAccountStatus(string $reference): BankUResponse
    {
        // A status lookup is not a new account verification or wallet charge.
        // The client generates a new key for this lookup and reuses it on HTTP retries.
        return $this->client->post('/api/reseller/v1/bank-account/status', [
            'user_id' => str_replace('-', '_', $reference),
        ]);
    }

    public function verifyGstin(string $gstNumber, ?int $companyId, ?int $actorUserId = null): BankUResponse
    {
        return $this->chargeThenCall($companyId, 'gstin', $actorUserId, fn () => $this->client->post(
            '/api/reseller/v1/tax/gstin/verify',
            ['gstNumber' => $gstNumber],
        ));
    }

    /**
     * Charge the company's wallet, then make the BankU call outside the
     * charge's DB transaction (never hold a lock across a slow HTTP call).
     * Refunds the charge if the call fails to connect or BankU rejects it,
     * so a company is only ever left debited for a call that actually
     * succeeded. $companyId === null (Super Admin acting with no company
     * context) skips wallet gating entirely -- there's no wallet to charge.
     *
     * @throws \App\Services\Wallet\Exceptions\ApiCallDisabledException
     * @throws \App\Services\Wallet\Exceptions\InsufficientWalletBalanceException
     */
    private function chargeThenCall(?int $companyId, string $apiKey, ?int $actorUserId, Closure $makeCall): BankUResponse
    {
        if ($companyId === null) {
            return $makeCall();
        }

        $debit = $this->wallet->chargeForApiCall($companyId, $apiKey, $actorUserId);

        try {
            $response = $makeCall();
        } catch (BankUConnectionException | \Illuminate\Http\Client\RequestException $e) {
            $this->refundQuietly($companyId, $apiKey, $debit);
            throw $e;
        }

        if (! $response->success) {
            $this->refundQuietly($companyId, $apiKey, $debit);
        }

        return $response;
    }

    private function refundQuietly(int $companyId, string $apiKey, CompanyWalletTransaction $debit): void
    {
        try {
            $this->wallet->refund($companyId, $debit);
        } catch (\Throwable $e) {
            // A refund-write failure must not turn an already-handled BankU
            // failure into a 500 -- log it for manual reconciliation instead.
            Log::error('CompanyWalletService refund failed after a failed BankU call', [
                'company_id' => $companyId,
                'api_key' => $apiKey,
                'debit_transaction_id' => $debit->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
