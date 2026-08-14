<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'latitude',
        'longitude',
    ];

    /**
     * A branch belongs to a user (creator/manager).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A branch belongs to a company (also in users table).
     */
    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }
}
