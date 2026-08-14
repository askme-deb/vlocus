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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id');

            // Subscription details
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Renew logic
            $table->boolean('is_recurring')->default(false);

            // Status examples: active, expired, cancelled, pending
            $table->string('status')->default('active');

            // Snapshot plan data (price, coins etc at purchase time)
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('coins')->nullable();
            $table->integer('task_coin_cost')->nullable();
            $table->integer('max_tasks')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
