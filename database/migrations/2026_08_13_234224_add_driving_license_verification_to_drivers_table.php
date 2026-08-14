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
        Schema::table('drivers', function (Blueprint $table) {
            $table->timestamp('driving_license_verified_at')->nullable()->after('driving_license_number');
            $table->json('driving_license_verification_data')->nullable()->after('driving_license_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn(['driving_license_verified_at', 'driving_license_verification_data']);
        });
    }
};
