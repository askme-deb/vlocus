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
        Schema::table('shops', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('shop_number')->unique()->nullable()->after('id');
        });
          DB::statement('UPDATE shops SET shop_number = id + 10000');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            //
            $table->dropColumn('shop_number');

        });
    }
};
