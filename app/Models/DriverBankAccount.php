<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverBankAccount extends Model
{
    protected $guarded = ['id'];

    protected $hidden = ['account_number', 'ifsc_response', 'bank_response', 'ifsc_idempotency_key', 'bank_idempotency_key'];

    protected $casts = [
        'account_number' => 'encrypted',
        'ifsc_response' => 'encrypted:array',
        'bank_response' => 'encrypted:array',
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function maskedAccountNumber(): string
    {
        return str_repeat('*', max(0, strlen($this->account_number) - 4)) . substr($this->account_number, -4);
    }
}
