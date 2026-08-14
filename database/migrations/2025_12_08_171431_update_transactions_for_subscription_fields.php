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
        Schema::table('transactions', function (Blueprint $table) {
            // Add missing plan purchase fields
            $table->unsignedBigInteger('plan_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('subscription_id')->nullable()->after('plan_id');
            $table->string('order_id')->nullable()->after('payment_id');
            $table->string('signature')->nullable()->after('order_id');

            // Foreign keys
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('set null');
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            // Drop FK first
            $table->dropForeign(['plan_id']);
            $table->dropForeign(['subscription_id']);

            // Then drop columns
            $table->dropColumn(['plan_id', 'subscription_id', 'order_id', 'signature']);
        });
    }
};
