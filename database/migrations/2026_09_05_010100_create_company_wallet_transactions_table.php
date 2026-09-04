<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Append-only ledger for company_wallets. Every debit (API charge) and
     * credit (manual top-up, refund) gets a row, with the resulting balance
     * snapshotted so history doesn't depend on replaying the whole ledger.
     */
    public function up(): void
    {
        Schema::create('company_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['credit', 'debit']);
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->string('description');
            // 'pan'|'aadhaar'|'driving_licence'|'rc'|'gstin'|'topup'
            $table->string('reference_type', 40)->nullable();
            // For a refund row: the id of the debit transaction it reverses.
            $table->unsignedBigInteger('reference_id')->nullable();
            // Set for manual top-ups (the Super Admin who credited it); null for
            // system-generated debit/refund rows.
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['company_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_wallet_transactions');
    }
};
