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
use Illuminate\Support\Facades\DB;

/**
 * Super-Admin-only administration of a company's BankU API rates
 * (enable/disable + amount per document type) and wallet (balance, manual
 * top-up, transaction history), plus a read-only self-view (myWallet())
 * for Company/Branch/Employee to check their own balance/usage, scoped to
 * what each role is allowed to see. Kept separate from CompanyController,
 * which carries the company's own onboarding/KYC concerns.
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
     * Read-only self-view: the company's balance, and a filterable
     * (Branch / User / API type / date range) log + per-user statement of
     * API calls made under the company -- no amounts, no rate-settings
     * link, both of which stay behind Wallet Management Edit / Wallet
     * Settings Show, which none of Company/Branch/Employee have.
     *
     * What's actually visible is scoped by the acting user's own role,
     * enforced here server-side (not merely as filter defaults, so it
     * can't be bypassed by editing the query string):
     *   - Company: everything under the company.
     *   - Branch:  only itself + its own employees.
     *   - Employee: only their own activity.
     */
    public function myWallet(Request $request)
    {
        $actingUser = auth()->user();
        $companyId = $actingUser->companyId();
        abort_unless($companyId, 403);

        $company = User::findOrFail($companyId);

        $isBranch = $actingUser->hasRole('Branch');
        $isEmployee = $actingUser->hasRole('Employee');

        // Locked (non-overridable) scope for this viewer. null means "no
        // restriction of this kind" (a Company sees the whole company).
        $lockedBranchUserId = $isBranch ? $actingUser->id : null;
        $lockedActorUserId = $isEmployee ? $actingUser->id : null;
        $canFilterBranch = ! $isBranch && ! $isEmployee;
        $canFilterUser = ! $isEmployee;

        // The wallet balance is a company-wide financial figure -- only the
        // Company role itself sees it; Branch/Employee only ever see usage
        // counts, never money.
        $canViewBalance = ! $isBranch && ! $isEmployee;

        $filters = $request->only(['branch_user_id', 'actor_user_id', 'api_key', 'date_from', 'date_to']);
        if ($lockedBranchUserId) {
            $filters['branch_user_id'] = $lockedBranchUserId;
        }
        if ($lockedActorUserId) {
            $filters['actor_user_id'] = $lockedActorUserId;
        }

        $usage = CompanyWalletTransaction::where('company_id', $companyId)
            ->where('type', 'debit')
            ->with(['actor', 'branchUser'])
            ->when($filters['branch_user_id'] ?? null, fn ($q, $v) => $q->where('branch_user_id', $v))
            ->when($filters['actor_user_id'] ?? null, fn ($q, $v) => $q->where('actor_user_id', $v))
            ->when($filters['api_key'] ?? null, fn ($q, $v) => $q->where('reference_type', $v))
            ->when($filters['date_from'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($filters['date_to'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Dropdown data + the "users" list for the Usage Statement table,
        // scoped to what this viewer is allowed to see at all.
        if ($isEmployee) {
            $branches = collect();
            $users = User::where('id', $actingUser->id)->get(['id', 'name']);
        } elseif ($isBranch) {
            $branches = collect();
            $users = User::where('id', $actingUser->id)->get(['id', 'name'])
                ->merge(User::role('Employee')->whereHas('employee', fn ($q) => $q->where('branch_id', $actingUser->id))->get(['id', 'name']));
        } else {
            $branches = User::role('Branch')->whereHas('branch', fn ($q) => $q->where('company_id', $companyId))->get(['id', 'name']);
            $users = User::where('id', $companyId)->get(['id', 'name'])
                ->merge(User::role('Branch')->whereHas('branch', fn ($q) => $q->where('company_id', $companyId))->get(['id', 'name']))
                ->merge(User::role('Employee')->whereHas('employee', fn ($q) => $q->where('company_id', $companyId))->get(['id', 'name']));
        }

        // Per-user statement: a count of calls per API type (+ total),
        // scoped the same as $usage above. Never collapsed by
        // actor_user_id/api_key from the request (only the locked value for
        // an Employee is applied) -- those would defeat the per-user/
        // per-type breakdown this table exists to show.
        $counts = CompanyWalletTransaction::where('company_id', $companyId)
            ->where('type', 'debit')
            ->when($filters['branch_user_id'] ?? null, fn ($q, $v) => $q->where('branch_user_id', $v))
            ->when($lockedActorUserId, fn ($q, $v) => $q->where('actor_user_id', $v))
            ->when($filters['date_from'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($filters['date_to'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->select('actor_user_id', 'reference_type', DB::raw('COUNT(*) as cnt'))
            ->groupBy('actor_user_id', 'reference_type')
            ->get();

        $statement = [];
        foreach ($counts as $row) {
            $uid = $row->actor_user_id ?? 0;
            $statement[$uid]['counts'][$row->reference_type] = (int) $row->cnt;
            $statement[$uid]['total'] = ($statement[$uid]['total'] ?? 0) + (int) $row->cnt;
        }

        $wallet = CompanyWallet::firstOrNew(['company_id' => $companyId], ['balance' => 0]);

        return view('admin.company.wallet', array_merge(
            compact('company', 'wallet', 'usage', 'branches', 'users', 'filters', 'statement', 'canFilterBranch', 'canFilterUser', 'canViewBalance'),
            ['transactions' => null, 'selfView' => true],
        ));
    }

    private function renderWallet(User $company, bool $selfView)
    {
        $wallet = CompanyWallet::firstOrNew(['company_id' => $company->id], ['balance' => 0]);

        // Transaction history (Type/Amount/Balance After/Description) is
        // Super-Admin-only -- the view never renders it for a self-view, so
        // skip the query entirely rather than paginating rows nobody sees.
        $transactions = $selfView
            ? null
            : CompanyWalletTransaction::where('company_id', $company->id)->latest()->paginate(20);

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
