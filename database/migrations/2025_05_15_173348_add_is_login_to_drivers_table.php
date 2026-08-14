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
        Schema::table('drivers', function (Blueprint $table) {
            $table->tinyInteger('is_online')->default(0)->after('driving_exprience'); // 0 = offline, 1 = online
            $table->tinyInteger('ride_mode')->default(0)->after('is_online');         // 0 = not accepting rides, 1 = active
            $table->decimal('latitude', 10, 7)->nullable()->after('ride_mode');       // more accurate than string
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            //
            $table->dropColumn('is_online');
            $table->dropColumn('ride_mode');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');



        });
    }
};
