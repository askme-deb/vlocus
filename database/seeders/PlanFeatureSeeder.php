<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\Feature;
use App\Models\PlanFeature;

class PlanFeatureSeeder extends Seeder
{
    public function run(): void
    {
        // --- Create Plans ---
        $plans = [
            ['name' => 'Basic', 'description' => 'Small operators / <10 vehicles', 'price' => 499, 'coins' => 499, 'task_coin_cost' => 5, 'is_active' => 1],
            ['name' => 'Professional', 'description' => 'Growing fleets / 10–100 vehicles', 'price' => 999, 'coins' => 999, 'task_coin_cost' => 3, 'is_active' => 1],
            ['name' => 'Enterprise', 'description' => 'Large fleets / logistics & transport companies', 'price' => 1999, 'coins' => 1999, 'task_coin_cost' => 1, 'is_active' => 1],
        ];

        foreach ($plans as $planData) {
            Plan::updateOrCreate(['name' => $planData['name']], $planData);
        }

        $planBasic = Plan::where('name', 'Basic')->first();
        $planPro = Plan::where('name', 'Professional')->first();
        $planEnt = Plan::where('name', 'Enterprise')->first();

        // --- Define Features ---
        $features = [
            'driver_application' => 'Driver Application',
            'route_navigate' => 'Route Navigate',
            'routing' => 'Routing',
            'multi_task_upload' => 'Multi Task Upload',
            'access_maps_address' => 'Access Maps Address',
            'access_driver_database' => 'Access All Driver Database',
            'driver_verification' => 'Driver Verification',
            'rc_verification' => 'RC Verification',
            'otp_validation' => 'OTP Validation',
            'photo_upload' => 'Photo Upload',
            'signature_upload' => 'Signature Upload',
            'sms_integration' => 'SMS Integration',
            'whatsapp_integration' => 'WhatsApp Integration',
            'support_maintenance' => 'Support & Maintenance',
            'api_access' => 'API Access',
            'analytics_dashboard' => 'Analytics Dashboard',
            'custom_branding' => 'Custom Branding',
            'data_retention' => 'Data Retention',
        ];

        foreach ($features as $code => $name) {
            Feature::updateOrCreate(['slug' => $code], ['name' => $name, 'category' => 'general']);
        }
    }
}
