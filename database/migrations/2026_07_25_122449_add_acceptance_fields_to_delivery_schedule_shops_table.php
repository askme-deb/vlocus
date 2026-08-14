<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * App\Http\Controllers\Api\DeliveryController::update_delivery() and
     * App\Http\Controllers\Admin\ReportController (trip summary, route
     * history, run/idle) already read/write is_accepted, accepted_at,
     * accepted_lat, accepted_long and app_serial on delivery_schedule_shops,
     * but no prior migration actually creates these columns. Add them here
     * so those existing code paths (and the new Task Report) work.
     */
    public function up(): void
    {
        Schema::table('delivery_schedule_shops', function (Blueprint $table) {
            if (!Schema::hasColumn('delivery_schedule_shops', 'is_accepted')) {
                $table->tinyInteger('is_accepted')->default(0)->after('status');
            }
            if (!Schema::hasColumn('delivery_schedule_shops', 'accepted_at')) {
                $table->timestamp('accepted_at')->nullable()->after('is_accepted');
            }
            if (!Schema::hasColumn('delivery_schedule_shops', 'accepted_lat')) {
                $table->string('accepted_lat')->nullable()->after('accepted_at');
            }
            if (!Schema::hasColumn('delivery_schedule_shops', 'accepted_long')) {
                $table->string('accepted_long')->nullable()->after('accepted_lat');
            }
            if (!Schema::hasColumn('delivery_schedule_shops', 'app_serial')) {
                $table->integer('app_serial')->nullable()->after('order_serial');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_schedule_shops', function (Blueprint $table) {
            foreach (['is_accepted', 'accepted_at', 'accepted_lat', 'accepted_long', 'app_serial'] as $column) {
                if (Schema::hasColumn('delivery_schedule_shops', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
