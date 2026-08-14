<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class VehicleType extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'is_set_price_management',
        'is_visible',
    ];

    public function cabFair()
    {
        return $this->hasOne(CabFair::class, 'vehicle_type_id');
    }
}
