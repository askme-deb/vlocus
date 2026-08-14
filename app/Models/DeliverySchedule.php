<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliverySchedule extends Model
{
    protected $fillable = ['delivery_date','order_id','route_code','driver_id','vehicle_id','status','delivery_note','payment_type','amount'];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
    
    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'delivery_schedule_shops');
    }

    public function deliveryScheduleShops()
    {
        return $this->hasMany(DeliveryScheduleShop::class, 'delivery_schedule_id');
    }
}
