<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Vehicle extends Model implements HasMedia
{
    //
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'vehicle_number',
        'rwc_number',
        'rc_verified_at',
        'rc_verification_data',
        'engine_number',
        'brand_id',
        'model_id',
        'color_id',
        'seating_capacity',
        'body_type',
        'vehicle_condition',
        'transmisssion',
        'fuel_type',
        'left_hand_drive',
        'hybird',
        'engine_type',
        'description',
        'is_visible',
        'layout_id',
        'vehicle_type',
        'ac_status',
        'seat_booking_price',
        'route_id',
        'company_id',
    ];

    protected $casts = [
        'rc_verified_at' => 'datetime',
        'rc_verification_data' => 'array',
    ];

    public function layout()
    {
        return $this->belongsTo(VehicleLayout::class, 'layout_id', 'id');
    }
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type', 'id');
    }
    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id','id');
    }

    public function timeTable()
    {
        return $this->hasMany(TimeTable::class, 'vehicle_id');
    }

    public function journeys()
    {
        return $this->hasMany(Journey::class, 'vehicle_id');
    }
}
