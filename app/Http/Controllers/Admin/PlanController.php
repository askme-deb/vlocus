<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Feature;
use App\Models\PlanFeature;
use App\Models\PlanFeaturePermission;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PlanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Plan Show', only: ['index']),
            new Middleware('permission:Plan Create', only: ['storePlan', 'storeFeature']),
            new Middleware('permission:Plan Edit', only: ['editPlan', 'updatePlan', 'editFeature', 'updateFeature', 'updateMapping']),
            new Middleware('permission:Plan Delete', only: ['destroyPlan', 'destroyFeature']),
        ];
    }

    public function index()
    {
        $plans = Plan::with('features')->orderBy('id', 'asc')->get();
        $features = Feature::orderBy('category')->orderBy('order')->get();
        $allPermissions = Permission::all();
        return view('admin.plan.index', compact('plans', 'features', 'allPermissions'));
    }

    public function storePlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'coins' => 'required|integer|min:0',
            'task_coin_cost' => 'required|integer|min:1',
        ]);

        Plan::create($request->only('name', 'description', 'price', 'coins', 'task_coin_cost', 'is_active'));

        return back()->with('success', 'Plan created successfully!');
    }

    public function storeFeature(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
        ]);
        // Feature::create($request->only('name', 'category', 'order'));
        Feature::create([
            'slug' => createSlug($request->name,Feature::class),
            'name' => $request->name,
            'category' => $request->category ?? 'general',
            'order' => $request->order ?? 0,
        ]);
        return back()->with('success', 'Feature created successfully!');
    }

    /*public function updateMapping(Request $request)
    {
        $data = $request->input('mapping', []);

        DB::transaction(function () use ($data) {
            foreach ($data as $planId => $features) {
                foreach ($features as $featureId => $map) {
                    PlanFeature::updateOrCreate(
                        ['plan_id' => $planId, 'feature_id' => $featureId],
                        [
                            'availability' => $map['availability'] ?? 'no',
                            'details' => $map['details'] ?? null,
                            'limit' =>$map['limit'] ?? null
                        ]
                    );
                }
            }
        });

        return back()->with('success', 'Plan feature mapping updated!');
    }*/

    public function updateMapping(Request $request)
    {
        $data = $request->input('mapping', []);
        $featurePermissions = $request->input('feature_permissions', []);

        DB::transaction(function () use ($data, $featurePermissions) {
            // 🔹 1. Update Plan ↔ Feature mapping
            foreach ($data as $planId => $features) {
                foreach ($features as $featureId => $map) {
                    PlanFeature::updateOrCreate(
                        ['plan_id' => $planId, 'feature_id' => $featureId],
                        [
                            'availability' => $map['availability'] ?? 'no',
                            'details' => $map['details'] ?? null,
                            'limit' => $map['limit'] ?? null,
                        ]
                    );
                }
            }

            // 🔹 2. Update Feature ↔ Permission mapping
            // foreach ($featurePermissions as $featureId => $permissionIds) {
            //     $feature = Feature::find($featureId);
            //     if ($feature) {
            //         // Sync Spatie permissions for this feature
            //         $feature->permissions()->sync($permissionIds);
            //     }
            // }

            foreach ($featurePermissions as $planId => $features) {
    
                foreach ($features as $featureId => $permissionIds) {
                    
                    // Convert to array
                    $permissionIds = (array) $permissionIds;

                    // Delete previous permissions for this plan + feature
                    PlanFeaturePermission::where([
                        'plan_id'    => $planId,
                        'feature_id' => $featureId
                    ])->delete();

                    // Insert new permissions
                    foreach ($permissionIds as $pid) {
                        PlanFeaturePermission::create([
                            'plan_id'       => $planId,
                            'feature_id'    => $featureId,
                            'permission_id' => $pid,
                        ]);
                    }
                }
            }
        });

        return back()->with('success', 'Plan feature & permission mapping updated successfully!');
    }

    // Edit Plan
    public function editPlan($id)
    {
        $plan = Plan::findOrFail($id);
        return response()->json($plan);
    }

    // Update Plan
    public function updatePlan(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'coins' => 'required|integer|min:0',
            'task_coin_cost' => 'required|integer|min:1',
        ]);

        $plan = Plan::findOrFail($id);
        $plan->update($request->only('name', 'description', 'price', 'coins', 'task_coin_cost', 'is_active'));

        return back()->with('success', 'Plan updated successfully!');
    }

    // Delete Plan
    public function destroyPlan($id)
    {
        Plan::findOrFail($id)->delete();
        return back()->with('success', 'Plan deleted successfully!');
    }


    // Edit Feature
    public function editFeature($id)
    {
        $feature = Feature::findOrFail($id);
        return response()->json($feature);
    }

    // Update Feature
    public function updateFeature(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
        ]);

        $feature = Feature::findOrFail($id);
        $feature->update($request->only('name', 'category', 'order'));

        return back()->with('success', 'Feature updated successfully!');
    }

    // Delete Feature
    public function destroyFeature($id)
    {
        Feature::findOrFail($id)->delete();
        return back()->with('success', 'Feature deleted successfully!');
    }

}
