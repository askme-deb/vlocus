<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class DeliveryScheduleShopProduct extends Model implements HasMedia
{
    use InteractsWithMedia ;

    protected $fillable = ['delivery_schedule_shop_id','title','unit_or_box','qty'];

    public function deliveryScheduleShop()
    {
        return $this->belongsTo(DeliveryScheduleShop::class, 'delivery_schedule_shop_id');
    }

}
