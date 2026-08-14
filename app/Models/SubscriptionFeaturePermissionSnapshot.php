<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionFeaturePermissionSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'feature_snapshot_id',
        'permission_snapshot_id',
    ];

    /**
     * Relationship: Belongs to Feature Snapshot
     */
    public function featureSnapshot()
    {
        return $this->belongsTo(SubscriptionFeatureSnapshot::class, 'feature_snapshot_id');
    }

    /**
     * Relationship: Belongs to Permission Snapshot
     */
    public function permissionSnapshot()
    {
        return $this->belongsTo(SubscriptionPermissionSnapshot::class, 'permission_snapshot_id');
    }
}
