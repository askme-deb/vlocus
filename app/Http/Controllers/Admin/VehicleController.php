<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\HandlesBankUResponses;
use App\Http\Requests\Admin\VerifyRcRequest;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use App\Models\VehicleType;
use App\Models\Brand;
use App\Models\Models;
use App\Models\Color;
use App\Models\Route;

use App\Models\Booking;

use App\Models\Journey;

use App\Services\BankU\BankUIdentityService;
use App\Services\BankU\Exceptions\BankUConnectionException;

use Carbon\Carbon;
class VehicleController extends Controller implements HasMiddleware
{
    use HandlesBankUResponses;

    public function __construct(private readonly BankUIdentityService $bankUIdentityService)
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Vehicle Show', only: ['index','show']),
            new Middleware('permission:Vehicle Create', only: ['create','store','verifyRc']),
            new Middleware('permission:Vehicle Edit', only: ['edit','update']),
            new Middleware('permission:Vehicle Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $query = Vehicle::latest();

        if ($companyId = auth()->user()->companyId()) {
            $query->where('company_id', $companyId);
        }

        $vehicles = $query->get();

        return view('admin.vehicle.index',compact('vehicles'));
    }

    public function booking_list($journey_id)
    {

        $bookings = Booking::where('journey_id',$journey_id)->latest()->get();


        return view('admin.vehicle.bookings',compact('bookings'));
    }

    public function create()
    {

        $vehicle_types= VehicleType::where('is_visible',1)->get();
        $brands = Brand::where('is_visible',1)->get();

        $colors= Color::where('is_visible',1)->get();

        return view('admin.vehicle.create',compact('vehicle_types','brands','colors'));
    }
    public function getModels($brand_id)
    {
        $models = Models::where('brand_id', $brand_id)->get(['id', 'name']);
        return response()->json(['models' => $models]);

    }

    public function verifyRc(VerifyRcRequest $request)
    {
        try {
            $result = $this->bankUIdentityService->verifyRc(
                $request->string('vehicle_registration_number'),
                (float) $request->input('latitude', 28.6139),
                (float) $request->input('longitude', 77.209),
            );
        } catch (BankUConnectionException $e) {
            return $this->bankUUnavailableResponse();
        }

        return $this->bankUResponse($result, 'RC verification failed.');
    }
public function storeFromModal(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name'            => 'required|string|max:255',
        'vehicle_number'  => 'required|string|max:255',
        'rwc_number'      => 'required|string|max:255',
        'engine_number'   => 'required|string|max:255',
        'fuel_type'       => 'nullable',
        'vehicle_type'    => 'nullable|exists:vehicle_types,id',
        'description'     => 'nullable|string',
        'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors'  => $validator->errors(),
        ], 422);
    }

    $vehicleData = [
        'name'            => $request->name,
        'company_id'      => auth()->user()->companyId(),
        'vehicle_number'  => $request->vehicle_number,
        'rwc_number'      => $request->rwc_number,
        'engine_number'   => $request->engine_number,
        'fuel_type'       => $request->fuel_type,
        'vehicle_type'    => $request->vehicle_type,
        'description'     => $request->description,
    ];

    $rcVerificationData = $this->decodeVerificationPayload($request, 'rc_verification_data');
    if ($rcVerificationData !== null) {
        $vehicleData['rc_verification_data'] = $rcVerificationData;
        $vehicleData['rc_verified_at'] = now();
    }

    $vehicle = Vehicle::create($vehicleData);

    if ($request->hasFile('image')) {
        $vehicle->addMedia($request->file('image'))
            ->toMediaCollection('vehicles');
    }

    return response()->json([
        'success' => true,
        'message' => 'Vehicle created successfully.',
        'vehicle' => [
            'id'             => $vehicle->id,
            'name'           => $vehicle->name,
            'vehicle_number' => $vehicle->vehicle_number,
        ]
    ]);
}
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'             => 'required|string|max:255',
            'vehicle_number'   => 'required|string|max:255',
            'rwc_number'       => 'required|string|max:255',
            'engine_number'    => 'required|string|max:255',
            'brand_id'         => 'required',
            'model_id'         => 'nullable|exists:models,id',
            'color_id'         => 'nullable|exists:colors,id',
            'body_type'        => 'nullable|string|max:255',
            'vehicle_condition'=> 'nullable|string|max:255',
            'transmisssion'    => 'nullable|string|max:255',
            'fuel_type'        => 'nullable|string|max:255',
            'left_hand_drive'  => 'required|boolean',
            'hybird'           => 'required|boolean',
            'engine_type'      => 'nullable|string|max:255',
            'description'      => 'nullable|string|max:255',
            'vehicle_type'     => 'nullable|exists:vehicle_types,id',
            'ac_status'        => 'nullable',
            'seat_booking_price'=>'nullable',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $data=[
            'name'             => $request->name,
            'company_id'       => auth()->user()->companyId(),
            'vehicle_number'   => $request->vehicle_number,
            'rwc_number'       => $request->rwc_number,
            'engine_number'    => $request->engine_number,
            'brand_id'         => $request->brand_id,
            'model_id'         => $request->model_id,
            'color_id'         => $request->color_id,
            'body_type'        => $request->body_type,
            'vehicle_condition'=> $request->vehicle_condition,
            'transmisssion'    => $request->transmisssion,
            'fuel_type'        => $request->fuel_type,
            'left_hand_drive'  => $request->left_hand_drive,
            'hybird'           => $request->hybird,
            'engine_type'      => $request->engine_type,
            'description'      => $request->description,
            'is_visible'       => $request->is_visible,
            'vehicle_type'         => $request->vehicle_type,
            'ac_status'        => $request->ac_status,
        ];

        $rcVerificationData = $this->decodeVerificationPayload($request, 'rc_verification_data');
        if ($rcVerificationData !== null) {
            $data['rc_verification_data'] = $rcVerificationData;
            $data['rc_verified_at'] = now();
        }

        $authUser = auth()->user();


        $vehicle = Vehicle::create($data);

        if ($request->hasFile('image')) {
            $vehicle->addMedia($request->file('image'))->toMediaCollection('vehicles');
        }

        $vehicle->update();

        if($vehicle->id){
            return redirect()->route('vehicle.index')->with(['success'=>'Vehicle Created Successfully']);
        }else{
            return back()->with(['error'=>'Vehicle Not Created']);
        }
    }

    public function show(Vehicle $vehicle)
    {
        //
    }

    public function edit($id)
    {
        $data =  Vehicle::findOrFail($id);

        $vehicle_types= VehicleType::where('is_visible',1)->get();
        $brands = Brand::where('is_visible',1)->get();
        $colors= Color::where('is_visible',1)->get();
        $models = Models::where('is_visible',1)->get();

        return view('admin.vehicle.edit',compact('data','vehicle_types','brands','colors','models'));

    }

    public function update(Request $request)
    {

        // echo "<pre>";

        // die;
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name'             => 'required|string|max:255',
            'vehicle_number'   => 'required|string|max:255',
            'rwc_number'       => 'required|string|max:255',
            'engine_number'    => 'required|string|max:255',
            'brand_id'         => 'required',
            'model_id'         => 'nullable|exists:models,id',
            'color_id'         => 'nullable|exists:colors,id',
            'body_type'        => 'nullable|string|max:255',
            'vehicle_condition'=> 'required|string|max:255',
            'engine_type'      => 'required|string|max:255',
            'transmisssion'    => 'required|string|max:255',
            'fuel_type'        => 'required|string|max:255',
            'left_hand_drive'  => 'required|boolean',
            'hybird'           => 'required|boolean',
            'is_visible'       => 'nullable|boolean',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'description'      => 'nullable|string|max:255',
            'layout_id'          => 'nullable|exists:vehicle_layouts,id',
            'vehicle_type'          => 'nullable|exists:vehicle_types,id',
            'ac_status'        => 'nullable',
            'route_id'         =>'nullable|exists:routes,id',
            'seat_booking_price'=>'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Retrieve the vehicle record using the id from the request (adjust if using a route parameter)
        $vehicle = Vehicle::findOrFail($request->id);

        // Update the vehicle with corresponding fields
        $vehicle->name             = $request->name;
        $vehicle->vehicle_number   = $request->vehicle_number;
        $vehicle->rwc_number       = $request->rwc_number;
        $vehicle->engine_number    = $request->engine_number;
        $vehicle->brand_id         = $request->brand_id;
        $vehicle->model_id         = $request->model_id;
        $vehicle->color_id         = $request->color_id;
        $vehicle->body_type        = $request->body_type;
        $vehicle->vehicle_condition= $request->vehicle_condition;
        $vehicle->engine_type      = $request->engine_type;
        $vehicle->transmisssion    = $request->transmisssion;
        $vehicle->fuel_type        = $request->fuel_type;
        $vehicle->left_hand_drive  = $request->left_hand_drive;
        $vehicle->hybird           = $request->hybird;
        $vehicle->is_visible       = $request->is_visible;
        $vehicle->description      = $request->description;
        $vehicle->vehicle_type      = $request->vehicle_type;
        $vehicle->ac_status        = $request->ac_status;

        $authUser = auth()->user();


        if ($request->hasFile('image')) {
            // Optionally clear previous images
            $vehicle->clearMediaCollection('vehicles');
            $vehicle->addMediaFromRequest('image')->toMediaCollection('vehicles');
        }

        if ($vehicle->save()) {

            return redirect()->route('vehicle.index')->with('success', 'Vehicle updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Vehicle update failed.');
        }
    }

    public function destroy(string $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        if (!$vehicle) {
            return redirect()->back()->withErrors(['error' => 'Vehicle not found.'])->withInput();
        }

        $vehicle->delete();
        return response()->json(['success' => 'Vehicle Deleted Successfully']);
    }

      public function journey($vehicle_id)
    {
        $journeys = Journey::where('vehicle_id',$vehicle_id)->get();

        $vehicle = Vehicle::findOrFail($vehicle_id);

        $routes = Route::where('id', $vehicle->route_id)
        ->orWhere('reverse_route_of', $vehicle->route_id)
        ->get();
        return view('admin.vehicle.journey',compact('journeys','vehicle','routes'));
    }
    public function journey_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'route_id' => 'required|exists:routes,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $journey = Journey::create([
            'vehicle_id' => $request->vehicle_id,
            'route_id' => $request->route_id,
        ]);
        $routeBusStops = BusRouteStop::where('bus_route_id', $request->route_id)->orderBy('sl_no')->get();

        if ($routeBusStops->isNotEmpty()) {
            foreach ($routeBusStops as $bus_stop) {
                JourneyStoppage::create([
                    'journey_id' => $journey->id,
                    'stop_id' => $bus_stop->bus_stop_id,
                    'sl_no' => $bus_stop->sl_no,
                ]);
            }
        }

        return back()->with('success', 'Journey Created Successfully');
    }

    public function journey_start($journey_id)
    {
        $journey = Journey::find($journey_id);
        if (!$journey) {
            return back()->with('error', 'Journey not found.');
        }
        if ($journey->start_time) {
            return back()->with('error', 'Journey alredy started.');
        }
        $journey->start_time = Carbon::now();
        $journey->save();
        return back()->with('success', 'Journey Started');
    }
    public function journey_view_map(Request $request)
    {
        $route = Route::with('routeBusStop')->findOrFail($request->route_id);
        $selectedBusStops = BusStop::whereIn('id', $route->routeBusStop->pluck('bus_stop_id'))
            ->orderByRaw("FIELD(id, " . $route->routeBusStop->pluck('bus_stop_id')->implode(',') . ")")
            ->get();

        $busStopsArray = collect();

        foreach ($selectedBusStops as $stop) {
            $busStopsArray->push([
                'id' => $stop->id,
                'name' => $stop->name,
                'type' => 'mid',
                'arrival_time' => JourneyStoppage::where('journey_id', $request->journey_id)
                    ->where('stop_id', $stop->id)
                    ->value('arrival_time')
            ]);
        }

        $routeId = $request->route_id;
        $busRouteStops = BusRouteStop::where('bus_route_id', $routeId)
            ->orderBy('sl_no')
            ->with('busStop')
            ->get();

        return response()->json([
            'success' => true,
            'busStops' => $busStopsArray,
            'normalRoute' => $busRouteStops
        ]);
    }

    public function journey_delete($journey_id)
    {
        $journey = Journey::find($journey_id);

        if (!$journey) {
            return response()->json(['error' => 'Journey not found.'], 404);
        }
        JourneyStoppage::where('journey_id', $journey_id)->delete();
        $journey->delete();

        return response()->json(['success' => 'Journey Deleted Successfully']);
    }
}
