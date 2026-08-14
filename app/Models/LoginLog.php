<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    //

    protected $fillable = [
        'user_id', 'device','device_id','ip_address', 'status'
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}