<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverLocation;
use App\Models\DeliverySchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoutePlaybackController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Route Playback Show', only: ['index', 'getRouteData']),
        ];
    }

    public function index()
    {
        $drivers = Driver::all();
        return view('admin.route-playback.index', compact('drivers'));
    }

    public function getRouteData(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'date' => 'required|date'
        ]);

        $driverId = $request->driver_id;
        // $date = Carbon::parse($request->date);
        $date = $request->date;

        $driver = Driver::find($driverId);
        $driverId = $driver->id;

        $delivery_schedule = DeliverySchedule::whereDate('delivery_date',$date)->where('driver_id',$driver->user_id)->first();

        // Get locations for the selected date
        $locations = DriverLocation::where('driver_id', $driverId)
            ->whereDate('created_at', $date)
            ->orderBy('created_at')
            ->get();
        // return $date;

        if ($locations->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No location data found for the selected date'
            ]);
        }

        // Format locations for the map
        $routeData = $locations->map(function ($location) use ($delivery_schedule) {
            return [
                'lat' => (float)$location->latitude,
                'lng' => (float)$location->longitude,
                'speed' => $location->speed,
                'timestamp' => $location->created_at->toDateTimeString(),
                'vehicle' => $delivery_schedule->vehicle ? $delivery_schedule->vehicle->vehicle_number : 'Unknown'
            ];
        });

        return response()->json([
            'success' => true,
            'route' => $routeData,
            'start_location' => $routeData->first(),
            'end_location' => $routeData->last(),
            'total_points' => $routeData->count(),
            'date' => $date
        ]);
        
        // $data = [
        //     'route' => $routeData,
        //     'start_location' => $routeData->first(),
        //     'end_location' => $routeData->last(),
        //     'total_points' => $routeData->count(),
        //     'date' => $date->format('Y-m-d')
        // ];
        // return view('admin.route-playback.index')->with($data);
    }
}