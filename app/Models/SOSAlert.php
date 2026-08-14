<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SOSAlert extends Model
{

    protected $table = 'sos_alerts';
    protected $fillable = [
        'driver_id',
        'message',
        'latitude',
        'longitude',
    ];

    public $timestamps = true;

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id','id');
    }
}
