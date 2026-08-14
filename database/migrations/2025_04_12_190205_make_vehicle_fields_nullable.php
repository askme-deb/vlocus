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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('vehicle_number')->nullable()->change();
            $table->string('engine_number')->nullable()->change();
            $table->string('rwc_number')->nullable()->change();
            $table->unsignedBigInteger('brand_id')->nullable()->change();
            $table->unsignedBigInteger('model_id')->nullable()->change();
            $table->unsignedBigInteger('color_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->string('vehicle_number')->nullable(false)->change();
            $table->string('engine_number')->nullable(false)->change();
            $table->string('rwc_number')->nullable(false)->change();
            $table->unsignedBigInteger('brand_id')->nullable(false)->change();
            $table->unsignedBigInteger('model_id')->nullable(false)->change();
            $table->unsignedBigInteger('color_id')->nullable(false)->change();
        });
    }
};
