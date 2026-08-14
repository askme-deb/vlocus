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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('vehicle_number');
            $table->string('engine_number');
            $table->string('rwc_number');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('color_id');
            $table->bigInteger('seating_capacity')->nullable();
            $table->string('body_type')->nullable();
            $table->string('vehicle_condition')->nullable();
            $table->string('transmisssion')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('engine_type')->nullable();
            $table->string('left_hand_drive')->nullable();
            $table->string('hybird')->nullable();
            $table->string('description')->nullable();
            $table->tinyInteger('is_visible')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
