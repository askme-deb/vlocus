<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * RC (Vehicle Registration Certificate) detail columns, populated from the
     * BankU RC verify API on the vehicle create screen. Everything is nullable
     * so a vehicle can still be saved when the lookup only returns a partial
     * record. Dates are kept as plain strings because upstream formats vary
     * (dd-mm-yyyy, dd-MMM-yyyy, ...) and are shown back verbatim.
     */
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('rc_status')->nullable()->after('rc_verification_data');
            $table->string('vehicle_class')->nullable()->after('rc_status');
            $table->string('chassis_number')->nullable()->after('vehicle_class');
            $table->string('manufacturer')->nullable()->after('chassis_number');
            $table->string('model_name')->nullable()->after('manufacturer');
            $table->string('colour')->nullable()->after('model_name');
            $table->string('emission_norm')->nullable()->after('colour');
            $table->string('owner_name')->nullable()->after('emission_norm');
            $table->string('registration_date')->nullable()->after('owner_name');
            $table->string('rc_expiry_date')->nullable()->after('registration_date');
            $table->string('tax_upto')->nullable()->after('rc_expiry_date');
            $table->string('insurance_company')->nullable()->after('tax_upto');
            $table->string('insurance_upto')->nullable()->after('insurance_company');
            $table->string('financer')->nullable()->after('insurance_upto');
            $table->text('owner_address')->nullable()->after('financer');
            $table->string('cubic_capacity')->nullable()->after('owner_address');
            $table->string('gross_weight')->nullable()->after('cubic_capacity');
            $table->string('seat_capacity')->nullable()->after('gross_weight');
            $table->string('sleeper_capacity')->nullable()->after('seat_capacity');
            $table->string('pucc_number')->nullable()->after('sleeper_capacity');
            $table->string('pucc_upto')->nullable()->after('pucc_number');
            $table->string('permit_type')->nullable()->after('pucc_upto');
            $table->string('permit_valid_upto')->nullable()->after('permit_type');
            $table->string('national_permit_number')->nullable()->after('permit_valid_upto');
            $table->string('national_permit_upto')->nullable()->after('national_permit_number');
            $table->boolean('is_commercial')->nullable()->after('national_permit_upto');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'rc_status',
                'vehicle_class',
                'chassis_number',
                'manufacturer',
                'model_name',
                'colour',
                'emission_norm',
                'owner_name',
                'registration_date',
                'rc_expiry_date',
                'tax_upto',
                'insurance_company',
                'insurance_upto',
                'financer',
                'owner_address',
                'cubic_capacity',
                'gross_weight',
                'seat_capacity',
                'sleeper_capacity',
                'pucc_number',
                'pucc_upto',
                'permit_type',
                'permit_valid_upto',
                'national_permit_number',
                'national_permit_upto',
                'is_commercial',
            ]);
        });
    }
};
