<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanFeaturePermission extends Model
{
    protected $table = 'feature_permission';

    protected $fillable = [
        'plan_id',
        'feature_id',
        'permission_id',
    ];

    // Relationships
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

    public function permission()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Permission::class);
    }
}
