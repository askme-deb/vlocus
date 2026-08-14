<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class DeliveryScheduleShop extends Model implements HasMedia
{
    use InteractsWithMedia ;
    protected $fillable = ['lr_no','delivery_schedule_id','shop_id','sender_branch_id','order_serial','app_serial','invoice_no','delivery_note','payment_type','amount','otp','status','is_accepted','accepted_at','accepted_lat','accepted_long','is_delivered','delivered_at','deliver_lat','deliver_long','is_cancelled','cancelled_at','cancel_reason'];

    public function deliverySchedule()
    {
        return $this->belongsTo(DeliverySchedule::class, 'delivery_schedule_id');
    }
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function products()
    {
        return $this->hasMany(DeliveryScheduleShopProduct::class, 'delivery_schedule_shop_id');
    }

    public function branch()
    {
        return $this->belongsTo(User::class,'sender_branch_id','id');
    }

}
