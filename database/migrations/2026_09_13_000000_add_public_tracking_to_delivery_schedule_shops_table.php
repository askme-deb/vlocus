<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds what the public, unauthenticated order-tracking page
     * (route: order.tracking) needs on top of the existing shop-level
     * delivery record:
     *  - tracking_token: the opaque, unguessable id used in the tracking
     *    URL instead of the row's sequential id.
     *  - receiver_name / receiver_phone: an optional per-order override of
     *    the shop's contact, set by the customer from the tracking page
     *    without mutating the shared Shop record.
     *  - rating / rated_at: the customer's post-delivery rating from the
     *    tracking page.
     */
    public function up(): void
    {
        Schema::table('delivery_schedule_shops', function (Blueprint $table) {
            if (!Schema::hasColumn('delivery_schedule_shops', 'tracking_token')) {
                $table->string('tracking_token', 64)->nullable()->unique()->after('otp');
            }
            if (!Schema::hasColumn('delivery_schedule_shops', 'receiver_name')) {
                $table->string('receiver_name')->nullable()->after('accepted_long');
            }
            if (!Schema::hasColumn('delivery_schedule_shops', 'receiver_phone')) {
                $table->string('receiver_phone')->nullable()->after('receiver_name');
            }
            if (!Schema::hasColumn('delivery_schedule_shops', 'rating')) {
                $table->unsignedTinyInteger('rating')->nullable()->after('receiver_phone');
            }
            if (!Schema::hasColumn('delivery_schedule_shops', 'rated_at')) {
                $table->timestamp('rated_at')->nullable()->after('rating');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_schedule_shops', function (Blueprint $table) {
            foreach (['tracking_token', 'receiver_name', 'receiver_phone', 'rating', 'rated_at'] as $column) {
                if (Schema::hasColumn('delivery_schedule_shops', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
