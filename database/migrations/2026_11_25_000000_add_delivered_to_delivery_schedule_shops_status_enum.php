<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `delivery_schedule_shops` MODIFY `status` ENUM('pending','accepted','rejected','cancelled','expired','delivered') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE `delivery_schedule_shops` SET `status` = 'accepted' WHERE `status` = 'delivered'");
        DB::statement("ALTER TABLE `delivery_schedule_shops` MODIFY `status` ENUM('pending','accepted','rejected','cancelled','expired') NOT NULL DEFAULT 'pending'");
    }
};


