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
        Schema::create('delivery_schedule_shop_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('delivery_schedule_shop_id');
            $table->string('title')->nullable();
            $table->string('unit_or_box')->nullable();
            $table->bigInteger('qty')->default(0);
            $table->timestamps();

            $table->foreign('delivery_schedule_shop_id', 'dsshop_products_dsshop_id_fk')->references('id')->on('delivery_schedule_shops')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_schedule_shop_products');
    }
};
