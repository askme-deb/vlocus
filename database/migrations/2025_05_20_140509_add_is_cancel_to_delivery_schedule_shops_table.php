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

            $table->tinyInteger('is_cancelled')->default(0)->after('delivered_at');
            $table->timestamp('cancelled_at')->nullable()->after('is_cancelled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_schedule_shops', function (Blueprint $table) {
            //
            $table->dropColumn('is_cancelled');
            $table->dropColumn('cancelled_at');


        });
    }
};
