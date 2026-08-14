<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Grants the newly-added module permissions (Dashboard, Plan, Route Playback,
 * SOS Alert, Settings, Tracking) to existing roles, so enforcing them doesn't
 * regress access for roles that previously reached these pages unchecked.
 *
 * Uses givePermissionTo() only (never syncPermissions()) so this is purely
 * additive and never touches a role's existing permission assignments.
 */
class DefaultModulePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $broadAccess = [
            'Dashboard Show',
            'Plan Show', 'Plan Create', 'Plan Edit', 'Plan Delete',
            'Route Playback Show',
            'SOS Alert Show', 'SOS Alert Delete',
            'Tracking Show',
        ];

        $narrowAccess = [
            'Dashboard Show',
            'Route Playback Show',
            'SOS Alert Show',
            'Tracking Show',
        ];

        $settingsOnly = [
            'Settings Show', 'Settings Edit', 'Settings Delete',
        ];

        $allNewPermissions = array_unique(array_merge($broadAccess, $settingsOnly));

        if ($role = Role::where('name', 'Company')->first()) {
            $role->givePermissionTo($broadAccess);
        }

        if ($role = Role::where('name', 'Employee')->first()) {
            $role->givePermissionTo($broadAccess);
        }

        if ($role = Role::where('name', 'Branch')->first()) {
            $role->givePermissionTo($narrowAccess);
        }

        // Settings is intentionally not granted to Company/Branch/Employee --
        // nothing in their existing permission sets suggests app-wide config
        // access. Super Admin bypasses all checks via Gate::before regardless,
        // but is granted explicitly here to match the existing convention
        // (its other 82 permissions are stored the same way) so the
        // Role/Permission admin UI stays accurate.
        if ($role = Role::where('name', 'Super Admin')->first()) {
            $role->givePermissionTo($allNewPermissions);
        }

        // Driver, User, Admin: no grants. Driver has real users but is
        // confirmed mobile-API-only (doesn't use the web admin panel);
        // Admin/User currently have zero assigned users.
    }
}
