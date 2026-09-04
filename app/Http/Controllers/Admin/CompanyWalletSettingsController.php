<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyApiRate;
use App\Models\CompanyWallet;
use App\Models\CompanyWalletTransaction;
use App\Models\User;
use App\Services\Wallet\CompanyWalletService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * Super-Admin-only administration of a company's BankU API rates
 * (enable/disable + amount per document type) and wallet (balance, manual
 * top-up, transaction history), plus a read-only self-view for the Company
 * role to check its own balance/history. Kept separate from
 * CompanyController, which carries the company's own onboarding/KYC
 * concerns.
 */
class CompanyWalletSettingsController extends Controller implements HasMiddleware
{
    public function __construct(private readonly CompanyWalletService $wallet)
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Wallet Settings Show', only: ['edit']),
            new Middleware('permission:Wallet Settings Edit', only: ['update']),
            new Middleware('permission:Wallet Management Show', only: ['wallet']),
            new Middleware('permission:Wallet Management Edit', only: ['topUp']),
            new Middleware('permission:Wallet Show', only: ['myWallet']),
        ];
    }

    public function edit(User $company)
    {
        abort_unless($company->hasRole('Company'), 404);

        CompanyApiRate::ensureDefaultsFor($company->id);
        $rates = CompanyApiRate::where('company_id', $company->id)->get()->keyBy('api_key');

        return view('admin.company.wallet-settings', compact('company', 'rates'));
    }

    public function update(Request $request, User $company)
    {
        abort_unless($company->hasRole('Company'), 404);

        $validated = $request->validate([
            'rates' => 'required|array',
            'rates.*.enabled' => 'nullable|boolean',
            'rates.*.amount' => 'required|numeric|min:0|max:9999.99',
        ]);

        foreach (CompanyApiRate::API_TYPES as $key => $label) {
            CompanyApiRate::updateOrCreate(
                ['company_id' => $company->id, 'api_key' => $key],
                [
                    'amount' => $validated['rates'][$key]['amount'] ?? 0,
                    'is_enabled' => (bool) ($validated['rates'][$key]['enabled'] ?? false),
                ]
            );
        }

        return redirect()->back()->with('success', 'Wallet API rate settings updated successfully.');
    }

    public function wallet(User $company)
    {
        abort_unless($company->hasRole('Company'), 404);

        return $this->renderWallet($company, selfView: false);
    }

    /**
     * Read-only self-view for the Company role: their own balance and
     * transaction history, no top-up form, no rate-settings link -- both
     * of those stay behind Wallet Management Edit / Wallet Settings Show,
     * which Company doesn't have.
     */
    public function myWallet()
    {
        $companyId = auth()->user()->companyId();
        abort_unless($companyId, 403);

        $company = User::findOrFail($companyId);

        return $this->renderWallet($company, selfView: true);
    }

    private function renderWallet(User $company, bool $selfView)
    {
        $wallet = CompanyWallet::firstOrNew(['company_id' => $company->id], ['balance' => 0]);
        $transactions = CompanyWalletTransaction::where('company_id', $company->id)
            ->latest()
            ->paginate(20);

        return view('admin.company.wallet', compact('company', 'wallet', 'transactions', 'selfView'));
    }

    public function topUp(Request $request, User $company)
    {
        abort_unless($company->hasRole('Company'), 404);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:1000000',
            'note' => 'required|string|max:255',
        ]);

        $this->wallet->manualTopUp(
            $company->id,
            (float) $validated['amount'],
            $validated['note'],
            auth()->id(),
        );

        return redirect()->back()->with('success', 'Wallet topped up successfully.');
    }
}
