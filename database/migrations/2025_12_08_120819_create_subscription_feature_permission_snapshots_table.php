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
        Schema::create('subscription_feature_permission_snapshots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('feature_snapshot_id');
            $table->unsignedBigInteger('permission_snapshot_id');
            
            // SHORT FK NAMES
            $table->foreign('feature_snapshot_id', 'sfps_fsid_fk')
                ->references('id')
                ->on('subscription_feature_snapshots')
                ->onDelete('cascade');

            $table->foreign('permission_snapshot_id', 'sfps_psid_fk')
                ->references('id')
                ->on('subscription_permission_snapshots')
                ->onDelete('cascade');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_feature_permission_snapshots');
    }
};
