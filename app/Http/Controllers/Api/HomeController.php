<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\TimeTable;
use App\Models\Route;
use App\Models\BusStop;
use App\Models\VehicleType;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Support\Facades\Auth;
use App\Models\Driver;
use App\Models\BookingRequest;
use Illuminate\Support\Facades\Log;
use App\Models\Offer;
class HomeController extends Controller
{
    //

    public function index()
    {
        $bus_stops=  BusStop::where('is_visible',1)->get();
        return response()->json([
            'response' => true,
            'message' => 'all active data',
            'data' => [
            'bus_stops' => $bus_stops,
            ],
        ]);
    }
    
    
    public function get_vehicle_types()
    {
        $vehicle_types= VehicleType::where('is_visible',1)->get();
         return response()->json([
            'response' => true,
            'message' => 'get vehicle types',
            'data' => [
                'vehicle_types' => $vehicle_types,
            ],
        ]);
    }
    
    public function get_nearby_busstop(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'response' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = settings()->nearby_search_radius;
        

        
        
        
    
        $bus_stops = BusStop::select("*", DB::raw(
            "(
                6371 * acos(
                    cos(radians($latitude)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians($longitude)) +
                    sin(radians($latitude)) *
                    sin(radians(latitude))
                )
            ) AS distance"
        ))
        ->having("distance", "<=", $radius)
        ->orderBy("distance", "asc")
        ->get();
    
        return response()->json([
            'response' => true,
            'message' => 'Nearby bus stops retrieved successfully.',
            'data' => [
                'bus_stops' => $bus_stops,
            ],
        ]);
    }
    
    
//     
    
    
    // public function search_cab(Request $request)
    // {
    //     $request->validate([
    //         'from_lat' => 'required|numeric',
    //         'from_lng' => 'required|numeric',
    //         'to_lat' => 'required|numeric',
    //         'to_lng' => 'required|numeric',
    //     ]);
    
    //     $from_lat = $request->from_lat;
    //     $from_lng = $request->from_lng;
    //     $radius = settings()->cab_search_radius ?? 10; // default to 10km if not set
    
    //     // Get nearest available driver
    //     $driver = Driver::with('user')
    //         ->select('drivers.*', DB::raw("
    //             (6371 * acos(
    //                 cos(radians($from_lat)) *
    //                 cos(radians(latitude)) *
    //                 cos(radians(longitude) - radians($from_lng)) +
    //                 sin(radians($from_lat)) *
    //                 sin(radians(latitude))
    //             )) AS distance
    //         "))
    //         ->whereNotNull('latitude')
    //         ->whereNotNull('longitude')
    //         ->where('is_online', 1)
    //         ->where('ride_mode', 1)
    //         ->having('distance', '<=', $radius)
    //         ->orderBy('distance', 'asc')
    //         ->first();
    
    //     if (!$driver) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No drivers available nearby',
    //         ]);
    //     }
    
    //     // Log the matched driver
    //     \Log::info('Matched Driver', [
    //         'driver_id' => $driver->id,
    //         'user_id' => $driver->user_id,
    //         'distance' => $driver->distance,
    //         'fcm_token' => $driver->user->fcm_token ?? null
    //     ]);
    
    //     // Send notification if FCM token is present
    //     $fcmToken = $driver->user->fcm_token ?? null;
    //     if ($fcmToken) {
    //         sendNotificationToDriver($fcmToken, [
    //             'title' => 'New Ride Request',
    //             'body' => 'Pickup nearby. Tap to accept the ride.',
    //             'data' => [
    //                 'from_lat' => $from_lat,
    //                 'from_lng' => $from_lng,
    //                 'to_lat' => $request->to_lat,
    //                 'to_lng' => $request->to_lng,
    //                 'driver_id' => $driver->id,
    //             ]
    //         ]);
    //     }
    
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Driver notified',
    //         'driver' => [
    //             'driver_id' => $driver->id,
    //             'user_id' => $driver->user_id,
    //             'distance' => round($driver->distance, 2),
    //             'latitude' => $driver->latitude,
    //             'longitude' => $driver->longitude,
    //         ]
    //     ]);
    // }
    
    
    
    
    public function search_cab(Request $request)
    {
        $request->validate([
            'from_lat' => 'required|numeric',
            'from_lng' => 'required|numeric',
            'to_lat' => 'required|numeric',
            'to_lng' => 'required|numeric',
        ]);

        $from_lat = $request->from_lat;
        $from_lng = $request->from_lng;
        $radius = settings()->cab_search_radius ?? 10; // default to 10km if not set

        // Get nearest available driver
        $driver = Driver::with('user')
            ->select('drivers.*', DB::raw("
                (6371 * acos(
                    cos(radians($from_lat)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians($from_lng)) +
                    sin(radians($from_lat)) *
                    sin(radians(latitude))
                )) AS distance
            "))
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('is_online', 1)
            ->where('ride_mode', 1)
            ->having('distance', '<=', $radius)
            ->orderBy('distance', 'asc')
            ->first();

        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'No drivers available nearby',
            ]);
        }

        $bookingRequest = BookingRequest::create([
            'user_id' => $request->user()->id,
            'pickup_lat' => $from_lat,
            'pickup_lng' => $from_lng,
            'drop_lat' => $request->to_lat,
            'drop_lng' => $request->to_lng,
            'amount'=>200,
            'status' => 'pending',
            'type'=>"cab",
            'requested_at' => now(),
        ]);

        // Log the matched driver
        // \Log::info('Matched Driver', [
        //     'driver_id' => $driver->user_id,
        //     'user_id' => $driver->user_id,
        //     'distance' => $driver->distance,
        //     'fcm_token' => $driver->user->fcm_token ?? null
        // ]);

        // Send notification if FCM token is present
        $fcmToken = $driver->user->fcm_token ?? null;
        if ($fcmToken) {
           $notification= sendNotificationToDriver($fcmToken, [
                'title' => 'New Ride Request',
                'body' => 'Pickup nearby. Tap to accept the ride.',
                'data' => [
                    'booking_request_id' => $bookingRequest->id,
                    'from_lat' => $from_lat,
                    'from_lng' => $from_lng,
                    'to_lat' => $request->to_lat,
                    'to_lng' => $request->to_lng,
                    'driver_id' => $driver->user_id,
                ]
            ]);
            
            $responseData = $notification->getData(true);
            
        }

        return response()->json([
            'success' => true,
            'message' => 'Driver notified',
            'booking_request_id' => $bookingRequest->id,
            'notification'=>$responseData,
            'driver' => [
                'driver_id' => $driver->user_id,
                'user_id' => $driver->user_id,
                'distance' => round($driver->distance, 2),
                'latitude' => $driver->latitude,
                'longitude' => $driver->longitude,
            ]
        ]);
    }
    
    
    
    



public function sendNotificationToDriverTest(Request $request)
{
    $request->validate([
        'player_id' => 'required|string', // This is the OneSignal player_id
        'title' => 'required|string',
        'body' => 'required|string',
        'data' => 'nullable|array',
    ]);

    $fields = [
        'app_id' => env('ONESIGNAL_APP_ID'),
        'include_player_ids' => [$request->player_id],
        'headings' => ['en' => $request->title],
        'contents' => ['en' => $request->body],
        'data' => $request->data ?? [],
    ];

    $headers = [
        'Content-Type: application/json',
        'Authorization: Basic ' . env('ONESIGNAL_REST_API_KEY'),
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return response()->json([
        'status' => $httpCode,
        'response' => json_decode($response, true)
    ]);
}


    
    
    




    // public function search(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'from' => 'required|string',
    //         'to' => 'required|string',
    //         'date' => 'required|date',
    //     ]);        
    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 422);
    //     }

    //     $from = $request->from;
    //     $to = $request->to;
    //     $date = $request->date;

    //     $from_bus_stop = BusStop::where('name',$from)->first();
    //     $to_bus_stop = BusStop::where('name',$to)->first();
    //     $routeIds = Route::where('from_bus_stop', $from_bus_stop->id)
    //     ->where('to_bus_stop', $to_bus_stop->id)
    //     ->pluck('id');
    //     $vehicleData = DB::table('vehicles')
    //         ->join('vehicle_types', 'vehicles.vehicle_type', '=', 'vehicle_types.id')
    //         ->whereIn('vehicles.route_id', $routeIds)
    //         ->where('vehicles.is_visible', 1)
    //         ->where('vehicle_types.is_visible', 1)
    //         ->select('vehicle_types.name as vehicle_type', DB::raw('COUNT(vehicles.id) as count'), DB::raw('GROUP_CONCAT(vehicles.id) as vehicle_ids'))
    //         ->groupBy('vehicle_types.name')
    //         ->get();
    //     $vehicleCounts = $vehicleData->mapWithKeys(function ($item) {
    //         return [
    //             $item->vehicle_type => [
    //                 'count' => $item->count,
    //                 'ids' => explode(',', $item->vehicle_ids),
    //                 'vehicle_list'=> Vehicle::whereIn('id', explode(',', $item->vehicle_ids))->where('is_visible', 1)->get(),
    //             ],
    //         ];
    //     });

    //     // $vehicles = Vehicle::whereIn('id', $vehicleCounts)->where('is_visible', 1)->get();

    //     return response()->json([
    //         'response' => true,
    //         'message' => 'all active data',
    //         'data' => [
    //         'vehicle' => $vehicleCounts,
    //         ],
    //     ]);


    // }
    
    
     public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_id' => 'required|integer',
            'to_id' => 'required|integer',
            'date' => 'required|date',
            'vehicle_type_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $date = $request->date;
        $from_stop = BusStop::find($request->from_id);
        $to_stop = BusStop::find($request->to_id);
        $vehicle_type_id = $request->vehicle_type_id;

        if (!$from_stop || !$to_stop) {
            return response()->json([
                'response' => false,
                'message' => 'Invalid bus stop(s) provided',
            ]);
        }

        $routeIds = DB::table('bus_route_stops as from_stop')
            ->join('bus_route_stops as to_stop', function ($join) {
                $join->on('from_stop.bus_route_id', '=', 'to_stop.bus_route_id');
            })
            ->where('from_stop.bus_stop_id', $from_stop->id)
            ->where('to_stop.bus_stop_id', $to_stop->id)
            ->whereColumn('from_stop.sl_no', '<', 'to_stop.sl_no')
            ->pluck('from_stop.bus_route_id');

        $vehicleIds = DB::table('vehicles')
            ->join('time_tables', 'vehicles.id', '=', 'time_tables.vehicle_id')
            ->join('vehicle_types', 'vehicles.vehicle_type', '=', 'vehicle_types.id')
            ->join('stoppage_time_tables as from_stops', function ($join) use ($from_stop) {
                $join->on('time_tables.id', '=', 'from_stops.time_table_id')
                    ->where('from_stops.stop_id', $from_stop->id);
            })
            ->whereIn('time_tables.route_id', $routeIds)
            ->where('vehicles.is_visible', 1)
            ->where('vehicle_types.is_visible', 1)
            ->where('vehicles.vehicle_type', $vehicle_type_id)
            ->whereRaw("TIME(from_stops.time) > ?", [now()->format('H:i:s')])
            ->distinct()
            ->pluck('vehicles.id')
            ->toArray();
            
            // return  $vehicleIds;

        $vehicles = Vehicle::whereIn('id', $vehicleIds)
            ->where('is_visible', 1)
            ->with(['timeTable', 'journeys' => function($query) use ($date) {
                $query->whereDate('start_time', $date);
            }])
            // ->with(['timeTable', 'journeys'])
            ->get()
            ->keyBy('id');
            
            
            if ($vehicles->isEmpty()) {
                return response()->json([
                    'response' => false,
                    'message' => 'Vehicle not found',
                    'data' => []
                ]);
            }

            $vehicleData = [];
            $from_sl_no = fn($route_id) => getStopOrder($route_id, $from_stop->id);
            $to_sl_no = fn($route_id) => getStopOrder($route_id, $to_stop->id);

            foreach ($vehicles as $vehicle) {
                foreach ($vehicle->timeTable as $timetable) {
                    // Filter journeys for this timetable and today's date
                    $todayJourneys = $vehicle->journeys->filter(function($journey) use ($timetable, $date) {
                        // Make sure we're comparing dates in the same format
                        $journeyDate = $journey->start_time instanceof \Carbon\Carbon 
                            ? $journey->start_time->format('Y-m-d') 
                            : date('Y-m-d', strtotime($journey->start_time));
                        
                        return $journey->time_table_id == $timetable->id && 
                               $journeyDate == $date;
                    });
                    
                    $fromStoppageTime = $timetable->stoppageTimeForStop($from_stop->id)->first();
                    $toStoppageTime = $timetable->stoppageTimeForStop($to_stop->id)->first();

        
                    $fromOrder = $from_sl_no($vehicle->route_id);
                    $toOrder = $to_sl_no($vehicle->route_id);

                    $bookings = Booking::where('vehicle_id', $vehicle->id)
                        ->where('time_table_id', $timetable->id)
                        ->whereDate('booking_date', $date)
                        ->get(['id', 'type', 'from_stop_id', 'to_stop_id']);

                    $booked_seats = [];
                    $partially_booked_seats = [];

                    foreach ($bookings as $booking) {
                        $seats = BookingSeat::where('booking_id', $booking->id)->pluck('seat_number')->toArray();

                        if ($booking->type === 'full') {
                            $booked_seats = array_merge($booked_seats, $seats);
                        } else {
                            $booking_from_sl = getStopOrder($vehicle->route_id, $booking->from_stop_id);
                            $booking_to_sl = getStopOrder($vehicle->route_id, $booking->to_stop_id);

                            foreach ($seats as $seat) {
                                if ($fromOrder >= $booking_from_sl && $toOrder <= $booking_to_sl) {
                                    $booked_seats[] = $seat;
                                } else {
                                    $partially_booked_seats[$seat][] = [
                                        'start_sl_no' => $booking_from_sl,
                                        'end_sl_no' => $booking_to_sl,
                                    ];
                                }
                            }
                        }
                    }

                    $filtered_partial = [];
                    foreach ($partially_booked_seats as $seat => $segments) {
                        if (isSeatPartiallyAvailable($seat, $fromOrder, $toOrder, $partially_booked_seats)) {
                            $filtered_partial[] = $seat;
                        }
                    }

                    $partial_avail_info = [];
                    foreach ($filtered_partial as $seat) {
                        $bookingSeat = BookingSeat::where('seat_number', $seat)
                            ->whereHas('booking', function ($q) use ($vehicle) {
                                $q->where('vehicle_id', $vehicle->id)->where('type', 'partial');
                            })
                            ->first();

                        if ($bookingSeat) {
                            $booking = Booking::find($bookingSeat->booking_id);
                            $busStop = BusStop::find($booking->to_stop_id);
                            $partial_avail_info[] = [
                                'seat_number' => $seat,
                                'bus_stop_name' => $busStop->name ?? '',
                            ];
                        }
                    }

                    $available_seat = max(0, $vehicle->seating_capacity - count($booked_seats) - count($filtered_partial));

                    $vehicleData[$vehicle->id][$timetable->id] = [
                        'booked_seats' => $booked_seats,
                        'partially_booked_seats' => $filtered_partial,
                        'fare' => getFare($vehicle->route_id, $from_stop->id, $to_stop->id),
                        'available_seat' => $available_seat,
                        'timetable' => $timetable,
                        'partially_booked_avaliable_from' => $partial_avail_info,
                        'journeys' => $todayJourneys->values(),
                    ];
                }
            }

        return response()->json([
            'response' => true,
            'message' => 'Available vehicle data',
            'data' => [
                'date' => $date,
                'vehicles' => $vehicles,
                'vehicle_data' => $vehicleData,
                'from_stop' => $from_stop,
                'to_stop' => $to_stop,
                'from_stoppage_time' => $fromStoppageTime,
                'to_stoppage_time' => $toStoppageTime,
            ],
        ]);
    }

    public function get_vehicle_details($vehicle_id)
    {

        $vehicle = Vehicle::find($vehicle_id);
        $bookings = Booking::where('vehicle_id', $vehicle->id)->pluck('id');
        $booked_seats = BookingSeat::whereIn('booking_id', $bookings)->pluck('seat_number')->toArray();
        return response()->json([
            'response' => true,
            'message' => 'get booking vehicle details',
            'data' => [
            'vehicle' => $vehicle,
            'booked_seats' => $booked_seats,
            ],
        ]);

    }
    
    
     public function get_offers(Request $request)
    {
        $offers = Offer::latest()->get()->map(function ($offer) {
            return [
                'id' => $offer->id,
                'name' => $offer->name,
                'description' => $offer->description,
                'discount_value' => $offer->discount_value,
                'image_url' => $offer->getFirstMediaUrl('offer-image'),
            ];
        });
    
        return response()->json([
            'response' => true,
            'message' => 'get all offers',
            'data' => [
                'offers' => $offers
            ],
        ]);
    }

    
    
    
    

    public function proceed_booking(Request $request)
    {
        // $user = Auth::user();
   

        $validator = Validator::make($request->all(), [
            'from_stop_id' => 'required',
            'to_stop_id' => 'required',
            'route_id' => 'required',
            'layout_id' => 'required',
            'vehicle_id' => 'required',
        ]);      
        if ($validator->fails()) {
       
            return response()->json(['response' => false, 'message' => $validator->errors()], 422);
  
        }
        $wallet = Wallet::where('user_id', $user->id)->first();

        if ($wallet && $wallet->balance >= $request->totalAmount){

            $booking_number = generateBookingNumber();
            $booking = Booking::create([
                'booking_number'=> $booking_number,
                'user_id'=>$user->id,
                'vehicle_id'=>$request->vehicle_id,
                'route_id'=>$request->route_id,
                'layout_id'=>$request->layout_id,
                'from_stop_id'=>$request->from_stop_id,
                'to_stop_id'=>$request->to_stop_id,
                'total_amount'=>$request->totalAmount,
                'status'=>'confirm',
            ]);

            foreach ($request->seat_number as $seatString) {
                $seats = explode(',', $seatString); 
                foreach ($seats as $seat) {
                    $seat = trim($seat);
                    if (!empty($seat)) {
                        BookingSeat::create([
                            'booking_id' => $booking->id,
                            'seat_number'=> $seat,
                        ]);
                    }
                }
            }

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'booking_id' => $booking->id,
                'amount' => $request->totalAmount,
                'debit' => $request->totalAmount,
                'payment_id' =>'788955' ,
                'type' => 'debit',
                'payment_method' => 'wallet',
                'payment_status' => 'completed',
                'remarks' => 'Ticket Booking via wallet',
            ]);

            $redirectUrl = route('home.print.ticket', $booking->id);
            return response()->json([
                'success' => true,
                'message' => 'Booking confirm',
                'redirect_url' => $redirectUrl,
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Please Recharge your wallet',
            ]);
        }

        // echo "<pre>";
        // print_r($request->all());
        // die;
    }
    
}
