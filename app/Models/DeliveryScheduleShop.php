<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class DeliveryScheduleShop extends Model implements HasMedia
{
    use InteractsWithMedia ;
    protected $fillable = ['lr_no','delivery_schedule_id','shop_id','sender_branch_id','order_serial','app_serial','invoice_no','delivery_note','payment_type','amount','otp','status','is_accepted','accepted_at','accepted_lat','accepted_long','receiver_name','receiver_phone','rating','rated_at','is_delivered','delivered_at','deliver_lat','deliver_long','is_cancelled','cancelled_at','cancel_reason','tracking_token'];

    protected $casts = [
        'accepted_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'rated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $deliveryScheduleShop) {
            if (blank($deliveryScheduleShop->tracking_token)) {
                $deliveryScheduleShop->tracking_token = static::generateUniqueTrackingToken();
            }
        });
    }

    /**
     * A random, non-sequential id safe to expose in a public tracking URL.
     */
    public static function generateUniqueTrackingToken(): string
    {
        do {
            $token = Str::random(40);
        } while (static::where('tracking_token', $token)->exists());

        return $token;
    }

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

    /**
     * The full public tracking URL for this order, built from the
     * configured public domain (services.whatsapp.tracking_base_url) so it
     * never depends on the host the request happened to arrive on.
     */
    public function publicTrackingUrl(): string
    {
        return rtrim((string) config('services.whatsapp.tracking_base_url'), '/')
            . route('order.tracking', ['token' => $this->tracking_token], false);
    }

    /**
     * The receiver's name for this order: the customer's own override if
     * they set one from the tracking page, otherwise the shop's contact.
     */
    public function effectiveReceiverName(): ?string
    {
        return $this->receiver_name ?: ($this->shop?->shop_contact_person_name ?: $this->shop?->shop_name);
    }

    public function effectiveReceiverPhone(): ?string
    {
        return $this->receiver_phone ?: $this->shop?->shop_contact_person_phone;
    }

    public function isCancelled(): bool
    {
        return (bool) $this->is_cancelled || $this->status === 'rejected' || $this->status === 'cancelled';
    }

    /**
     * Maps this order's delivery flags onto the 4-step tracking timeline
     * used by the public tracking page: 1=confirmed, 2=picked up,
     * 3=out for delivery, 4=delivered. There is no distinct "picked up"
     * vs "out for delivery" flag on the shop record, so acceptance by the
     * driver marks both steps done at once.
     */
    public function timelineStage(): int
    {
        if ($this->is_delivered) {
            return 4;
        }

        if ($this->is_accepted) {
            return 3;
        }

        return 1;
    }
}
