<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Journey;
use App\Models\JourneyStoppage;
use App\Models\BusRouteStop;
use Carbon\Carbon;
use App\Models\VehicleType;
use App\Models\User;
use App\Models\Company;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Route;

class VehicleController extends Controller
{

    public function get_vehicle_types(Request $request){
        $vehicle_types = VehicleType::where('is_visible', 1)
                                    ->with('media') // still need it to get the media URL
                                    ->orderBy('id', 'desc')
                                    ->get()
                                    ->map(function ($type) {
                                        return [
                                            'id' => $type->id,
                                            'name' => $type->name,
                                            // ... include other fields you need
                                            'image_url' => $type->media->first()?->getUrl() ?? null,
                                        ];
                                    });

        return response()->json([
            'response' => true,
            'message' => 'These are all vehicle types',
            'data' => [
                'types' => $vehicle_types
            ],
        ]);

    }

   

    public function get_vehicle_timings_by_driver_or_conductur(Request $request){
        $user = $request->user();
    
        if (!$user->hasRole('Driver') && !$user->hasRole('Conductor')) {
            return response()->json([
                'response' => false,
                'message' => 'The user is not a Driver or Conductor.',
                'data' => [],
            ]);
        }
    
        if ($user->hasRole('Driver')) {
            $driver = Driver::where('user_id', $user->id)->first();
    
            if (!$driver || !$driver->vehicle_id) {
                return response()->json([
                    'response' => false,
                    'message' => 'No vehicle assigned to this driver.',
                    'data' => [],
                ]);
            }
    
            $today = Carbon::today()->toDateString();
    
            $vehicle_with_timings = Vehicle::where('id', $driver->vehicle_id)
                ->where('is_visible', 1)
                ->with(['timeTable' => function ($query) use ($today) {
                    $query->with([
                        'route.fromBusStop',
                        'route.toBusStop',
                        'route.viaBusStop',
                        'journeys' => function ($journeyQuery) use ($today) {
                            $journeyQuery->whereDate('start_time', $today);
                        }
                    ]);
                }])
                ->get();
    
            return response()->json([
                'response' => true,
                'message' => 'Vehicle timings with or without journey data.',
                'data' => [
                    'vehicle_with_timings' => $vehicle_with_timings
                ],
            ]);
        }
    
        return response()->json([
            'response' => false,
            'message' => 'Only drivers are supported in this version.',
            'data' => [],
        ]);
    }
    
    
    public function get_journey_bus_stops(Request $request,string $route_id){
        $route = Route::with([
                                'fromBusStop',
                                'toBusStop', 
                                'viaBusStop'
                            ])->findOrFail($route_id);
        $bus_route_stops = BusRouteStop::with('busStop')->where('bus_route_id', $route_id)->orderBy('sl_no')->get();
        return response()->json([
            'response' => true,
            'message' => 'These are the route with bus stops',
            'data' => [
                'route' => $route,
                'route_with_bus_stops' => $bus_route_stops
            ],
        ]);
    }
    
    public function index($vehicle_id)
    {
        $journeys = Journey::where('vehicle_id',$vehicle_id)->whereDate('start_time',date('Y-m-d'))->get();
        return response()->json([
            'response' => true,
            'message' => 'These is the all journes of a vehicle',
            'data' => [
                'journeys' => $journeys,
            ],
        ]);
    }

    public function get_journey_data(Request $request ){
        
    
        
        $route_id = $request->route_id ;
        $journey_id =  $request->journey_id;
        
        if($journey_id){
            $journey = Journey::find($journey_id);
            $journey_bus_stops = JourneyStoppage::with('busStop')->where('journey_id',$journey->id)->orderBy('sl_no')->get();


            return response()->json([
                'response' => true,
                'message' => 'Journey data fetch Successfully',
                'data' => [
                'journey' => $journey,
                'bus_stops' =>  $journey_bus_stops ,
                ],
            ]);
            
        }else{
            
             $route = Route::with([
                                'fromBusStop',
                                'toBusStop', 
                                'viaBusStop'
                            ])->findOrFail($route_id);
            $bus_route_stops = BusRouteStop::with('busStop')->where('bus_route_id', $route_id)->orderBy('sl_no')->get();

            return response()->json([
                'response' => true,
                'message' => 'Route data fetch Successfully',
                'data' => [
                'route' => $route,
                'bus_stops' =>  $bus_route_stops ,
                ],
            ]);
        }
        
   

    }


    public function journey_create(Request $request,$need = null)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'route_id' => 'required|exists:routes,id',
            'time_table_id' => 'required|exists:time_tables,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $journey = Journey::create([
            'vehicle_id' => $request->vehicle_id,
            'route_id' => $request->route_id,
            'timetable_id' => $request->time_table_id,
            'start_time' => Carbon::now(),
        ]);
        $routeBusStops = BusRouteStop::where('bus_route_id', $request->route_id)->orderBy('sl_no')->get();

        if ($routeBusStops->isNotEmpty()) {
            foreach ($routeBusStops as $bus_stop) {
                JourneyStoppage::create([
                    'journey_id' => $journey->id,
                    'stop_id' => $bus_stop->bus_stop_id,
                    'sl_no' => $bus_stop->sl_no,
                    'arrival_time' => $bus_stop->sl_no == 1 ? Carbon::now() : null, 
                ]);
            }
        }
        $journeys = Journey::where('vehicle_id',$request->vehicle_id)->get();

        if($need != null){
            return $journey;
        }else{
            return response()->json([
                'response' => true,
                'message' => 'Journey Created Successfully',
                'data' => [
                'journeys' => $journeys,
                ],
            ]);
        }

    }

    public function journey_start(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'route_id' => 'required|exists:routes,id',
            'time_table_id' => 'required|exists:time_tables,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $journey = Journey::where('vehicle_id',$request->vehicle_id)
                        ->where('route_id',$request->route_id)
                        ->where('timetable_id',$request->time_table_id)
                        ->whereDate('created_at',date('Y-m-d'))->exists();



        if (!$journey) {
            $created_journey = $this->journey_create($request,'get_journey_id');
       
             
        
            $journey_bus_stops = JourneyStoppage::where('journey_id',$created_journey->id)->orderBy('sl_no')->get();

            return response()->json([
                'response' => true,
                'message' => 'Journey created and started',
                'data' => [
                    'journey' => $created_journey,
                    'journey_bus_stops' =>  $journey_bus_stops ,
                ],
            ]);
        }else{
            $journey = Journey::where('vehicle_id',$request->vehicle_id)
                        ->where('route_id',$request->route_id)
                        ->where('timetable_id',$request->time_table_id)
                        ->whereDate('created_at',date('Y-m-d'))->first();
                        
            $journey_bus_stops = JourneyStoppage::where('journey_id',$journey->id)->orderBy('sl_no')->get();
            // return $journey->start_time;
            if(!empty($journey->start_time)){
                return response()->json([
                    'response' => true,
                    'message' => 'Journey alredy started',
                    'data' => [
                        'journey' => $journey,
                        'journey_bus_stops' =>  $journey_bus_stops ,
                    ],
                ]);
            }else{
                $journey->start_time = Carbon::now();
                $journey->update();

                return response()->json([
                    'response' => true,
                    'message' => 'Journey started',
                    'data' => [
                    'journey' => $journey,
                    'journey_bus_stops' =>  $journey_bus_stops ,
                    ],
                ]);
            }
        }
    }

    public function update_journey_departure(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'journey_id' => 'required|exists:journey_stoppages,journey_id',
            'journey_stoppage_id' => 'required|exists:journey_stoppages,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $journey = Journey::findOrFail($request->journey_id);
        if($journey){


            $journey_stoppage= JourneyStoppage::findOrFail($request->journey_stoppage_id);
            
            if ($journey_stoppage) {
                $journey_stoppage->arrival_time = Carbon::now();
                $journey_stoppage->departure_time = Carbon::now();
                $journey_stoppage->is_completed = 1;
                $journey_stoppage->save();
            }else{
                return response()->json([
                    'response' => false,
                    'message' => 'please enter correct journey id and journey stoppage id',
                   
                ]);
            }
        


            $journey_stoppage_list= JourneyStoppage::where('journey_id', $journey->id)->get();
            return response()->json([
                'response' => true,
                'message' => 'Journey Stoppage Update',
                'data' => [
                    'journey_stoppage_list' => $journey_stoppage_list,
                ],
            ]);
        }else{

            return response()->json([
                'response' => false,
                'message' => 'please start your journey first',
               
            ]);

        }

       

    }

    // public function journey_end(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'vehicle_id' => 'required|exists:vehicles,id',
    //         'route_id' => 'required|exists:routes,id',
    //         'time_table_id' => 'required|exists:time_tables,id',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 422);
    //     }

    //     $journey = Journey::where('vehicle_id',$request->vehicle_id)
    //                     ->where('route_id',$request->route_id)
    //                     ->where('timetable_id',$request->time_table_id)
    //                     ->whereDate('created_at',date('Y-m-d'))->exists();

    //     // $journey = Journey::find($journey_id);
    //     // if (!$journey) {
    //     //     return response()->json(['error' => 'Journey not found.'], 401);
    //     // }
    //     // if ($journey->start_time) {
    //     //     return response()->json(['error' => 'Journey alredy started.'], 401);

    //     // }
    //     // $journey->start_time = Carbon::now();
    //     // $journey->save();

    //     if (!$journey) {
    //         return response()->json([
    //             'response' => true,
    //             'message' => 'Journey not started yet',
    //             'data' => [],
    //         ]);
    //     }else{
    //         $journey = Journey::where('vehicle_id',$request->vehicle_id)
    //                     ->where('route_id',$request->route_id)
    //                     ->where('timetable_id',$request->time_table_id)
    //                     ->whereDate('created_at',date('Y-m-d'))->first();
    //         // return $journey->start_time;
    //         if(!empty($journey->end_time)){
    //             return response()->json([
    //                 'response' => true,
    //                 'message' => 'Journey alredy ended',
    //                 'data' => [
    //                     'journey' => $journey,
    //                 ],
    //             ]);
    //         }else{
    //             $journey->end_time = Carbon::now();
    //             $journey->update();

    //             return response()->json([
    //                 'response' => true,
    //                 'message' => 'Journey ended successfully',
    //                 'data' => [
    //                 'journey' => $journey,
    //                 ],
    //             ]);
    //         }
    //     }
    // }
    
    
       public function journey_end(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'journey_id' => 'required|exists:journeys,id',
            'journey_stoppage_id' => 'required|exists:journey_stoppages,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $journey = Journey::findOrFail($request->journey_id);

        if (!empty($journey->end_time)) {
            return response()->json([
                'response' => false,
                'message' => 'Journey already ended',
                'data' => [
                    'journey' => $journey,
                ],
            ]);
        }

        $journey_stoppage = JourneyStoppage::findOrFail($request->journey_stoppage_id);
        
        $now = Carbon::now();

        $journey_stoppage->update([
            'arrival_time' => $now,
            'departure_time' => $now,
            'is_completed' => 1,
        ]);
        $journey->end_time = $now;
        $journey->save();

        return response()->json([
            'response' => true,
            'message' => 'Journey ended successfully',
            'data' => [
                'journey' => $journey,
            ],
        ]);
    }

    public function trip_history(Request $request){
        $user = $request->user();

        if($user->hasRole('Driver')){
            $driver = Driver::where('user_id',$user->id)->first();
            $vehicle = Vehicle::find($driver->vehicle_id);
            if($vehicle){
                $journeys = Journey::with([
                                            'vehicle',
                                            'route' => function($query) {
                                                    $query->with([
                                                        'fromBusStop',
                                                        'toBusStop',
                                                        'viaBusStop'
                                                    ]);
                                                }])
                                        // ])
                                        ->where('vehicle_id', $vehicle->id)
                                        ->get()
                ->map(function ($journey) {
                    if ($journey->start_time && $journey->end_time) {
                        $journey->status = 'completed';
                    } else {
                        $journey->status = 'pending';
                    }
                    return $journey;
                });
                return response()->json([
                    'response' => true,
                    'message' => 'These are the vehicle timings',
                    'data' => [
                        'history' => $journeys
                    ],
                ]);
            }else{
                return response()->json([
                    'response' => false,
                    'message' => 'No vehicle assigned to this driver.',
                    'data' => [
                        'history' => []
                    ],
                ]);
            }
        }
    }

    public function update_vehicle_location(Request $request){
        $vehicle = Vehicle::find($request->vehicle_id);
        if($vehicle){
            $vehicle->latitude = $request->latitude;
            $vehicle->longitude = $request->longitude;
            $vehicle->update();

            return response()->json([
                'response' => true,
                'message' => 'Vehicle Location Updated Successfully',
                'data' => [
                    'vehicle' => $vehicle
                ],
            ]);
        }else{
            return response()->json([
                'response' => false,
                'message' => 'Vehicle Not Found',
                'data' => [
                    'vehicle' => []
                ],
            ]);
        }
    }
    
    
    //  public function update_journey_departure(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'vehicle_id' => 'required|exists:vehicles,id',
    //         'route_id' => 'required|exists:routes,id',
    //         'time_table_id' => 'required|exists:time_tables,id',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 422);
    //     }

    //     $journey = Journey::where('vehicle_id',$request->vehicle_id)
    //                     ->where('route_id',$request->route_id)
    //                     ->where('timetable_id',$request->time_table_id)
    //                     ->whereDate('created_at',date('Y-m-d'))->exists();



    //     if (!$journey) {
    //         $created_journey = $this->journey_create($request,'get_journey_id');
    //         // $created_journey->start_time = Carbon::now();
    //         // $created_journey->update();
    //         // d($journey->stoppages);
             
        
    //         $journey_bus_stops = JourneyStoppage::where('journey_id',$created_journey->id)->orderBy('sl_no')->get();

    //         return response()->json([
    //             'response' => true,
    //             'message' => 'Journey created and started',
    //             'data' => [
    //                 'journey' => $created_journey,
    //                 'journey_bus_stops' =>  $journey_bus_stops ,
    //             ],
    //         ]);
    //     }else{
    //         $journey = Journey::where('vehicle_id',$request->vehicle_id)
    //                     ->where('route_id',$request->route_id)
    //                     ->where('timetable_id',$request->time_table_id)
    //                     ->whereDate('created_at',date('Y-m-d'))->first();
                        
    //         $journey_bus_stops = JourneyStoppage::where('journey_id',$journey->id)->orderBy('sl_no')->get();
    //         // return $journey->start_time;
    //         if(!empty($journey->start_time)){
    //             return response()->json([
    //                 'response' => true,
    //                 'message' => 'Journey alredy started',
    //                 'data' => [
    //                     'journey' => $journey,
    //                     'journey_bus_stops' =>  $journey_bus_stops ,
    //                 ],
    //             ]);
    //         }else{
    //             $journey->start_time = Carbon::now();
    //             $journey->update();

    //             return response()->json([
    //                 'response' => true,
    //                 'message' => 'Journey started',
    //                 'data' => [
    //                 'journey' => $journey,
    //                 'journey_bus_stops' =>  $journey_bus_stops ,
    //                 ],
    //             ]);
    //         }
    //     }
    // }
    
    
    
}
