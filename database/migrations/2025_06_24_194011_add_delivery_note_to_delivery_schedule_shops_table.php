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
            $table->text('delivery_note')->nullable()->after('order_serial');
            $table->enum('payment_type',['Pre-Paid', 'COD'])->nullable()->after('delivery_note');
            $table->decimal('amount', 10, 2)->default(0.00)->nullable()->after('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_schedule_shops', function (Blueprint $table) {
            //
            $table->dropColumn('delivery_note');
            $table->dropColumn('payment_type');
            $table->dropColumn('amount');

        });
    }
};
