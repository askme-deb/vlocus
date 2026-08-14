<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'category',
        'order',
    ];

    /**
     * Relationship: A feature has many plan-feature entries.
     */
    public function planFeatures()
    {
        return $this->hasMany(PlanFeature::class);
    }

    /**
     * Relationship: A feature belongs to many plans through the pivot.
     */
    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_features')
                    ->withPivot('availability', 'details')
                    ->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(\Spatie\Permission\Models\Permission::class, 'feature_permission');
    }

}
