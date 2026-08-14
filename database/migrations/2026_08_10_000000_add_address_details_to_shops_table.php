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
            $table->string('shop_street_address')->nullable()->after('shop_address');
            $table->string('shop_district')->nullable()->after('shop_street_address');
            $table->string('shop_pincode')->nullable()->after('shop_district');
            $table->string('shop_state')->nullable()->after('shop_pincode');
            $table->string('shop_country')->nullable()->after('shop_state');
            $table->string('shop_registration_number')->nullable()->after('shop_country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn([
                'shop_street_address',
                'shop_district',
                'shop_pincode',
                'shop_state',
                'shop_country',
                'shop_registration_number',
            ]);
        });
    }
};
