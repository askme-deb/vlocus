<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyWallet extends Model
{
    protected $fillable = ['company_id', 'balance'];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function transactions()
    {
        return $this->hasMany(CompanyWalletTransaction::class, 'company_id', 'company_id');
    }
}
