<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'coins',
        'task_coin_cost',
        'is_active',
    ];

    /**
     * Relationship: A plan has many plan-feature entries.
     */
    public function planFeatures()
    {
        return $this->hasMany(PlanFeature::class);
    }

    /**
     * Relationship: A plan has many features through the pivot table.
     */
    public function features()
    {
        return $this->belongsToMany(Feature::class, 'plan_features')
                    ->withPivot('availability', 'details')
                    ->withTimestamps();
    }

    /**
     * Accessor: Calculate how many tasks a user can create from this plan.
     */
    public function getMaxTasksAttribute()
    {
        if ($this->task_coin_cost == 0) {
            return 0;
        }

        return floor($this->coins / $this->task_coin_cost);
    }
}
