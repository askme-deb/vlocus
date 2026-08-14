<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionFeatureSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'feature_id',
        'availability',
        'details',
        'limit',
    ];

    /**
     * Relationship: Snapshot belongs to Subscription
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Relationship: Snapshot belongs to Feature
     */
    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

    /**
     * Scope: Only available features
     */
    public function scopeAvailable($query)
    {
        return $query->where('availability', 'yes');
    }

    public function permissionSnapshots()
    {
        return $this->hasMany(
            SubscriptionFeaturePermissionSnapshot::class,
            'feature_snapshot_id'
        );
    }

}
