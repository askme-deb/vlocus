<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Records who actually triggered an API-charge debit (a Company/Branch/
     * Employee user operating the admin panel -- Drivers never trigger these
     * calls themselves) and, for Employee/Branch actors, which Branch they
     * belong to. Both nullable: only ever set on debit rows created via
     * CompanyWalletService::chargeForApiCall(); top-ups/refunds leave them
     * null (created_by already tracks the admin for a top-up).
     *
     * branch_user_id is denormalized at write time (rather than resolved via
     * a join through employees/branches on every read) so filtering the
     * company's API-usage report by branch is a plain indexed WHERE.
     */
    public function up(): void
    {
        Schema::table('company_wallet_transactions', function (Blueprint $table) {
            $table->foreignId('actor_user_id')->nullable()->after('created_by')
                ->constrained('users')->nullOnDelete();
            $table->foreignId('branch_user_id')->nullable()->after('actor_user_id')
                ->constrained('users')->nullOnDelete();

            $table->index(['company_id', 'actor_user_id']);
            $table->index(['company_id', 'branch_user_id']);
            $table->index(['company_id', 'reference_type']);
        });
    }

    public function down(): void
    {
        Schema::table('company_wallet_transactions', function (Blueprint $table) {
            $table->dropForeign(['actor_user_id']);
            $table->dropForeign(['branch_user_id']);
            $table->dropColumn(['actor_user_id', 'branch_user_id']);
        });
    }
};
