<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Shop;
use App\Models\Driver;
use App\Models\DeliverySchedule;
use App\Models\DeliveryScheduleShop;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TrackingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // order_tracking, public_driver_location, update_receiver_contact,
            // submit_rating and get_driver_location are intentionally excluded:
            // they're only wired to public, unauthenticated routes
            // (routes/web.php ~56-62) for customer-facing order tracking,
            // not the admin dashboard.
            new Middleware('permission:Tracking Show', only: ['index']),
        ];
    }

    public function index()
    {
        $companyId = auth()->user()->companyId();

        $driverQuery = User::role('Driver')
                    ->with(['driver.latestLocation', 'currentActiveDelivery.vehicle', 'currentActiveDelivery.deliveryScheduleShops']);
        $vehicleQuery = Vehicle::latest();
        $shopQuery = Shop::latest();

        if ($companyId) {
            $driverQuery->whereHas('driver', fn($q) => $q->where('company_id', $companyId));
            $vehicleQuery->where('company_id', $companyId);
            $shopQuery->where('company_id', $companyId);
        }

        $drivers = $driverQuery->latest()->get();
        $vehicles = $vehicleQuery->get();
        $shops = $shopQuery->get();
        return view('admin.tracking.index',compact('drivers','vehicles','shops'));
    }
    
    /**
     * The public, unauthenticated order-tracking page. Looked up strictly by
     * the opaque tracking_token - never by the row's own id - so the URL
     * can safely be handed to a customer over WhatsApp.
     */
    public function order_tracking(string $token)
    {
        $order = $this->findByTrackingToken($token);

        if (! $order) {
            return response()->view('order_tracking_unavailable', [], 404);
        }

        $delivery = $order->deliverySchedule;
        $driverUser = $delivery?->driver;
        $driverDetails = $driverUser?->driver;
        $vehicle = $delivery?->vehicle;
        $shop = $order->shop;
        $products = $order->products;

        return view('order_tracking', [
            'order' => $order,
            'delivery' => $delivery,
            'driver' => $driverUser,
            'driver_details' => $driverDetails,
            'vehicle' => $vehicle,
            'shop' => $shop,
            'products' => $products,
            'etaMinutes' => $this->estimateEtaMinutes($driverDetails, $shop),
        ]);
    }

    /**
     * A rough "arriving in N minutes" estimate from the driver's last known
     * position to the delivery address, assuming a modest city driving
     * speed. Returns null whenever either coordinate is missing so the view
     * can fall back to a neutral "calculating..." state instead of a made
     * up number.
     */
    private function estimateEtaMinutes(?Driver $driverDetails, ?Shop $shop): ?int
    {
        if (! $driverDetails || $driverDetails->latitude === null || $driverDetails->longitude === null) {
            return null;
        }

        if (! $shop || $shop->shop_latitude === null || $shop->shop_longitude === null) {
            return null;
        }

        $earthRadiusKm = 6371;
        $lat1 = deg2rad((float) $driverDetails->latitude);
        $lat2 = deg2rad((float) $shop->shop_latitude);
        $deltaLat = deg2rad((float) $shop->shop_latitude - (float) $driverDetails->latitude);
        $deltaLng = deg2rad((float) $shop->shop_longitude - (float) $driverDetails->longitude);

        $a = sin($deltaLat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($deltaLng / 2) ** 2;
        $distanceKm = $earthRadiusKm * 2 * atan2(sqrt($a), sqrt(1 - $a));

        $averageCitySpeedKmh = 20;
        $minutes = (int) ceil(($distanceKm / $averageCitySpeedKmh) * 60);

        return max(2, min($minutes, 60));
    }

    /**
     * Live driver coordinates for the map on the public tracking page,
     * scoped by tracking_token so it never has to expose the driver's or
     * order's internal database id to the browser.
     */
    public function public_driver_location(string $token)
    {
        $order = $this->findByTrackingToken($token);

        if (! $order) {
            return response()->json(['response' => false, 'message' => 'Tracking link not found.'], 404);
        }

        $driverDetails = $order->deliverySchedule?->driver?->driver;

        if (! $driverDetails || $driverDetails->latitude === null || $driverDetails->longitude === null) {
            return response()->json(['response' => false, 'message' => 'Location not available yet.'], 404);
        }

        return response()->json([
            'response' => true,
            'data' => [
                'latitude' => $driverDetails->latitude,
                'longitude' => $driverDetails->longitude,
                'eta_minutes' => $this->estimateEtaMinutes($driverDetails, $order->shop),
            ],
        ]);
    }

    /**
     * Lets the customer correct the receiver's name/number for this order
     * from the tracking page without touching the shared Shop contact
     * record (other orders for the same shop are unaffected).
     */
    public function update_receiver_contact(Request $request, string $token)
    {
        $order = $this->findByTrackingToken($token);

        if (! $order) {
            return response()->json(['response' => false, 'message' => 'Tracking link not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|digits:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['response' => false, 'errors' => $validator->errors()], 422);
        }

        $order->update([
            'receiver_name' => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
        ]);

        return response()->json(['response' => true, 'message' => "Receiver's contact updated."]);
    }

    /**
     * Records the customer's post-delivery rating (1-5) from the tracking
     * page. Can only be set once per order.
     */
    public function submit_rating(Request $request, string $token)
    {
        $order = $this->findByTrackingToken($token);

        if (! $order) {
            return response()->json(['response' => false, 'message' => 'Tracking link not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['response' => false, 'errors' => $validator->errors()], 422);
        }

        if (blank($order->rated_at)) {
            $order->update(['rating' => $request->rating, 'rated_at' => now()]);
        }

        return response()->json(['response' => true, 'message' => 'Thank you for your feedback!']);
    }

    private function findByTrackingToken(string $token): ?DeliveryScheduleShop
    {
        if ($token === '') {
            return null;
        }

        return DeliveryScheduleShop::with([
            'shop',
            'products',
            'deliverySchedule.driver.driver',
            'deliverySchedule.vehicle.brand',
        ])->where('tracking_token', $token)->first();
    }

    public function get_driver_location($id){
        $driver = Driver::where('user_id',$id)->first();
        return response()->json([
            'latitude' => $driver->latitude,
            'longitude' => $driver->longitude
        ]);
    }
}
