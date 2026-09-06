<?php

namespace App\Services\Wallet;

use App\Models\CompanyApiRate;
use App\Models\CompanyWalletTransaction;
use App\Models\User;
use App\Services\Wallet\Exceptions\ApiCallDisabledException;
use App\Services\Wallet\Exceptions\InsufficientWalletBalanceException;
use Illuminate\Support\Facades\DB;

class CompanyWalletService
{
    /**
     * Gate + atomically charge a company's wallet for one API call.
     *
     * Throws before touching the wallet if the API isn't configured/enabled
     * for this company. Otherwise deducts the configured amount via a single
     * guarded UPDATE ... WHERE balance >= amount, so two concurrent calls on
     * the same company can never both succeed when only one could be
     * afforded -- InnoDB takes the row's exclusive lock as part of
     * evaluating that one statement, so there is no read-then-write gap for
     * a second transaction to race through.
     *
     * $actorUserId is the Company/Branch/Employee user who triggered the
     * call (Drivers never trigger these themselves -- they have no admin
     * panel access), recorded on the debit row for the company's API-usage
     * report. Null is fine (e.g. a scripted/system call with no acting user).
     *
     * @throws ApiCallDisabledException
     * @throws InsufficientWalletBalanceException
     */
    public function chargeForApiCall(int $companyId, string $apiKey, ?int $actorUserId = null): CompanyWalletTransaction
    {
        $rate = CompanyApiRate::where('company_id', $companyId)->where('api_key', $apiKey)->first();

        if (! $rate || ! $rate->is_enabled) {
            throw new ApiCallDisabledException(
                "This company is not configured to use the {$apiKey} verification API."
            );
        }

        $amount = (float) $rate->amount;
        $branchUserId = $this->resolveBranchUserId($actorUserId);

        return DB::transaction(function () use ($companyId, $apiKey, $amount, $actorUserId, $branchUserId) {
            $this->ensureWalletRow($companyId);

            // Zero-cost APIs always succeed and skip the guarded decrement
            // entirely -- an UPDATE that changes no column value (balance -= 0)
            // can report 0 "affected rows" under PHP's default (non-
            // CLIENT_FOUND_ROWS) MySQL driver, which would be misread as
            // insufficient balance.
            if ($amount > 0) {
                $affected = DB::table('company_wallets')
                    ->where('company_id', $companyId)
                    ->where('balance', '>=', $amount)
                    ->decrement('balance', $amount, ['updated_at' => now()]);

                if ($affected === 0) {
                    throw new InsufficientWalletBalanceException(
                        'Insufficient Wallet Balance. Your wallet balance is insufficient to verify the documents. '
                        . 'Please contact the Super Admin to recharge your wallet.'
                    );
                }
            }

            return CompanyWalletTransaction::create([
                'company_id' => $companyId,
                'type' => 'debit',
                'amount' => $amount,
                'balance_after' => $this->currentBalance($companyId),
                'description' => ucfirst(str_replace('_', ' ', $apiKey)) . ' verification charge',
                'reference_type' => $apiKey,
                'actor_user_id' => $actorUserId,
                'branch_user_id' => $branchUserId,
            ]);
        });
    }

    /**
     * Resolve which Branch (a User row with role Branch) the given actor
     * belongs to: the actor themselves if they ARE a Branch, or their
     * assigned branch if they're an Employee. Null for a Company actor (no
     * branch context) or a Driver (never an actor here in practice).
     */
    private function resolveBranchUserId(?int $actorUserId): ?int
    {
        if (! $actorUserId) {
            return null;
        }

        $actor = User::find($actorUserId);
        if (! $actor) {
            return null;
        }

        if ($actor->hasRole('Branch')) {
            return $actor->id;
        }

        if ($actor->hasRole('Employee')) {
            return $actor->employee?->branch_id;
        }

        return null;
    }

    /**
     * Credit back a previously-charged amount (a failed BankU call after a
     * successful charge). Records a `credit` ledger row pointing at the
     * debit it reverses.
     */
    public function refund(int $companyId, CompanyWalletTransaction $originalDebit): CompanyWalletTransaction
    {
        return DB::transaction(function () use ($companyId, $originalDebit) {
            $this->ensureWalletRow($companyId);

            DB::table('company_wallets')->where('company_id', $companyId)
                ->increment('balance', (float) $originalDebit->amount, ['updated_at' => now()]);

            return CompanyWalletTransaction::create([
                'company_id' => $companyId,
                'type' => 'credit',
                'amount' => $originalDebit->amount,
                'balance_after' => $this->currentBalance($companyId),
                'description' => 'Refund: ' . $originalDebit->description . ' (call failed)',
                'reference_type' => $originalDebit->reference_type,
                'reference_id' => $originalDebit->id,
            ]);
        });
    }

    /**
     * Manual Super Admin top-up. Immediate credit, no payment gateway.
     */
    public function manualTopUp(int $companyId, float $amount, string $note, int $adminUserId): CompanyWalletTransaction
    {
        return DB::transaction(function () use ($companyId, $amount, $note, $adminUserId) {
            $this->ensureWalletRow($companyId);

            DB::table('company_wallets')->where('company_id', $companyId)
                ->increment('balance', $amount, ['updated_at' => now()]);

            return CompanyWalletTransaction::create([
                'company_id' => $companyId,
                'type' => 'credit',
                'amount' => $amount,
                'balance_after' => $this->currentBalance($companyId),
                'description' => $note,
                'reference_type' => 'topup',
                'created_by' => $adminUserId,
            ]);
        });
    }

    /**
     * Create the wallet row if this company doesn't have one yet. Relies on
     * the company_id unique constraint: a concurrent duplicate insert blocks
     * on the unique index and becomes a no-op once the first commits, so
     * this is race-safe for a company's very first charge/top-up.
     */
    private function ensureWalletRow(int $companyId): void
    {
        DB::table('company_wallets')->insertOrIgnore([
            'company_id' => $companyId,
            'balance' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function currentBalance(int $companyId): float
    {
        return (float) DB::table('company_wallets')->where('company_id', $companyId)->value('balance');
    }
}
