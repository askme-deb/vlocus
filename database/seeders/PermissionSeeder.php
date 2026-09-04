<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'Permission Create',
            'Permission Show',
            'Permission Edit',
            'Permission Delete',
            'Role Show',
            'Role Create',
            'Role Edit',
            'Role Delete',
            'Asign Permission',

            'Branch Show',
            'Branch Create',
            'Branch Edit',
            'Branch Delete',

            'Brand Show',
            'Brand Create',
            'Brand Edit',
            'Brand Delete',

            'Color Show',
            'Color Create',
            'Color Edit',
            'Color Delete',

            'Company Show',
            'Company Create',
            'Company Edit',
            'Company Delete',

            'Company Show',
            'Company Create',
            'Company Edit',
            'Company Delete',

            'Wallet Settings Show',
            'Wallet Settings Edit',
            'Wallet Management Show',
            'Wallet Management Edit',
            'Wallet Show',

            'Contact Us Show',
            'Contact Us Delete',

            'Delivery Schedule Show',
            'Delivery Schedule Create',
            'Delivery Schedule Edit',
            'Delivery Schedule Delete',

            'Driver Show',
            'Driver Create',
            'Driver Edit',
            'Driver Delete',
            'Driver Bulk Upload Template',
            'Driver Bulk Upload',

            'Model Show',
            'Model Create',
            'Model Edit',
            'Model Delete',

            'Shop Show',
            'Shop Create',
            'Shop Edit',
            'Shop Delete',
            'Shop Bulk Upload Template',
            'Shop Bulk Upload',

            'System User Show',
            'System User Create',
            'System User Edit',
            'System User Delete',

            'User Show',
            'User Create',
            'User Edit',
            'User Delete',

            'Vehicle Show',
            'Vehicle Create',
            'Vehicle Edit',
            'Vehicle Delete',
            'Vehicle Bulk Upload Template',
            'Vehicle Bulk Upload',

            'Vehicle Type Show',
            'Vehicle Type Create',
            'Vehicle Type Edit',
            'Vehicle Type Delete',

            'Employee Show',
            'Employee Create',
            'Employee Edit',
            'Employee Delete',

            'Trip Summary Report Show',
            'Route History Report Show',
            'Run Idle Report Show',
            'Distance Report Show',
            'Geo Fence Report Show',
            'Overstay Report Show',
            'Attendance Report Show',
            'Login Logout Report Show',
            'Login Time Report Show',
            'Emergency SOS Report Show',
            'Task Report Show',
            'Dispatch Details Report Show',

            'Dashboard Show',

            'Plan Show',
            'Plan Create',
            'Plan Edit',
            'Plan Delete',

            'Route Playback Show',

            'SOS Alert Show',
            'SOS Alert Delete',

            'Settings Show',
            'Settings Edit',
            'Settings Delete',

            'Tracking Show',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }
    }
}
