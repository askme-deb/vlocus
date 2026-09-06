<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyWalletTransaction extends Model
{
    protected $fillable = [
        'company_id',
        'type',
        'amount',
        'balance_after',
        'description',
        'reference_type',
        'reference_id',
        'created_by',
        'actor_user_id',
        'branch_user_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** The Company/Branch/Employee user who triggered an API-charge debit. */
    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }

    /** The Branch user the actor belongs to (null for a Company actor). */
    public function branchUser()
    {
        return $this->belongsTo(User::class, 'branch_user_id');
    }
}
