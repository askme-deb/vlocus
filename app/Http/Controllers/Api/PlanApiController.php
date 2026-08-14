<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;

class PlanApiController extends Controller
{
    public function index()
    {
        $plans = Plan::with('features')
            ->where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        // Format response
        $data = $plans->map(function ($plan) {
            // Group features by category
            $groupedFeatures = $plan->features
                ->sortBy(['category', 'order'])
                ->groupBy('category')
                ->map(function ($features, $category) {
                    return [
                        'category' => $category ?? 'General',
                        'items' => $features->map(function ($feature) {
                            return [
                                'id' => $feature->id,
                                'name' => $feature->name,
                                'availability' => $feature->pivot->availability,
                                'details' => $feature->pivot->details,
                            ];
                        })->values(),
                    ];
                })
                ->values();

            return [
                'id' => $plan->id,
                'name' => $plan->name,
                'description' => $plan->description,
                'price' => $plan->price,
                'coins' => $plan->coins,
                'task_coin_cost' => $plan->task_coin_cost,
                'max_tasks' => $plan->max_tasks,
                'features_by_category' => $groupedFeatures,
            ];
        });

        return response()->json([
            'status' => true,
            'plans' => $data,
        ]);
    }
}
