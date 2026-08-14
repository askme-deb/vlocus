<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPermissionSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'permission_id',
        'availability',
        'details'
    ];

    /**
     * Relationship: Belongs to Subscription
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Relationship: Belongs to Permission
     */
    public function permission()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Permission::class, 'permission_id');
    }

    public function featureLinks()
    {
        return $this->hasMany(
            SubscriptionFeaturePermissionSnapshot::class,
            'permission_snapshot_id'
        );
    }

}
