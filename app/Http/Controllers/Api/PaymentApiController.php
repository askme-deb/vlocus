<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Plan;
use Illuminate\Support\Facades\Validator;

use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\SubscriptionFeatureSnapshot;
use App\Models\SubscriptionPermissionSnapshot;
use App\Models\SubscriptionFeaturePermissionSnapshot;
use Carbon\Carbon;

class PaymentApiController extends Controller
{
    public function createOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Errors',
                'data' => $validator->errors(),
            ],422);
        }

        $plan = Plan::findOrFail($request->plan_id);

        $razorpay = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        // Amount in paise
        $amount = $plan->price * 100;

        // Create order
        $order = $razorpay->order->create([
            'receipt'         => 'order_' . time(),
            'amount'          => $amount,
            'currency'        => 'INR',
            'payment_capture' => 1,
        ]);

        return response()->json([
            'status' => true,
            'order_id' => $order['id'],
            'key' => env('RAZORPAY_KEY'),
            'amount' => $amount,
            'currency' => 'INR',
            'plan' => $plan
        ]);
    }

    public function paymentSuccess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'razorpay_payment_id' => 'required',
            'razorpay_order_id'   => 'required',
            'razorpay_signature'  => 'required',
            'plan_id'             => 'required|exists:plans,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Errors',
                'data' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();
        $plan = Plan::with('features.permissions')->findOrFail($request->plan_id);

        // Create Subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonth(),
            'is_recurring' => true,
            'status' => 'active',
            'price' => $plan->price,
            'coins' => $plan->coins,
            'task_coin_cost' => $plan->task_coin_cost,
            'max_tasks' => $plan->max_tasks,
        ]);

        // Snapshot: Features + Permissions
        foreach ($plan->features as $feature) {

            // Feature snapshot
            $featureSnapshot = SubscriptionFeatureSnapshot::create([
                'subscription_id' => $subscription->id,
                'feature_id' => $feature->id,
                'availability' => $feature->pivot->availability,
                'details' => $feature->pivot->details,
                'limit' => $feature->pivot->limit,
            ]);

            // Permission snapshots under this feature
            foreach ($feature->permissions as $permission) {

                $permissionSnap = SubscriptionPermissionSnapshot::create([
                    'subscription_id' => $subscription->id,
                    'permission_id' => $permission->id,
                    'availability' => 'yes',
                    'details' => null,
                ]);

                // Link feature → permission
                SubscriptionFeaturePermissionSnapshot::create([
                    'feature_snapshot_id' => $featureSnapshot->id,
                    'permission_snapshot_id' => $permissionSnap->id,
                ]);
            }
        }

        Transaction::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'subscription_id' => $subscription->id,

            'amount' => $plan->price,
            'credit' => $plan->price,
            'type' => 'credit',

            'payment_id' => $request->razorpay_payment_id,
            'order_id' => $request->razorpay_order_id,
            'signature' => $request->razorpay_signature,

            'payment_method' => 'razorpay',
            'payment_status' => 'success',
            'remarks' => 'Plan purchased successfully',
        ]);


        return response()->json([
            'status' => true,
            'message' => 'Subscription activated successfully',
            'subscription_id' => $subscription->id
        ]);
    }

}
