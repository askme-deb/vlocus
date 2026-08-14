<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\DeliverySchedule;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Shop;
use App\Models\DeliveryScheduleShop;
use App\Models\DeliveryScheduleShopProduct;
use App\Models\VehicleType;
use App\Models\Brand;
use App\Models\Color;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Validator;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\Driver;
class DeliveryScheduleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Delivery Schedule Show', only: ['index','show']),
            new Middleware('permission:Delivery Schedule Create', only: ['create','store']),
            new Middleware('permission:Delivery Schedule Edit', only: ['edit','update','reject_shop']),
            new Middleware('permission:Delivery Schedule Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $query = DeliverySchedule::latest();

        if ($companyId = auth()->user()->companyId()) {
            $query->whereHas('vehicle', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        }

        $delivery_schedules = $query->get();
        return view('admin.delivery_schedules.index',compact('delivery_schedules'));
    }

    // public function track_delivery(Request $request)
    // {
    //     $delivery = DeliveryScheduleShop::with('shop')->findOrFail($request->delivery_id);

    //     $shop = $delivery->shop;

    //     $driverLat = $request->lat;
    //     $driverLng = $request->lng;
    //     $shopLat = $shop->shop_latitude;
    //     $shopLng = $shop->shop_longitude;
    //     $driverId= $request->driver_id;
    //     // $driver = Driver::where('user_id',$request->driver_id)->first();
    //     $driver = User::findOrFail($request->driver_id);
    //     //  d($driverLng);

    //     return view('admin.delivery_schedules.tracking_details', compact('driverLat', 'driverLng', 'shopLat', 'shopLng', 'shop','driverId','driver','delivery'));
    // }


    public function track_delivery(Request $request)
    {
        $delivery = DeliverySchedule::with('deliveryScheduleShops')->findOrFail($request->delivery_id);
        $driver = $delivery->driver;
        $driver_details = $delivery->driver->driver;
        $delivery_schedule_shops = $delivery->deliveryScheduleShops;
        $delivery_sender_branch = $delivery->deliveryScheduleShops->first();
        $delivery_sender_branch = $delivery_sender_branch->branch->branch;

        return view('admin.delivery_schedules.tracking_details', compact('delivery', 'driver', 'driver_details', 'delivery_schedule_shops','delivery_sender_branch'));
    }

    public function deliveryInvoice(Request $request, String $delivery_id, String $shop_id)
    {
        // Fetch only the delivery schedule and the shop we need
        $deliverySchedule = DeliverySchedule::with([
            'driver.driver',
            'deliveryScheduleShops' => function ($query) use ($shop_id) {
                $query->where('shop_id', $shop_id)
                    ->with(['shop', 'products','branch']);
            }
        ])->findOrFail($delivery_id);

        $driver = $deliverySchedule->driver;
        $driver_details = $driver->driver;

        // Since we filtered, there will be only one shop in this collection
        $shop = $deliverySchedule->deliveryScheduleShops->first();

        if (!$shop) {
            abort(404, 'Shop not found in this delivery schedule.');
        }

        $invoiceData = [
            'order_no'        => $shop->invoice_no ?? 'N/A',
            'invoice_no'      => $shop->lr_no ?? 'N/A',
            'consignor'       => $shop->branch ?? 'N/A',
            'consignee'       => $shop->shop ?? 'N/A',
            'products'        => $shop->products->toArray(),
            'total_items'     => $shop->products->count(),
            'item_descriptions' => $shop->products->map(function ($p) {
                return "{$p->title} ({$p->qty})";
            })->toArray(),
            'payment_details' => $deliverySchedule->payment_details ?? 'N/A',
            'delivery_date'   => $deliverySchedule->delivery_date ?? 'N/A',
            'vehicle'         => $deliverySchedule->vehicle ?? 'N/A',
            'driver'          => [
                'name'  => $driver->name ?? 'N/A',
                'phone' => $driver->phone ?? 'N/A'
            ],
            'otp'             => $shop->otp ?? 'N/A',
        ];

        return view('admin.delivery_schedules.invoice', compact(
            'invoiceData',
            'shop',
            'driver',
            'driver_details'
        ));
    }



    public function get_driver_location(Request $request)
    {
        $driverId = $request->driver_id;

        $driver = Driver::where('user_id', $driverId)->first();

        if (!$driver) {
            return response()->json([
                'response' => false,
                'message' => 'Driver not found.'
            ], 404);
        }

        return response()->json([
            'response' => true,
            'message' => 'Driver location fetched successfully.',
            'data' => [
                'latitude' => $driver->latitude,
                'longitude' => $driver->longitude
            ]
        ]);
    }


    public function search(Request $request)
    {
        $search = $request->search;

        $shops = Shop::where(function ($query) use ($search) {
                $query->where('shop_name', 'like', "%{$search}%")
                    ->orWhere('shop_contact_person_name', 'like', "%{$search}%")
                    ->orWhere('shop_contact_person_phone', 'like', "%{$search}%")
                    ->orWhere('shop_number', 'like', "%{$search}%");
            })
            ->orderBy('shop_name')
            ->limit(10)
            ->get([
                'id',
                'shop_name',
                'shop_address',
                'shop_latitude',
                'shop_longitude',
                'shop_contact_person_name',
                'shop_contact_person_phone',
            ]);

        return response()->json($shops);
    }


     public function add_shop(Request $request)
    {
        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'shop_name' => 'required|max:255',
                'shop_address' => 'required|max:255',
                'shop_contact_person_name' => 'required|max:255',
                'shop_contact_person_phone' => 'required|max:255',
                'shop_latitude' => 'nullable|numeric',
                'shop_longitude' => 'nullable|numeric',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',

            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()], 422);
            }
            $maxNumber = Shop::max('shop_number');
            $newNumber = $maxNumber ? $maxNumber + 1 : 10000;
            $data = Shop::create([
                'shop_number'=>$newNumber,
                'shop_name' => $request->shop_name,
                'shop_address' => $request->shop_address,
                'shop_contact_person_name' => $request->shop_contact_person_name,
                'shop_contact_person_phone' => $request->shop_contact_person_phone,
                'shop_latitude' => $request->shop_latitude,
                'shop_longitude' => $request->shop_longitude,
                'is_visible' => 1,
            ]);

            if ($request->hasFile('image')) {
                $data->addMedia($request->file('image'))->toMediaCollection('shop-image');
            }elseif ($request->filled('shop_image_from_google')) {
                try {
                    $googleImageUrl = $request->shop_image_from_google;
                    $imageContents = file_get_contents($googleImageUrl);
                    $tempFile = tmpfile();
                    $tempPath = stream_get_meta_data($tempFile)['uri'];
                    fwrite($tempFile, $imageContents);
                    $data->addMedia($tempPath)
                        ->usingFileName('shop_' . time() . '.jpg')
                        ->toMediaCollection('shop-image');
                    fclose($tempFile);
                } catch (\Exception $e) {
                    \Log::error('Failed to save Google image: ' . $e->getMessage());
                }
            }


            if($data->id){

                return response()->json([
                    'success' => true,
                    'message' => 'Shop Created Successfully',

                ]);

            }else{


                  return response()->json([
                    'success' => true,
                    'message' => 'Shop Not Created',
                ]);
            }
        }
    }


    public function create()
    {
        // $engagedDriverIds = DeliverySchedule::where('is_completed', 0)
        //     ->pluck('driver_id')
        //     ->toArray();
        // $drivers = User::role('Driver')
        //     ->whereNotIn('id', $engagedDriverIds)
        //     ->latest()
        //     ->get();
        // // $drivers = User::role('Driver')->latest()->get();
        // dd($drivers);
$drivers = User::role('Driver')
    ->whereNotIn('id', function ($query) {
        $query->select('driver_id')
            ->from('delivery_schedules')
            ->where('is_completed', 0)
            ->whereNotNull('driver_id');
    })
    ->latest()
    ->get();

   // dd($drivers);
        $engagedVehicleIds = DeliverySchedule::where('is_completed', 0)
            ->pluck('vehicle_id')
            ->toArray();

        $vehicles = Vehicle::whereNotIn('id', $engagedVehicleIds)
            ->latest()
            ->get();

        // $vehicles = Vehicle::latest()->get();
        $shops = Shop::latest()->get();

        $vehicle_types= VehicleType::where('is_visible',1)->get();
        $brands = Brand::where('is_visible',1)->get();

        $colors= Color::where('is_visible',1)->get();
        return view('admin.delivery_schedules.create',compact('drivers','vehicles','shops','vehicle_types','brands','colors'));
    }

    public function store(Request $request)
    {
        // return $request->all();

        // d($request->all());

        $validator = Validator::make($request->all(), [
            'shop_ids' => 'required',
            'delivery_date' => 'required',
            'driver_id' => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        // $shopIds = explode(',', $request->shop_ids);


       $driver= Driver::where('user_id',$request->driver_id)->first();


        $deliverySchedule = DeliverySchedule::create([
            'delivery_date' => toDbDate($request->delivery_date),
            'order_id' => generateOrderNumber(),
            'route_code' => $request->route_code,
            'driver_id' => $request->driver_id,
            'vehicle_id' => $request->vehicle_id,
            'delivery_note'=>$request->delivery_note,
            'payment_type'=>$request->payment_type,
            'amount'=>$request->amount,
        ]);

        // Attach sorted shops (Modify based on your DB structure)
        foreach ($request->shop_ids as $index => $shopId) {
            $maxNumber = DeliveryScheduleShop::max('lr_no');
            $newNumber = $maxNumber ? $maxNumber + 1 : 10000;
            // dd($request->branch_id[$index]);

            $delivery_schedule = DeliveryScheduleShop::create([
                'lr_no'=> $newNumber,
                'delivery_schedule_id' => $deliverySchedule->id,
                'shop_id' => $shopId,
                'sender_branch_id' => $request->branch_id[$index] ?? null,
                'order_serial' => $index + 1,
                'invoice_no' => $request->invoice_nos[$index] ?? null,
                'delivery_note' => $request->delivery_notes[$index] ?? null,
                'payment_type' => $request->payment_types[$index] ?? null,
                'amount' => $request->amounts[$index] ?? null,
                'otp' => 1234
            ]);

            if (!empty($request->product_titles[$index+1])) {
                foreach ($request->product_titles[$index+1] as $pIndex => $title) {
                    DeliveryScheduleShopProduct::create([
                        'delivery_schedule_shop_id' => $delivery_schedule->id,
                        'title' => $title,
                        'unit_or_box' => $request->product_units[$index+1][$pIndex],
                        'qty' => $request->product_qtys[$index+1][$pIndex],
                    ]);
                }
            }
        }

        if($deliverySchedule->id){
            return redirect()->route('delivery-schedule.index')->with(['success'=>'Delivery Schedule Created Successfully']);
        }else{
            return back()->with(['error'=>'Delivery Schedule Not Created']);
        }
    }

    public function show(string $id)
    {
        $deliverySchedule = DeliverySchedule::with('shops')->findOrFail($id);

        // return $deliverySchedule;
        // Get shop coordinates as "lat,lng"
        $locations = $deliverySchedule->shops->map(fn($shop) => "{$shop->shop_latitude},{$shop->shop_longitude}")->toArray();

        // return $locations;
        if (count($locations) < 2) {
            return back()->with('error', 'At least two locations are required to show a route.');
        }

        // Admin-Defined Route
        $adminRoute = $locations;

        // Optimized Route using GoMaps API
        $gomapsKey = env('GOMAPS_API_KEY'); // Store in .env
        $path = implode('|', $locations);

        // return $path;
        $response = Http::get("https://roads.gomaps.pro/v1/snaptoroads", [
            'path' => $path,
            'interpolate' => 'true',
            'key' => $gomapsKey
        ]);

        $data = $response->json();
        // return $data;
        // Extract optimized route points
        $optimizedRoute = $adminRoute; // Default to admin route
        if (!empty($data['snappedPoints'])) {
            $optimizedRoute = collect($data['snappedPoints'])->map(fn($point) => "{$point['location']['latitude']},{$point['location']['longitude']}")->toArray();
        }

        $selectedShops = DeliveryScheduleShop::where('delivery_schedule_id', $id)
            ->orderBy('order_serial', 'asc')
            ->with('shop')
            ->get();

        $shopsForMap = $selectedShops->map(function ($item) {
            return [
                'name' => $item->shop->shop_name,
                'lat' => (float) $item->shop->shop_latitude,
                'lng' => (float) $item->shop->shop_longitude,
                'address' => $item->shop->shop_address,
            ];
        })->values(); // Ensure it's a clean array, not a Collection

        return view('admin.delivery_schedules.show', compact('adminRoute', 'optimizedRoute', 'gomapsKey','shopsForMap'));
    }

    public function edit(string $id)
    {
        $delivery_Schedule = DeliverySchedule::findOrFail($id);

        $engagedDriverIds = DeliverySchedule::where('is_completed', 0)
            ->where('id', '!=', $delivery_Schedule->id) // exclude current schedule
            ->pluck('driver_id')
            ->toArray();

        $drivers = User::role('Driver')
            ->whereNotIn('id', $engagedDriverIds)
            ->latest()
            ->get();
        // $drivers = User::role('Driver')->latest()->get();

        $engagedVehicleIds = DeliverySchedule::where('is_completed', 0)
            ->where('id', '!=', $delivery_Schedule->id) // exclude current schedule
            ->pluck('vehicle_id')
            ->toArray();

        $vehicles = Vehicle::whereNotIn('id', $engagedVehicleIds)
            ->latest()
            ->get();

        // $vehicles = Vehicle::latest()->get();

        $shops = Shop::latest()->get();

        // $selectedShops = DeliveryScheduleShop::where('delivery_schedule_id', $id)
        //                 ->orderBy('order_serial', 'asc')
        //                 ->pluck('shop_id')
        //                 ->toArray();

        // $selectedShops = DeliveryScheduleShop::where('delivery_schedule_id', $id)
        //                     ->orderBy('order_serial', 'asc')
        //                     ->with('shop')
        //                     ->get();

        $selectedShops = DeliveryScheduleShop::where('delivery_schedule_id', $id)
                            ->orderBy('order_serial', 'asc')
                            ->with(['shop', 'products']) // <-- eager load products
                            ->get();
        // return $selectedShops;

        $vehicle_types= VehicleType::where('is_visible',1)->get();
        $brands = Brand::where('is_visible',1)->get();

        $colors= Color::where('is_visible',1)->get();


        return view('admin.delivery_schedules.edit',compact('drivers','vehicle_types','brands','colors','vehicles','shops','delivery_Schedule','selectedShops'));
    }

    public function update(Request $request, string $id)
    {

        $validator = Validator::make($request->all(), [
            'shop_ids' => 'required',
            'delivery_date' => 'required',
            'driver_id' => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        // return $request->all();
        // foreach ($request->shop_ids as $index => $shopId) {
        //     echo "index => ". $index . " Shop Id => ". $shopId;
        // }
        // die;

        // $shopIds = explode(',', $request->shop_ids);

         $driver= Driver::where('user_id',$request->driver_id)->first();

        $deliverySchedule = DeliverySchedule::findOrFail($id);
        $deliverySchedule->update([
            'delivery_date' => toDbDate($request->delivery_date),
            'route_code' => $request->route_code,
            'driver_id' => $request->driver_id,
            'vehicle_id' => $request->vehicle_id,
            'delivery_note'=>$request->delivery_note,
            'payment_type'=>$request->payment_type,
            'amount'=>$request->amount,
        ]);

        // Remove old shops and insert new ones
        DeliveryScheduleShop::where('delivery_schedule_id', $id)->delete();
        foreach ($request->shop_ids as $index => $shopId) {
            $maxNumber = DeliveryScheduleShop::max('lr_no');
            $newNumber = $maxNumber ? $maxNumber + 1 : 10000;

            $delivery_schedule = DeliveryScheduleShop::create([
                'lr_no'=> $newNumber,
                'delivery_schedule_id' => $deliverySchedule->id,
                'shop_id' => $shopId,
                'sender_branch_id' => $request->branch_id[$index] ?? null,
                'order_serial' => $index + 1,
                'invoice_no' => $request->invoice_nos[$index] ?? null,
                'delivery_note' => $request->delivery_notes[$index] ?? null,
                'payment_type' => $request->payment_types[$index] ?? null,
                'amount' => $request->amounts[$index] ?? null,
                'otp' => 1234
            ]);

            if (!empty($request->product_titles[$index+1])) {
                foreach ($request->product_titles[$index+1] as $pIndex => $title) {
                    DeliveryScheduleShopProduct::create([
                        'delivery_schedule_shop_id' => $delivery_schedule->id,
                        'title' => $title,
                        'unit_or_box' => $request->product_units[$index+1][$pIndex],
                        'qty' => $request->product_qtys[$index+1][$pIndex],
                    ]);
                }
            }
        }


        return redirect()->route('delivery-schedule.index')->with(['success' => 'Delivery Schedule Updated Successfully']);
    }

    public function reject_shop(Request $request, string $delivery_schedule_shop)
    {
        $shop = DeliveryScheduleShop::findOrFail($delivery_schedule_shop);

        if ($shop->is_delivered) {
            return response()->json(['success' => false, 'message' => 'This stop is already delivered and cannot be rejected.'], 422);
        }

        $shop->status = 'rejected';
        $shop->is_cancelled = 1;
        $shop->cancelled_at = now();
        $shop->cancel_reason = $request->reason;
        $shop->save();

        return response()->json(['success' => true, 'message' => 'Stop rejected and removed from the route.']);
    }

    public function show_map(string $id)
    {
        $delivery_Schedule = DeliverySchedule::findOrFail($id);

        $selectedShops = DeliveryScheduleShop::where('delivery_schedule_id', $id)
            ->orderBy('order_serial', 'asc')
            ->with('shop')
            ->get();

        // ✅ Convert shop data for map view
        $shopsForMap = $selectedShops->map(function ($item) {
            return [
                'name' => $item->shop->shop_name,
                'lat' => (float) $item->shop->shop_latitude,
                'lng' => (float) $item->shop->shop_longitude,
                'address' => $item->shop->shop_address,
            ];
        })->values(); // Ensure it's a clean array, not a Collection
        $apiKey = env('GOOGLE_MAPS_API_KEY');

        return view('admin.delivery_schedules.show-map', compact('delivery_Schedule', 'selectedShops', 'shopsForMap','apiKey'));
    }




    public function destroy(string $id)
    {
        $delivery_schedule = DeliverySchedule::findOrFail($id);

        if (!$delivery_schedule) {
            return redirect()->back()->withErrors(['error' => 'Delivery Schedule not found.'])->withInput();

        }
        $delivery_schedule->deliveryScheduleShops()->delete();
        $delivery_schedule->delete();
        return response()->json(['success' => 'Route Deleted Successfully']);
    }
}
