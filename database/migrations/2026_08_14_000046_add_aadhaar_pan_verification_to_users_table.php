<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('aadhaar_verified_at')->nullable()->after('aadhar_card_number');
            $table->json('aadhaar_verification_data')->nullable()->after('aadhaar_verified_at');
            $table->timestamp('pan_verified_at')->nullable()->after('aadhaar_verification_data');
            $table->json('pan_verification_data')->nullable()->after('pan_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'aadhaar_verified_at',
                'aadhaar_verification_data',
                'pan_verified_at',
                'pan_verification_data',
            ]);
        });
    }
};
