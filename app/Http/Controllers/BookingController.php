<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Vehicle;
use App\Models\TimeTable;
use App\Models\Route;
use App\Models\BusStop;
use App\Models\VehicleType;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\Journey;
use PDF;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\View;
use DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    /*public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => 'required|string',
            'to' => 'required|string',
            'date' => 'required|date',
        ]);        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $from = $request->from;
        $to = $request->to;
        $date = $request->date;

        $from_bus_stop = BusStop::where('name',$from)->first();
        $to_bus_stop = BusStop::where('name',$to)->first();
        

        $routeIds = DB::table('bus_route_stops as from_stop')
                    ->join('bus_route_stops as to_stop', function ($join) {
                        $join->on('from_stop.bus_route_id', '=', 'to_stop.bus_route_id');
                    })
                    ->where('from_stop.bus_stop_id', $from_bus_stop->id)
                    ->where('to_stop.bus_stop_id', $to_bus_stop->id)
                    ->where(function ($query) {
                        $query->whereColumn('from_stop.sl_no', '<', 'to_stop.sl_no');
                    })
                    ->pluck('from_stop.bus_route_id')
                    ->first(); 
                    // ->get(); 
        // return $routeIds;
     
        $vehicleData = DB::table('vehicles')
                ->join('time_tables', 'vehicles.id', '=', 'time_tables.vehicle_id')
                ->join('vehicle_types', 'vehicles.vehicle_type', '=', 'vehicle_types.id')
                ->leftJoin('journeys', 'vehicles.id', '=', 'journeys.vehicle_id')
                ->join('stoppage_time_tables as from_stop', function ($join) use ($from_bus_stop) {
                    $join->on('time_tables.id', '=', 'from_stop.time_table_id')
                        ->where('from_stop.stop_id', $from_bus_stop->id);
                })
                ->leftJoin('media', function ($join) {
                    $join->on('media.model_id', '=', 'vehicle_types.id')
                        ->where('media.model_type', VehicleType::class)
                        ->where('media.collection_name', 'vehicle-type-icon');
                })
                ->where('time_tables.route_id', $routeIds)
                ->where('vehicles.is_visible', 1)
                ->where('vehicle_types.is_visible', 1)
                // ->where('time_tables.date', $date) // Ensure it matches today's schedule
                ->whereRaw("TIME(from_stop.time) > ?", [now()->format('H:i:s')]) // Only get buses that haven't left
                ->select(
                    'journeys.id as journey_ids',
                    'vehicle_types.name as vehicle_type',
                    DB::raw('COUNT(vehicles.id) as count'),
                    DB::raw('GROUP_CONCAT(vehicles.id) as vehicle_ids'),
                    'vehicles.latitude',
                    'vehicles.longitude',
                    'media.file_name',
                    'media.id as media_id'
                )
                ->groupBy('journeys.id','vehicle_types.name', 'media.file_name', 'media.id','vehicles.latitude', 'vehicles.longitude')
                ->get();
        

        // return $vehicleData;

     
        // $vehicleCounts = $vehicleData->mapWithKeys(function ($item) use ($from_bus_stop) {
        //     $imageUrl = $item->media_id
        //                 ? asset("storage/{$item->media_id}/{$item->file_name}")
        //                 : null;

        //     // Calculate distance using Google Distance Matrix API (or helper function)
        //     // $distanceResult = getDistanceFromAPI($item->latitude, $item->longitude, $userLatitude, $userLongitude);
        //     $distanceResult = getDistanceFromAPI($item->latitude, $item->longitude, $from_bus_stop->latitude, $from_bus_stop->longitude);

        //     if ($distanceResult['status'] === 'success') {
        //         $distanceText = $distanceResult['distance_text'];
        //         $distanceValue = $distanceResult['distance_value'];
        //         $durationText = $distanceResult['duration_text'];
        //         $durationValue = $distanceResult['duration_value'];
        //     } else {
        //         $distanceText = 'N/A';
        //         $distanceValue = null;
        //         $durationText = 'N/A';
        //         $durationValue = null;
        //     }

        //     return [
        //         $item->vehicle_type => [
        //             'count' => $item->count,
        //             'ids' => explode(',', $item->vehicle_ids),
        //             'image' => $imageUrl,
        //             'journey_ids' => explode(',', $item->journey_ids),
        //             'distance_text' => $distanceText,
        //             'distance_value' => $distanceValue,
        //             'duration_text' => $durationText,
        //             'duration_value' => $durationValue,
        //         ],
        //     ];
        // });

        // d($vehicleData);

        // return $vehicleData;

        $vehicleCounts = $vehicleData->mapWithKeys(function ($item) {
            $imageUrl = $item->media_id 
                        ? asset("storage/{$item->media_id}/{$item->file_name}")
                        : null;
            return [
                $item->vehicle_type => [
                    'count' => $item->count,
                    'ids' => explode(',', $item->vehicle_ids),
                    'image' => $imageUrl,
                    'journey_ids' => explode(',', $item->journey_ids),
                ],
            ];
        });

        // return $vehicleCounts;
        return view('frontend.vehicle_search',compact('vehicleCounts','from','to','date','from_bus_stop'));
    }*/

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => 'required|string',
            'to' => 'required|string',
            'date' => 'required|date',
        ]);        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $from = $request->from;
        $to = $request->to;
        $date = $request->date;

        $from_bus_stop = BusStop::where('name',$from)->first();
        $to_bus_stop = BusStop::where('name',$to)->first();
        

        $routeIds = DB::table('bus_route_stops as from_stop')
                    ->join('bus_route_stops as to_stop', function ($join) {
                        $join->on('from_stop.bus_route_id', '=', 'to_stop.bus_route_id');
                    })
                    ->where('from_stop.bus_stop_id', $from_bus_stop->id)
                    ->where('to_stop.bus_stop_id', $to_bus_stop->id)
                    ->where(function ($query) {
                        $query->whereColumn('from_stop.sl_no', '<', 'to_stop.sl_no');
                    })
                    ->pluck('from_stop.bus_route_id');
                    // ->first(); 
                    // ->get(); 
        // return $routeIds;
     
        $vehicleData = DB::table('vehicles')
                ->join('time_tables', 'vehicles.id', '=', 'time_tables.vehicle_id')
                ->join('vehicle_types', 'vehicles.vehicle_type', '=', 'vehicle_types.id')
                // ->leftJoin('journeys', 'vehicles.id', '=', 'journeys.vehicle_id')
                ->join('stoppage_time_tables as from_stop', function ($join) use ($from_bus_stop) {
                    $join->on('time_tables.id', '=', 'from_stop.time_table_id')
                        ->where('from_stop.stop_id', $from_bus_stop->id);
                })
                ->leftJoin('media', function ($join) {
                    $join->on('media.model_id', '=', 'vehicle_types.id')
                        ->where('media.model_type', VehicleType::class)
                        ->where('media.collection_name', 'vehicle-type-icon');
                })
                ->whereIn('time_tables.route_id', $routeIds)
                ->where('vehicles.is_visible', 1)
                ->where('vehicle_types.is_visible', 1)
                // ->where('time_tables.date', $date) // Ensure it matches today's schedule
                ->whereRaw("TIME(from_stop.time) > ?", [now()->format('H:i:s')]) // Only get buses that haven't left
                ->select(
                    // 'journeys.id as journey_ids',
                    'vehicle_types.name as vehicle_type',
                    DB::raw('COUNT(vehicles.id) as count'),
                    DB::raw('GROUP_CONCAT(vehicles.id) as vehicle_ids'),
                    // 'vehicles.latitude',
                    // 'vehicles.longitude',
                    'media.file_name',
                    'media.id as media_id'
                )//'journeys.id',
                ->groupBy('vehicle_types.name', 'media.file_name', 'media.id') // ,'vehicles.latitude', 'vehicles.longitude'
                ->get();
        

        // return $vehicleData;

     
        // $vehicleCounts = $vehicleData->mapWithKeys(function ($item) use ($from_bus_stop) {
        //     $imageUrl = $item->media_id
        //                 ? asset("storage/{$item->media_id}/{$item->file_name}")
        //                 : null;

        //     // Calculate distance using Google Distance Matrix API (or helper function)
        //     // $distanceResult = getDistanceFromAPI($item->latitude, $item->longitude, $userLatitude, $userLongitude);
        //     $distanceResult = getDistanceFromAPI($item->latitude, $item->longitude, $from_bus_stop->latitude, $from_bus_stop->longitude);

        //     if ($distanceResult['status'] === 'success') {
        //         $distanceText = $distanceResult['distance_text'];
        //         $distanceValue = $distanceResult['distance_value'];
        //         $durationText = $distanceResult['duration_text'];
        //         $durationValue = $distanceResult['duration_value'];
        //     } else {
        //         $distanceText = 'N/A';
        //         $distanceValue = null;
        //         $durationText = 'N/A';
        //         $durationValue = null;
        //     }

        //     return [
        //         $item->vehicle_type => [
        //             'count' => $item->count,
        //             'ids' => explode(',', $item->vehicle_ids),
        //             'image' => $imageUrl,
        //             'journey_ids' => explode(',', $item->journey_ids),
        //             'distance_text' => $distanceText,
        //             'distance_value' => $distanceValue,
        //             'duration_text' => $durationText,
        //             'duration_value' => $durationValue,
        //         ],
        //     ];
        // });

        // d($vehicleData);

        // return $vehicleData;

        $vehicleCounts = $vehicleData->mapWithKeys(function ($item) {
            $imageUrl = $item->media_id 
                        ? asset("storage/{$item->media_id}/{$item->file_name}")
                        : null;
            return [
                $item->vehicle_type => [
                    'count' => $item->count,
                    'ids' => explode(',', $item->vehicle_ids),
                    'image' => $imageUrl,
                    // 'journey_ids' => explode(',', $item->journey_ids),
                ],
            ];
        });

        if ($request->ajax()) {
            return response()->json([
                'vehicleCounts' => $vehicleCounts,
                'from' => $from,
                'to' => $to,
                'date' => $date,
                'from_stop' => $from_bus_stop,
                'to_stop' => $to_bus_stop,
            ]);
        }

        // return $vehicleCounts;
        return view('frontend.vehicle_search',compact('vehicleCounts','from','to','date','from_bus_stop','to_bus_stop'));
    }

  
    /*public function vehicle_list(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');
        $date = $request->query('date') ?? date('Y-m-d');
        $ids = $request->query('ids');
        $vehicleIds = explode(',', $ids);
        $journeyIds = explode(',', $request->query('journey_ids'));
        // $vehicles = Vehicle::whereIn('id', $vehicleIds)
        //     ->where('is_visible', 1)
        //     // ->with(['timeTable.stoppage.busStop']) // Load timeTable with stoppage times and stop details
        //     ->with('timeTable') // Load timeTable with stoppage times and stop details
        //     ->get()
        //     ->keyBy('id');

        $vehicles = Vehicle::whereIn('id', $vehicleIds)
        ->where('is_visible', 1)
        ->with(['timeTable', 'journeys' => function ($query) use ($journeyIds) {
            $query->whereIn('id', $journeyIds);
        }])
        ->get()
        ->keyBy('id');
        

        // d($vehicles);

        $from_bus_stop = BusStop::where('name', $from)->first();
        $to_bus_stop = BusStop::where('name', $to)->first();

        
        $vehicleData = [];

        foreach ($vehicles as $vehicle) {
            $from_sl_no = getStopOrder($vehicle->route_id, $from_bus_stop->id);
            $to_sl_no = getStopOrder($vehicle->route_id, $to_bus_stop->id);
            // return $to_sl_no;
            $bookings = Booking::where('vehicle_id', $vehicle->id)->whereDate('created_at',$date)
                                ->get(['id', 'type', 'from_stop_id', 'to_stop_id']);
                                // ->pluck('id');
            $fare = getFare($vehicle->route_id, $from_bus_stop->id, $to_bus_stop->id);
            // $booked_seats = BookingSeat::whereIn('booking_id', $bookings)->pluck('seat_number')->toArray();
            
            $booked_seats = [];
            $partially_booked_seats = [];

            // return $vehicle->timeTable;
            foreach ($bookings as $booking) {
                $seats = BookingSeat::where('booking_id', $booking->id)->pluck('seat_number')->toArray();
                
                if ($booking->type == 'full') {
                    $booked_seats = array_merge($booked_seats, $seats);
                } elseif ($booking->type == 'partial') {
                    
                    // $partially_booked_seats = array_merge($partially_booked_seats, $seats);

                    $booking_from_sl_no = getStopOrder($vehicle->route_id, $booking->from_stop_id);
                    $booking_to_sl_no = getStopOrder($vehicle->route_id, $booking->to_stop_id);

                    foreach ($seats as $seat) {
                        if ($from_sl_no >= $booking_from_sl_no && $to_sl_no <= $booking_to_sl_no) {
                            $booked_seats[] = $seat;
                        } else {
                            if (!isset($partially_booked_seats[$seat])) {
                                $partially_booked_seats[$seat] = [];
                            }

                            $partially_booked_seats[$seat][] = [
                                'start_sl_no' => $booking_from_sl_no,
                                'end_sl_no' => $booking_to_sl_no
                            ];
                        }
                    }
                }
            }

            // Remove seats that are not available for the requested range

            // $partially_booked_seats = $filtered_partially_booked_seats;
            $filtered_partially_booked_seats = [];

            foreach ($partially_booked_seats as $seat => $booked_segments) {
                if (isSeatPartiallyAvailable($seat, $from_sl_no, $to_sl_no, $partially_booked_seats)) {
                    $filtered_partially_booked_seats[] = $seat;
                }
            }

            $partially_booked_seats = $filtered_partially_booked_seats;
            $partially_booked_avaliable_from = [];
            foreach($partially_booked_seats as $seat){
                // return $seat;
                $vehicleId = $vehicle->id;
                $bookingSeat = BookingSeat::where('seat_number', $seat)
                    ->whereHas('booking', function ($query) use ($vehicleId) {
                        $query->where('vehicle_id', $vehicleId)
                            ->where('type', 'partial');
                    })
                    ->first();
        
                $booking = Booking::find($bookingSeat->booking_id);

                if ($bookingSeat) {
                    $busStop = BusStop::find($booking->to_stop_id);
                    $partially_booked_avaliable_from[] = [
                        'seat_number' => $seat,
                        'bus_stop_name' => $busStop->name
                    ];
                }
            }
            // return $partially_booked_avaliable_from;

            $available_seat = max(0, $vehicle->seating_capacity - count($booked_seats) - count($partially_booked_seats));

            $vehicleData[$vehicle->id] = [
                'booked_seats' => $booked_seats,
                'partially_booked_seats' => $partially_booked_seats,
                'fare' => $fare,
                'available_seat' => $available_seat,
                'timetables' => $vehicle->timeTable,
                'partially_booked_avaliable_from' => $partially_booked_avaliable_from,
            ];
        }

        //  d($vehicleData);

        return view('frontend.vehicle_list', compact('vehicles', 'from', 'to', 'date', 'vehicleData','from_bus_stop','to_bus_stop'));
    }*/

    public function vehicle_list(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');
        $date = $request->query('date') ?? date('Y-m-d');
        $ids = $request->query('ids');
        $vehicleIds = explode(',', $ids);
        $journeyIds = explode(',', $request->query('journey_ids'));
        // $vehicles = Vehicle::whereIn('id', $vehicleIds)
        //     ->where('is_visible', 1)
        //     // ->with(['timeTable.stoppage.busStop']) // Load timeTable with stoppage times and stop details
        //     ->with('timeTable') // Load timeTable with stoppage times and stop details
        //     ->get()
        //     ->keyBy('id');

        $vehicles = Vehicle::whereIn('id', $vehicleIds)
        ->where('is_visible', 1)
        ->with(['timeTable', 'journeys' => function ($query) use ($journeyIds) {
            $query->whereIn('id', $journeyIds);
        }])
        ->get()
        ->keyBy('id');
        

        // d($vehicles);

        $from_bus_stop = BusStop::where('name', $from)->first();
        $to_bus_stop = BusStop::where('name', $to)->first();

        
        $vehicleData = [];

        foreach ($vehicles as $vehicle) {
            foreach ($vehicle->timeTable as $timetable) {
                $from_sl_no = getStopOrder($vehicle->route_id, $from_bus_stop->id);
                $to_sl_no = getStopOrder($vehicle->route_id, $to_bus_stop->id);
        
                $bookings = Booking::where('vehicle_id', $vehicle->id)
                    ->where('time_table_id', $timetable->id) // Ensure bookings are linked to this specific timetable
                    // ->whereDate('created_at', $date)
                    ->whereDate('booking_date',$date)
                    ->get(['id', 'type', 'from_stop_id', 'to_stop_id']);
        
                $booked_seats = [];
                $partially_booked_seats = [];
        
                foreach ($bookings as $booking) {
                    $seats = BookingSeat::where('booking_id', $booking->id)->pluck('seat_number')->toArray();
        
                    if ($booking->type == 'full') {
                        $booked_seats = array_merge($booked_seats, $seats);
                    } elseif ($booking->type == 'partial') {
                        $booking_from_sl_no = getStopOrder($vehicle->route_id, $booking->from_stop_id);
                        $booking_to_sl_no = getStopOrder($vehicle->route_id, $booking->to_stop_id);
        
                        foreach ($seats as $seat) {
                            if ($from_sl_no >= $booking_from_sl_no && $to_sl_no <= $booking_to_sl_no) {
                                $booked_seats[] = $seat;
                            } else {
                                if (!isset($partially_booked_seats[$seat])) {
                                    $partially_booked_seats[$seat] = [];
                                }
                                $partially_booked_seats[$seat][] = [
                                    'start_sl_no' => $booking_from_sl_no,
                                    'end_sl_no' => $booking_to_sl_no
                                ];
                            }
                        }
                    }
                }
        
                // Filter partially booked seats
                $filtered_partially_booked_seats = [];
                foreach ($partially_booked_seats as $seat => $booked_segments) {
                    if (isSeatPartiallyAvailable($seat, $from_sl_no, $to_sl_no, $partially_booked_seats)) {
                        $filtered_partially_booked_seats[] = $seat;
                    }
                }
        
                $partially_booked_seats = $filtered_partially_booked_seats;
                $partially_booked_avaliable_from = [];
                foreach($partially_booked_seats as $seat){
                    // return $seat;
                    $vehicleId = $vehicle->id;
                    $bookingSeat = BookingSeat::where('seat_number', $seat)
                        ->whereHas('booking', function ($query) use ($vehicleId) {
                            $query->where('vehicle_id', $vehicleId)
                                ->where('type', 'partial');
                        })
                        ->first();
            
                    $booking = Booking::find($bookingSeat->booking_id);

                    if ($bookingSeat) {
                        $busStop = BusStop::find($booking->to_stop_id);
                        $partially_booked_avaliable_from[] = [
                            'seat_number' => $seat,
                            'bus_stop_name' => $busStop->name
                        ];
                    }
                }
                // return $partially_booked_avaliable_from;
                $available_seat = max(0, $vehicle->seating_capacity - count($booked_seats) - count($partially_booked_seats));
        
                $vehicleData[$vehicle->id][$timetable->id] = [
                    'booked_seats' => $booked_seats,
                    'partially_booked_seats' => $partially_booked_seats,
                    'fare' => getFare($vehicle->route_id, $from_bus_stop->id, $to_bus_stop->id),
                    'available_seat' => $available_seat,
                    'timetable' => $timetable,
                    'partially_booked_avaliable_from' => $partially_booked_avaliable_from,
                ];
            }
        }
        

        //  d($vehicleData);
        if ($request->ajax()) {
            $html = View::make('frontend.vehicle_list_content', compact('vehicles', 'from', 'to', 'date', 'vehicleData','from_bus_stop','to_bus_stop'))->render();
            return response()->json([
                'html' => $html,
            ]);
        }

        return view('frontend.vehicle_list', compact('vehicles', 'from', 'to', 'date', 'vehicleData','from_bus_stop','to_bus_stop'));
    }
    
    
    
    
    public function directVehicleSearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => 'required|string',
            'to' => 'required|string',
            'date' => 'required|date',
            'vehicle_type_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $from = $request->from;
        $to = $request->to;
        $date = $request->date;
        $vehicle_type_id = $request->vehicle_type_id;
        $journeyIds = explode(',', $request->query('journey_ids'));
        $from_stop = BusStop::where('name', $from)->first();
        $to_stop = BusStop::where('name', $to)->first();

        if (!$from_stop || !$to_stop) {
            return response()->json(['error' => 'Invalid bus stop(s) provided'], 400);
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

        $vehicles = Vehicle::whereIn('id', $vehicleIds)
            ->where('is_visible', 1)
            ->with(['timeTable', 'journeys'])
            ->get()
            ->keyBy('id');
        $from_bus_stop = BusStop::where('name', $from)->first();
        $to_bus_stop = BusStop::where('name', $to)->first();
        
        if ($vehicles->isEmpty()) {
            if ($request->ajax()) {
                $html = '<p class="text-center">No vehicles found for the selected route and date.</p>';
                return response()->json([
                    'html' => $html,
                    'data' => [
                        'from_stop' => $from_bus_stop,
                        'to_stop' => $to_bus_stop,
                    ]
                ]);
            }
        }

        
        $vehicleData = [];

        foreach ($vehicles as $vehicle) {
            foreach ($vehicle->timeTable as $timetable) {
                $from_sl_no = getStopOrder($vehicle->route_id, $from_bus_stop->id);
                $to_sl_no = getStopOrder($vehicle->route_id, $to_bus_stop->id);
        
                $bookings = Booking::where('vehicle_id', $vehicle->id)
                    ->where('time_table_id', $timetable->id) // Ensure bookings are linked to this specific timetable
                    // ->whereDate('created_at', $date)
                    ->whereDate('booking_date',$date)
                    ->get(['id', 'type', 'from_stop_id', 'to_stop_id']);
        
                $booked_seats = [];
                $partially_booked_seats = [];
        
                foreach ($bookings as $booking) {
                    $seats = BookingSeat::where('booking_id', $booking->id)->pluck('seat_number')->toArray();
        
                    if ($booking->type == 'full') {
                        $booked_seats = array_merge($booked_seats, $seats);
                    } elseif ($booking->type == 'partial') {
                        $booking_from_sl_no = getStopOrder($vehicle->route_id, $booking->from_stop_id);
                        $booking_to_sl_no = getStopOrder($vehicle->route_id, $booking->to_stop_id);
        
                        foreach ($seats as $seat) {
                            if ($from_sl_no >= $booking_from_sl_no && $to_sl_no <= $booking_to_sl_no) {
                                $booked_seats[] = $seat;
                            } else {
                                if (!isset($partially_booked_seats[$seat])) {
                                    $partially_booked_seats[$seat] = [];
                                }
                                $partially_booked_seats[$seat][] = [
                                    'start_sl_no' => $booking_from_sl_no,
                                    'end_sl_no' => $booking_to_sl_no
                                ];
                            }
                        }
                    }
                }
        
                // Filter partially booked seats
                $filtered_partially_booked_seats = [];
                foreach ($partially_booked_seats as $seat => $booked_segments) {
                    if (isSeatPartiallyAvailable($seat, $from_sl_no, $to_sl_no, $partially_booked_seats)) {
                        $filtered_partially_booked_seats[] = $seat;
                    }
                }
        
                $partially_booked_seats = $filtered_partially_booked_seats;
                $partially_booked_avaliable_from = [];
                foreach($partially_booked_seats as $seat){
                    // return $seat;
                    $vehicleId = $vehicle->id;
                    $bookingSeat = BookingSeat::where('seat_number', $seat)
                        ->whereHas('booking', function ($query) use ($vehicleId) {
                            $query->where('vehicle_id', $vehicleId)
                                ->where('type', 'partial');
                        })
                        ->first();
            
                    $booking = Booking::find($bookingSeat->booking_id);

                    if ($bookingSeat) {
                        $busStop = BusStop::find($booking->to_stop_id);
                        $partially_booked_avaliable_from[] = [
                            'seat_number' => $seat,
                            'bus_stop_name' => $busStop->name
                        ];
                    }
                }
                // return $partially_booked_avaliable_from;
                $available_seat = max(0, $vehicle->seating_capacity - count($booked_seats) - count($partially_booked_seats));
        
                $vehicleData[$vehicle->id][$timetable->id] = [
                    'booked_seats' => $booked_seats,
                    'partially_booked_seats' => $partially_booked_seats,
                    'fare' => getFare($vehicle->route_id, $from_bus_stop->id, $to_bus_stop->id),
                    'available_seat' => $available_seat,
                    'timetable' => $timetable,
                    'partially_booked_avaliable_from' => $partially_booked_avaliable_from,
                ];
            }
        }
        

        //  d($vehicleData);
        if ($request->ajax()) {
            $html = View::make('frontend.vehicle_list_content', compact('vehicles', 'from', 'to', 'date', 'vehicleData','from_bus_stop','to_bus_stop'))->render();
            return response()->json([
                'html' => $html,
               'data'=> [
                    'from_stop' => $from_bus_stop,
                    'to_stop' => $to_bus_stop,
                ]

            ]);
        }

        // return view('frontend.vehicle_list', compact('vehicles', 'from', 'to', 'date', 'vehicleData','from_bus_stop','to_bus_stop'));
    }





    public function seat_booking(Request $request ,$vehicleId)
    {
        $vehicle = Vehicle::find($vehicleId);
        $seat_number = $request->seat_number;
        $from = $request->from;
        $to = $request->to;
        $date = $request->date ?? date('Y-m-d');
        $timeTableID = $request->timeid;
        $journeyId = $request->journey_id;
        $timeTable = TimeTable::find($timeTableID);
        $journey = Journey::find($journeyId);

        $from_bus_stop = BusStop::where('name',$from)->first();
        $to_bus_stop = BusStop::where('name',$to)->first();

        $from_sl_no = getStopOrder($vehicle->route_id, $from_bus_stop->id);
        $to_sl_no = getStopOrder($vehicle->route_id, $to_bus_stop->id);

        $fare= getFare($vehicle->route_id, $from_bus_stop->id, $to_bus_stop->id);
        $seat_booking_price = $vehicle->seat_booking_price;
        // $bookings = Booking::where('vehicle_id', $vehicle->id)->pluck('id');
        $bookings = Booking::where('vehicle_id', $vehicle->id)
                            ->where('time_table_id',$timeTable->id)
                            // ->whereDate('created_at',$date)
                            ->whereDate('booking_date',$date)
                            ->get(['id', 'type', 'from_stop_id', 'to_stop_id']);
        $booked_seats = [];
        $partially_booked_seats = [];
    
        foreach ($bookings as $booking) {
            $seats = BookingSeat::where('booking_id', $booking->id)->pluck('seat_number')->toArray();
            
            if ($booking->type == 'full') {
                $booked_seats = array_merge($booked_seats, $seats);
            } elseif ($booking->type == 'partial') {
                // $partially_booked_seats = array_merge($partially_booked_seats, $seats);
                $booking_from_sl_no = getStopOrder($vehicle->route_id, $booking->from_stop_id);
                $booking_to_sl_no = getStopOrder($vehicle->route_id, $booking->to_stop_id);

                foreach ($seats as $seat) {
                    if ($from_sl_no >= $booking_from_sl_no && $to_sl_no <= $booking_to_sl_no) {
                        $booked_seats[] = $seat;
                    } else {
                        if (!isset($partially_booked_seats[$seat])) {
                            $partially_booked_seats[$seat] = [];
                        }

                        $partially_booked_seats[$seat][] = [
                            'start_sl_no' => $booking_from_sl_no,
                            'end_sl_no' => $booking_to_sl_no
                        ];
                    }
                }
            }
        }

        // Remove seats that are not available for the requested range

        // $partially_booked_seats = $filtered_partially_booked_seats;
        $filtered_partially_booked_seats = [];

        foreach ($partially_booked_seats as $seat => $booked_segments) {
            if (isSeatPartiallyAvailable($seat, $from_sl_no, $to_sl_no, $partially_booked_seats)) {
                $filtered_partially_booked_seats[] = $seat;
            }
        }

        $partially_booked_seats = $filtered_partially_booked_seats;

        $partially_booked_avaliable_from = [];
        foreach($partially_booked_seats as $seat){
            // return $seat;
            $vehicleId = $vehicle->id;
            $bookingSeat = BookingSeat::where('seat_number', $seat)
                ->whereHas('booking', function ($query) use ($vehicleId) {
                    $query->where('vehicle_id', $vehicleId)
                        ->where('type', 'partial');
                })
                ->first();
    
            $booking = Booking::find($bookingSeat->booking_id);

            if ($bookingSeat) {
                $busStop = BusStop::find($booking->to_stop_id);
                $partially_booked_avaliable_from[] = [
                    'seat_number' => $seat,
                    'bus_stop_name' => $busStop->name,
                    'bus_stop_id' => $busStop->id
                ];
            }
        }

        // $avaliable_seat = $vehicle->seating_capacity - count($booked_seats);
        $avaliable_seat = max(0, $vehicle->seating_capacity - count($booked_seats) - count($partially_booked_seats));

        // d($booked_seats);
        // d($partially_booked_seats);

        return view('frontend.seat_booking',compact('vehicle','timeTable','journey','seat_number','booked_seats','fare','seat_booking_price', 'from_bus_stop','to_bus_stop','avaliable_seat','partially_booked_seats','partially_booked_avaliable_from'));
    }

    public function getAvailableBusStop(Request $request)
    {
        $seatNumber = $request->seat_number;
        $vehicleId = $request->vehicle_id;

        $bookingSeat = BookingSeat::where('seat_number', $seatNumber)
            ->whereHas('booking', function ($query) use ($vehicleId) {
                $query->where('vehicle_id', $vehicleId)
                    ->where('type', 'partial');
            })
            ->first();

  
        $booking = Booking::find($bookingSeat->booking_id);

        if ($bookingSeat) {
            $busStop = BusStop::find($booking->to_stop_id);

            return response()->json([
                'success' => true,
                'bus_stop' => $busStop ? $busStop->name : 'Unknown Stop'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Seat availability details not found.'
        ]);
    }


    public function proceed_booking(Request $request )
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'from_stop_id' => 'required',
            'to_stop_id' => 'required',
            'route_id' => 'required',
            'layout_id' => 'required',
            'vehicle_id' => 'required',

        ]);      
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 422);
        }

        // return $request->seat_number;

        $wallet = Wallet::where('user_id', $user->id)->first();
        $vehicle = Vehicle::find($request->vehicle_id);
        // return $vehicle->route;

        if($vehicle->route->from_bus_stop ==  $request->from_stop_id && $vehicle->route->to_bus_stop ==  $request->to_stop_id) 
        {
            $bookingType = 'full';
        }else{
            $bookingType = 'partial';
        }
        // return $bookingType;

        if ($wallet && $wallet->balance >= $request->totalAmount) { 
    
            $booking_number = generateBookingNumber();

            $booking = Booking::create([
                'booking_number'=> $booking_number,
                'booking_date'=>$request->booking_date,
                'user_id'=>$user->id,
                'vehicle_id'=>$request->vehicle_id,
                'route_id'=>$request->route_id,
                'layout_id'=>$request->layout_id,
                'from_stop_id'=>$request->from_stop_id,
                'to_stop_id'=>$request->to_stop_id,
                'total_amount'=>$request->totalAmount,
                'status'=>'confirm',
                'type'=>$bookingType,
                'time_table_id'=>$request->time_table_id,
                'journey_id'=>$request->journey_id,

            ]);

            // foreach ($request->seat_number as $seatString) {
            //     $seats = explode(',', $seatString); // Split seat numbers
            //     foreach ($seats as $seat) {
            //         $seat = trim($seat); // Remove spaces
            //         if (!empty($seat)) {
            //             BookingSeat::create([
            //                 'booking_id' => $booking->id,
            //                 'seat_number'=> $seat,  // Store individual seat
            //                 'avaliable_from_stop_id' => 4,
            //                 'avaliable_from_stop_text' => 'avaliable from sam bazar'
            //             ]);
            //         }
            //     }
            // }

            $seatData = $request->seat_number; // Laravel receives an array with a string inside

            if (is_array($seatData) && count($seatData) > 0) {
                $seatJson = $seatData[0]; // Extract the JSON string
                $seats = json_decode($seatJson, true); // Decode JSON into array
                if (is_array($seats)) {
                    foreach ($seats as $seat) {
                        // d($seat['seat_number']);
                        if (!empty($seat['seat_number'])) {
                            $busStop = !empty($seat['available_from']) ? BusStop::find($seat['available_from']) : null;
                            // return $seat['available_from'];
                            BookingSeat::create([
                                'booking_id' => $booking->id,
                                'seat_number' => $seat['seat_number'],
                                'avaliable_from_stop_id' => !empty($seat['available_from']) ? $seat['available_from'] : null, // Default to NULL if empty
                                'avaliable_from_stop_text' => $busStop ? 'Available from '.$busStop->name : NULL,
                                'amount' => $request->ticket_price + $request->booking_charge
                            ]);

                        }
                    }
                }
            }



            $transaction = Transaction::create([
                'user_id' => $user->id,
                'booking_id' => $booking->id,
                'amount' => $request->totalAmount,
                'debit' => $request->totalAmount,
                'payment_id' => now() ,
                'type' => 'debit',
                'payment_method' => 'wallet',
                'payment_status' => 'completed',
                'remarks' => 'Ticket Booking via wallet',
            ]);

            $wallet= Wallet::where('user_id',$user->id)->first();
            if ($wallet) {
                $wallet->balance -= $request->totalAmount;
                $wallet->save();
            }

            $qrData = json_encode([
                'booking_number' => $booking->booking_number,
                'user' => $user->name,
                'vehicle' => $booking->vehicle_id,
                'route' => $booking->route_id,
                'from' => $booking->from_stop_id,
                'to' => $booking->to_stop_id,
                'seats' => $request->seat_number,
                'amount' => $request->totalAmount,
            ]);

            $qrCodePath = "qrcodes/$booking->booking_number" . $booking->id . ".png";
            Storage::disk('public')->put($qrCodePath, QrCode::format('png')->size(200)->generate($qrData));
            $booking->qr_code = $qrCodePath;
            $booking->save();

            $redirectUrl = route('front.user.profile');



            return response()->json([
                'success' => true,
                'message' => 'Booking confirm',
                'redirect_url' => $redirectUrl,
            ]);
        }else{
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $order = $api->order->create([
                'receipt' => uniqid(),
                'amount' => $request->totalAmount * 100, // Amount in paise
                'currency' => 'INR'
            ]);

            return response()->json([
                'success' => false,
                'recharge_wallet' => 1,
                'order_id' => $order['id'],
                'amount' => $order['amount'],
                'message' => 'Please Recharge your wallet',
            ]);
        }

        // echo "<pre>";
        // print_r($request->all());
        // die;


    }

    public function print_ticket($booking_id)
    {
       $booking= Booking::find($booking_id);
    //    $booked_seats = BookingSeat::where('booking_id',$booking->id)->pluck('seat_number')->toArray();
       $booked_seats = BookingSeat::where('booking_id',$booking->id)->get();
       $timeTable = TimeTable::find($booking->time_table_id);

       $html = view('frontend.ticket', compact('booking', 'booked_seats','timeTable'))->render();
       $pdf = PDF::loadHTML($html)->setPaper([0, 0, 165, 450])->set_option('isHtml5ParserEnabled', true);
       
       return $pdf->stream($booking->booking_number . '.pdf', ['Attachment' => false]);
        //  return view('frontend.ticket', compact('booking', 'booked_seats'));
    }
}