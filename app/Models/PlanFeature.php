<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanFeature extends Model
{
    protected $fillable = [
        'plan_id',
        'feature_id',
        'availability',
        'details',
        'limit',
    ];

    /**
     * A PlanFeature belongs to a Plan.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * A PlanFeature belongs to a Feature.
     */
    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

    public function planPermissions()
    {
        return $this->hasMany(PlanFeaturePermission::class);
    }

}
