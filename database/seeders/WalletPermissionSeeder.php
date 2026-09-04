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
 * - Wallet Show is a read-only self-view (balance + transaction history,
 *   no top-up, no rate editing) granted to the Company role only -- not
 *   Branch/Employee, per explicit decision.
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

        if ($role = Role::where('name', 'Company')->first()) {
            $role->givePermissionTo(['Wallet Show']);
        }
    }
}
