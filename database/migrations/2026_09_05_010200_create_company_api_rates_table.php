<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Per-company, per-document-type pricing for BankU verification calls
     * (see App\Models\CompanyApiRate::API_TYPES for the fixed key list).
     * is_enabled defaults false: an unconfigured row must behave exactly
     * like a missing row (fail-safe -- a company can't be charged/blocked
     * before the Super Admin explicitly configures and enables it).
     */
    public function up(): void
    {
        Schema::create('company_api_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('users')->cascadeOnDelete();
            $table->string('api_key', 40);
            $table->decimal('amount', 8, 2)->default(0);
            $table->boolean('is_enabled')->default(false);
            $table->timestamps();

            $table->unique(['company_id', 'api_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_api_rates');
    }
};
