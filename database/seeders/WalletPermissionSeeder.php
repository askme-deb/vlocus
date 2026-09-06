<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Grants the new company wallet/API-rate permissions.
 *
 * - Wallet Settings (Show/Edit) and Wallet Management (Show/Edit) are
 *   Super-Admin-only: rate configuration and top-up are administrative
 *   actions a company never performs on itself.
 * - Wallet Show is a read-only self-view (balance + a usage log/statement,
 *   no top-up, no rate editing) granted to Company, Branch, and Employee.
 *   CompanyWalletSettingsController::myWallet() further scopes what each
 *   role actually sees within that page: Company sees everyone under it,
 *   a Branch is locked to itself + its own employees, an Employee is
 *   locked to just their own activity -- this permission only gates
 *   reaching the page at all, not which rows within it are visible.
 *
 * Uses givePermissionTo() only (never syncPermissions()) so this is purely
 * additive and never touches a role's existing permission assignments.
 * Super Admin bypasses all checks via Gate::before regardless, but is
 * granted explicitly here to match the existing convention (see
 * DefaultModulePermissionSeeder) so the Role/Permission admin UI stays
 * accurate.
 *
 * Not wired into DatabaseSeeder::run() -- run manually once via:
 *   php artisan db:seed --class=WalletPermissionSeeder
 */
class WalletPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $adminOnly = [
            'Wallet Settings Show', 'Wallet Settings Edit',
            'Wallet Management Show', 'Wallet Management Edit',
        ];

        if ($role = Role::where('name', 'Super Admin')->first()) {
            $role->givePermissionTo(array_merge($adminOnly, ['Wallet Show']));
        }

        foreach (['Company', 'Branch', 'Employee'] as $roleName) {
            if ($role = Role::where('name', $roleName)->first()) {
                $role->givePermissionTo(['Wallet Show']);
            }
        }
    }
}
