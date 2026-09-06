<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('bank_name');
            $table->string('branch_name');
            $table->text('account_number');
            $table->string('account_holder_name');
            $table->string('ifsc', 11);
            $table->string('status')->default('draft');
            $table->uuid('verification_reference')->unique();
            $table->uuid('ifsc_idempotency_key');
            $table->uuid('bank_idempotency_key');
            $table->longText('ifsc_response')->nullable();
            $table->longText('bank_response')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_bank_accounts');
    }
};
