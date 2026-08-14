<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::table('delivery_schedule_shops', function (Blueprint $table) {
            if (!Schema::hasColumn('delivery_schedule_shops', 'lr_no')) {
                $table->unsignedBigInteger('lr_no')->nullable()->after('id');
            }
            if (!Schema::hasColumn('delivery_schedule_shops', 'invoice_no')) {
                $table->string('invoice_no')->nullable()->after('order_serial');
            }
        });

        DB::statement('UPDATE delivery_schedule_shops SET lr_no = id + 10000');

        Schema::table('delivery_schedule_shops', function (Blueprint $table) {
            $table->unique('lr_no');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_schedule_shops', function (Blueprint $table) {
            //
           $table->dropUnique(['lr_no']);
            $table->dropColumn('lr_no');
            $table->dropColumn('invoice_no');
        });
    }
};
