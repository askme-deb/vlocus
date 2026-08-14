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
        Schema::table('delivery_schedule_shops', function (Blueprint $table) {
            //
             $table->enum('status', ['pending', 'accepted','rejected','cancelled', 'expired'])->default('pending')->after('otp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_schedule_shops', function (Blueprint $table) {
            //
             $table->dropColumn('status');
        });
    }
};
