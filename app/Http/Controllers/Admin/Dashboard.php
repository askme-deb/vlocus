<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\User;

use App\Models\Vehicle;
use App\Models\DeliverySchedule;
use App\Models\DeliveryScheduleShop;
use App\Models\Shop;
use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginLog;
use App\Models\SOSAlert;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Controllers\Admin\Concerns\ScopesToCompany;

class Dashboard extends Controller implements HasMiddleware
{
    use ScopesToCompany;

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Dashboard Show', only: ['index', 'update_delivery', 'resend_delivery_otp']),
        ];
    }

    public function index(Request $request){
        if (auth()->user()->hasRole('Customer')) {
            return redirect()->route('front.user.profile');
        }

        // Global filters shared by every report tab
        $startDate = $request->filled('start_date') ? $request->start_date : null;
        $endDate   = $request->filled('end_date') ? $request->end_date : null;
        $vehicleId = $request->filled('vehicle_id') ? $request->vehicle_id : null;
        $driverId  = $request->filled('driver_id') ? $request->driver_id : null;

        $data['filters'] = [
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'vehicle_id' => $vehicleId,
            'driver_id'  => $driverId,
        ];

        // Trip Summary
        $trip_summary = $this->get_trip_summary($startDate, $endDate, $vehicleId, $driverId);
        $data['trip_summary'] = $trip_summary;

        // Route History Start
        $chartData = $this->get_route_history($startDate, $endDate, $vehicleId, $driverId);
        $data['routeHistory'] = $chartData;

        // Run & Idle Analytics Data
        $runIdleData = $this->getRunIdleAnalyticsData($startDate, $endDate, $vehicleId, $driverId);
        $data['runIdleAnalytics'] = $runIdleData;

        // Distance Analytics Data
        $distanceData = $this->get_distance($startDate, $endDate, $vehicleId, $driverId);
        $data['distanceAnalytics'] = $distanceData;

        // Geo-fence Analytics Data
        $geofenceData = $this->getGeofenceAnalyticsData($startDate, $endDate, $vehicleId, $driverId);
        $data['geofenceAnalytics'] = $geofenceData;

        $overstayData = $this->getOverstayAnalyticsData($startDate, $endDate, $vehicleId, $driverId);
        $data['overstayAnalytics'] = $overstayData;

        $attendanceAnalytics = $this->getAttendanceAnalyticsData(null, null, $startDate, $driverId);
        $data['attendanceAnalytics'] = $attendanceAnalytics;

        $loginLogoutAnalytics = $this->getLoginLogoutAnalyticsData($startDate, $endDate, $driverId);
        $data['loginLogoutAnalytics'] = $loginLogoutAnalytics;

        $loginTimeAnalytics = $this->getLoginTimeAnalyticsData($startDate, $endDate, $driverId);
        $data['loginTimeAnalytics'] = $loginTimeAnalytics;

        $sosAnalytics = $this->getSOSAnalyticsData($startDate, $endDate, $driverId);
        $data['sosAnalytics'] = $sosAnalytics;

        $driverFilterQuery = User::role('Driver');
        $vehicleFilterQuery = Vehicle::query();
        if ($companyId = auth()->user()->companyId()) {
            $driverFilterQuery->whereHas('driver', fn($q) => $q->where('company_id', $companyId));
            $vehicleFilterQuery->where('company_id', $companyId);
        }
        $data['driver'] = $driverFilterQuery->get();
        $data['vehicle'] = $vehicleFilterQuery->get();


        return view('dashboard')->with($data);
    }


    public function update_delivery(Request $request){
        $delivery = DeliveryScheduleShop::find($request->delivery_id);
        $message = '';
        if ($request->status === 'delivered') {
            if ($request->otp == $delivery->otp) {
                $delivery->is_delivered = 1;
                $delivery->delivered_at = now();
                $message = 'OTP Verified & Delivered Successfully';
            } else {
                return response()->json(['error' => 'Invalid OTP'], 400);
            }
        } else {
            $delivery->is_delivered = 2;
            $delivery->cancel_reason = $request->reason;
            $message = 'Delivery Cancelled Successfully';
        }
        $delivery->update();
        return response()->json(['success' => true,'message'=>$message]);
    }

    public function resend_delivery_otp(Request $request){
        $delivery = DeliveryScheduleShop::find($request->delivery_id);
        $delivery->otp = 1234;
        $delivery->update();
        return response()->json(['success' => true,'message'=>'OTP Send Successfully']);
    }

    private function get_trip_summary($startDate = null, $endDate = null, $vehicleId = null, $driverId = null)
    {
        $tripSummaryQuery = DeliveryScheduleShop::with(['deliverySchedule', 'shop', 'products']);
        $this->scopeToCompany($tripSummaryQuery, 'deliverySchedule.vehicle');

        $reports = $tripSummaryQuery
                ->whereHas('deliverySchedule', function ($q) use ($startDate, $endDate, $vehicleId, $driverId) {
                    if ($startDate && $endDate) {
                        $q->whereDate('delivery_date', '>=', $startDate)
                            ->whereDate('delivery_date', '<=', $endDate);
                    }
                    if ($vehicleId) {
                        $q->where('vehicle_id', $vehicleId);
                    }
                    if ($driverId) {
                        $q->where('driver_id', $driverId);
                    }
                })
                ->get();

        // Real distance per day, computed once per delivery schedule (not per
        // stop) from the driver's GPS trail — same Haversine approach used by
        // the Distance/Overstay reports. This replaces the old dependency on
        // a `calculated_distance` attribute that was never actually set here.
        $scheduleIds = $reports->pluck('delivery_schedule_id')->filter()->unique();
        $scheduleDistanceKm = DeliverySchedule::with(['driver.driver.locations'])
            ->whereIn('id', $scheduleIds)
            ->get()
            ->mapWithKeys(function ($schedule) {
                $locations = $schedule->driver?->driver?->locations
                    ?->filter(function ($location) use ($schedule) {
                        return Carbon::parse($location->created_at)->isSameDay($schedule->delivery_date);
                    })
                    ->sortBy('created_at') ?? collect();

                $distanceMeters = 0;
                $previous = null;
                foreach ($locations as $location) {
                    if ($previous) {
                        $distanceMeters += $this->calculateDistance(
                            $previous->latitude,
                            $previous->longitude,
                            $location->latitude,
                            $location->longitude
                        );
                    }
                    $previous = $location;
                }

                return [$schedule->id => $distanceMeters / 1000];
            });

        $chartData = $reports->groupBy(function ($item) {
            return optional($item->deliverySchedule)->delivery_date
                ? \Carbon\Carbon::parse($item->deliverySchedule->delivery_date)->format('Y-m-d')
                : 'Unknown';
        })->map(function ($dayReports) use ($scheduleDistanceKm) {
            $uniqueScheduleIds = $dayReports->pluck('delivery_schedule_id')->filter()->unique();

            return [
                'total_trips' => $dayReports->count(),
                'total_distance' => $uniqueScheduleIds->sum(function ($id) use ($scheduleDistanceKm) {
                    return $scheduleDistanceKm->get($id, 0);
                }),
                'total_amount' => $dayReports->sum('amount'),
            ];
        });

        $dates = $chartData->keys();
        $totalTrips = $chartData->pluck('total_trips');
        $totalDistance = $chartData->pluck('total_distance');
        $totalAmount = $chartData->pluck('total_amount');

        $trip_summary = [
            'dates' => $dates,
            'totalTrips' => $totalTrips,
            'totalDistance' => $totalDistance,
            'totalAmount' => $totalAmount,
        ];

        return $trip_summary;
    }

    private function get_route_history($startDate = null, $endDate = null, $vehicleId = null, $driverId = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : now()->subDays(30);
        $endDate = $endDate ? Carbon::parse($endDate) : now();

        $routeHistoryQuery = DeliverySchedule::with(['vehicle', 'driver', 'driver.driver.locations', 'deliveryScheduleShops']);
        $this->scopeToCompany($routeHistoryQuery, 'vehicle');

        $schedules = $routeHistoryQuery
            ->whereBetween('delivery_date', [$startDate, $endDate])
            ->when($vehicleId, fn ($q) => $q->where('vehicle_id', $vehicleId))
            ->when($driverId, fn ($q) => $q->where('driver_id', $driverId))
            ->orderBy('delivery_date', 'asc')
            ->get();

        $chartData = [
            'dates' => [],
            'total_distance' => [],
            'total_stops' => [],
            'total_routes' => [],
            'efficiency_score' => []
        ];

        $dailyData = [];

        foreach ($schedules as $schedule) {
            $date = $schedule->delivery_date;

            if (!isset($dailyData[$date])) {
                $dailyData[$date] = [
                    'distance' => 0,
                    'stops' => 0,
                    'routes' => 0,
                    'efficiency_scores' => []
                ];
            }

            // Real distance from the driver's GPS trail for that day (km),
            // same Haversine approach used by the Distance/Overstay reports —
            // replaces the old dependency on a `total_distance` attribute
            // that was never actually set on DeliverySchedule.
            $locations = $schedule->driver?->driver?->locations
                ?->filter(function ($location) use ($schedule) {
                    return Carbon::parse($location->created_at)->isSameDay($schedule->delivery_date);
                })
                ->sortBy('created_at') ?? collect();

            $distanceMeters = 0;
            $previous = null;
            foreach ($locations as $location) {
                if ($previous) {
                    $distanceMeters += $this->calculateDistance(
                        $previous->latitude,
                        $previous->longitude,
                        $location->latitude,
                        $location->longitude
                    );
                }
                $previous = $location;
            }
            $distance = $distanceMeters / 1000;

            $dailyData[$date]['distance'] += $distance;
            $dailyData[$date]['stops'] += $schedule->deliveryScheduleShops->count();
            $dailyData[$date]['routes']++;

            if ($distance > 0 && $schedule->deliveryScheduleShops->count() > 0) {
                $dailyData[$date]['efficiency_scores'][] = $schedule->deliveryScheduleShops->count() / $distance;
            }
        }

        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $formattedDate = $currentDate->format('M d');
            
            $chartData['dates'][] = $formattedDate;
            $chartData['total_distance'][] = $dailyData[$dateStr]['distance'] ?? 0;
            $chartData['total_stops'][] = $dailyData[$dateStr]['stops'] ?? 0;
            $chartData['total_routes'][] = $dailyData[$dateStr]['routes'] ?? 0;
            
            $efficiencyScores = $dailyData[$dateStr]['efficiency_scores'] ?? [];
            if (!empty($efficiencyScores)) {
                $chartData['efficiency_score'][] = round(array_sum($efficiencyScores) / count($efficiencyScores), 2);
            } else {
                $chartData['efficiency_score'][] = 0;
            }

            $currentDate->addDay();
        }

        $nonZeroEfficiencies = array_filter($chartData['efficiency_score'], function($score) {
            return $score > 0;
        });
        $avgEfficiency = !empty($nonZeroEfficiencies) ? 
            round(array_sum($nonZeroEfficiencies) / count($nonZeroEfficiencies), 2) : 0;

        $chartData['summary'] = [
            'total_distance' => array_sum($chartData['total_distance']),
            'total_stops' => array_sum($chartData['total_stops']),
            'total_routes' => array_sum($chartData['total_routes']),
            'avg_efficiency' => $avgEfficiency,
            'most_active_day' => !empty($chartData['total_stops']) ? 
                $chartData['dates'][array_search(max($chartData['total_stops']), $chartData['total_stops'])] : 'N/A'
        ];

        return $chartData;
    }

    private function getRunIdleAnalyticsData($startDate = null, $endDate = null, $vehicleId = null, $driverId = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : now()->subDays(30);
        $endDate = $endDate ? Carbon::parse($endDate) : now();

        $runIdleQuery = DeliverySchedule::with(['driver', 'vehicle', 'deliveryScheduleShops']);
        $this->scopeToCompany($runIdleQuery, 'vehicle');

        $schedules = $runIdleQuery
            ->whereBetween('delivery_date', [$startDate, $endDate])
            ->when($vehicleId, fn ($q) => $q->where('vehicle_id', $vehicleId))
            ->when($driverId, fn ($q) => $q->where('driver_id', $driverId))
            ->orderBy('delivery_date', 'asc')
            ->get();

        $chartData = [
            'dates' => [],
            'run_time_minutes' => [],
            'idle_time_minutes' => [],
            'total_stops' => [],
            'efficiency_ratio' => [],
            'daily_data' => []
        ];

        $dailyData = [];

        foreach ($schedules as $schedule) {
            $date = $schedule->delivery_date;
            
            if (!isset($dailyData[$date])) {
                $dailyData[$date] = [
                    'run_time' => 0,
                    'idle_time' => 0,
                    'stops' => 0,
                    'drivers' => [],
                    'vehicles' => []
                ];
            }

            // Get location data for this schedule
            $locations = $schedule->driver?->driver?->locations
                ?->filter(function ($location) use ($schedule) {
                    return \Carbon\Carbon::parse($location->created_at)->isSameDay($schedule->delivery_date);
                })
                ->sortBy('created_at') ?? collect();

            // Calculate run and idle time
            $runTime = 0;
            $idleTime = 0;
            $stops = 0;
            $previous = null;
            $idleSegmentStart = null;

            foreach ($locations as $location) {
                if ($previous) {
                    $timeDiff = Carbon::parse($previous->created_at)->diffInMinutes($location->created_at);
                    $distance = $this->calculateDistance(
                        $previous->latitude,
                        $previous->longitude,
                        $location->latitude,
                        $location->longitude
                    );

                    if ($distance < 30) {
                        $idleTime += $timeDiff;
                        if (!$idleSegmentStart) {
                            $idleSegmentStart = Carbon::parse($previous->created_at);
                        }
                        if ($idleSegmentStart->diffInMinutes($location->created_at) >= 5) {
                            $stops++;
                            $idleSegmentStart = null;
                        }
                    } else {
                        $runTime += $timeDiff;
                        $idleSegmentStart = null;
                    }
                }
                $previous = $location;
            }

            $dailyData[$date]['run_time'] += $runTime;
            $dailyData[$date]['idle_time'] += $idleTime;
            $dailyData[$date]['stops'] += $stops;
            
            // Track unique drivers and vehicles
            if ($schedule->driver) {
                $dailyData[$date]['drivers'][$schedule->driver->id] = $schedule->driver->name;
            }
            if ($schedule->vehicle) {
                $dailyData[$date]['vehicles'][$schedule->vehicle->id] = $schedule->vehicle->vehicle_number;
            }
        }

        // Fill in missing dates and prepare chart data
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $formattedDate = $currentDate->format('M d');
            
            $chartData['dates'][] = $formattedDate;
            $chartData['run_time_minutes'][] = $dailyData[$dateStr]['run_time'] ?? 0;
            $chartData['idle_time_minutes'][] = $dailyData[$dateStr]['idle_time'] ?? 0;
            $chartData['total_stops'][] = $dailyData[$dateStr]['stops'] ?? 0;
            
            // Calculate efficiency ratio (run_time / total_time)
            $totalTime = ($dailyData[$dateStr]['run_time'] ?? 0) + ($dailyData[$dateStr]['idle_time'] ?? 0);
            $efficiency = $totalTime > 0 ? ($dailyData[$dateStr]['run_time'] / $totalTime) * 100 : 0;
            $chartData['efficiency_ratio'][] = round($efficiency, 1);

            // Store daily data for summary
            $chartData['daily_data'][$dateStr] = [
                'run_time' => $dailyData[$dateStr]['run_time'] ?? 0,
                'idle_time' => $dailyData[$dateStr]['idle_time'] ?? 0,
                'stops' => $dailyData[$dateStr]['stops'] ?? 0,
                'driver_count' => count($dailyData[$dateStr]['drivers'] ?? []),
                'vehicle_count' => count($dailyData[$dateStr]['vehicles'] ?? [])
            ];

            $currentDate->addDay();
        }

        // Calculate summary statistics
        $totalRunTime = array_sum($chartData['run_time_minutes']);
        $totalIdleTime = array_sum($chartData['idle_time_minutes']);
        $totalTime = $totalRunTime + $totalIdleTime;
        $overallEfficiency = $totalTime > 0 ? ($totalRunTime / $totalTime) * 100 : 0;

        // Safe average daily efficiency calculation
        $nonZeroEfficiencies = array_filter($chartData['efficiency_ratio'], function($efficiency) {
            return $efficiency > 0;
        });
        $avgDailyEfficiency = !empty($nonZeroEfficiencies) ? 
            round(array_sum($nonZeroEfficiencies) / count($nonZeroEfficiencies), 1) : 0;

        $chartData['summary'] = [
            'total_run_time' => $this->formatMinutesToTime($totalRunTime),
            'total_idle_time' => $this->formatMinutesToTime($totalIdleTime),
            'total_stops' => array_sum($chartData['total_stops']),
            'overall_efficiency' => round($overallEfficiency, 1),
            'avg_daily_efficiency' => $avgDailyEfficiency,
            'most_efficient_day' => !empty($chartData['efficiency_ratio']) ? 
                $chartData['dates'][array_search(max($chartData['efficiency_ratio']), $chartData['efficiency_ratio'])] : 'N/A',
            'total_drivers' => count(array_unique(array_merge(...array_column($dailyData, 'drivers')))),
            'total_vehicles' => count(array_unique(array_merge(...array_column($dailyData, 'vehicles'))))
        ];

        return $chartData;
    }

    private function get_distance($startDate = null, $endDate = null, $vehicleId = null, $driverId = null)
    {
        // Use provided dates or default to last 30 days
        $startDate = $startDate ? Carbon::parse($startDate) : now()->subDays(30);
        $endDate = $endDate ? Carbon::parse($endDate) : now();

        $distanceQuery = DeliverySchedule::with(['driver', 'vehicle', 'deliveryScheduleShops']);
        $this->scopeToCompany($distanceQuery, 'vehicle');

        $schedules = $distanceQuery
            ->whereBetween('delivery_date', [$startDate, $endDate])
            ->when($vehicleId, fn ($q) => $q->where('vehicle_id', $vehicleId))
            ->when($driverId, fn ($q) => $q->where('driver_id', $driverId))
            ->orderBy('delivery_date', 'asc')
            ->get();

        $chartData = [
            'dates' => [],
            'daily_distance' => [],
            'vehicle_distance' => [],
            'driver_distance' => [],
            'trip_count' => [],
            'avg_speed' => [],
            'summary' => []
        ];

        $dailyData = [];
        $vehicleData = [];
        $driverData = [];

        foreach ($schedules as $schedule) {
            $date = $schedule->delivery_date;
            $vehicleId = $schedule->vehicle_id;
            $driverId = $schedule->driver_id;
            
            // Initialize daily data
            if (!isset($dailyData[$date])) {
                $dailyData[$date] = [
                    'distance' => 0,
                    'trips' => 0,
                    'duration' => 0
                ];
            }

            // Initialize vehicle data
            if ($vehicleId && !isset($vehicleData[$vehicleId])) {
                $vehicleData[$vehicleId] = [
                    'number' => $schedule->vehicle?->vehicle_number ?? 'Unknown',
                    'distance' => 0,
                    'trips' => 0
                ];
            }

            // Initialize driver data
            if ($driverId && !isset($driverData[$driverId])) {
                $driverData[$driverId] = [
                    'name' => $schedule->driver?->name ?? 'Unknown',
                    'distance' => 0,
                    'trips' => 0
                ];
            }

            // Get location data for this schedule
            $locations = $schedule->driver?->driver?->locations
                ?->filter(function ($location) use ($schedule) {
                    return Carbon::parse($location->created_at)->isSameDay($schedule->delivery_date);
                })
                ->sortBy('created_at') ?? collect();

            $totalDistance = 0;
            $tripDuration = 0;

            if ($locations->count() >= 2) {
                $previous = null;
                foreach ($locations as $location) {
                    if ($previous) {
                        $totalDistance += $this->calculateDistance(
                            $previous->latitude,
                            $previous->longitude,
                            $location->latitude,
                            $location->longitude
                        );
                    }
                    $previous = $location;
                }

                // Calculate trip duration in hours
                $startTime = Carbon::parse($locations->first()->created_at);
                $endTime = Carbon::parse($locations->last()->created_at);
                $tripDuration = $startTime->diffInHours($endTime);
            }

            // Convert meters to kilometers
            $distanceKm = $totalDistance / 1000;

            // Update daily data
            $dailyData[$date]['distance'] += $distanceKm;
            $dailyData[$date]['trips']++;
            $dailyData[$date]['duration'] += $tripDuration;

            // Update vehicle data
            if ($vehicleId) {
                $vehicleData[$vehicleId]['distance'] += $distanceKm;
                $vehicleData[$vehicleId]['trips']++;
            }

            // Update driver data
            if ($driverId) {
                $driverData[$driverId]['distance'] += $distanceKm;
                $driverData[$driverId]['trips']++;
            }
        }

        // Prepare daily chart data
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $formattedDate = $currentDate->format('M d');
            
            $chartData['dates'][] = $formattedDate;
            $chartData['daily_distance'][] = $dailyData[$dateStr]['distance'] ?? 0;
            $chartData['trip_count'][] = $dailyData[$dateStr]['trips'] ?? 0;
            
            // Calculate average speed (km/h)
            $distance = $dailyData[$dateStr]['distance'] ?? 0;
            $duration = $dailyData[$dateStr]['duration'] ?? 0;
            $avgSpeed = $duration > 0 ? round($distance / $duration, 1) : 0;
            $chartData['avg_speed'][] = $avgSpeed;

            $currentDate->addDay();
        }

        // Prepare vehicle data for charts (top 10 vehicles by distance)
        usort($vehicleData, function($a, $b) {
            return $b['distance'] <=> $a['distance'];
        });
        $topVehicles = array_slice($vehicleData, 0, 10);

        $chartData['vehicle_distance'] = [
            'labels' => array_column($topVehicles, 'number'),
            'data' => array_column($topVehicles, 'distance')
        ];

        // Prepare driver data for charts (top 10 drivers by distance)
        usort($driverData, function($a, $b) {
            return $b['distance'] <=> $a['distance'];
        });
        $topDrivers = array_slice($driverData, 0, 10);

        $chartData['driver_distance'] = [
            'labels' => array_column($topDrivers, 'name'),
            'data' => array_column($topDrivers, 'distance')
        ];

        // Calculate summary statistics
        $totalDistance = array_sum($chartData['daily_distance']);
        $totalTrips = array_sum($chartData['trip_count']);
        $avgDailyDistance = count($chartData['daily_distance']) > 0 ? 
            round($totalDistance / count($chartData['daily_distance']), 2) : 0;

        $nonZeroSpeeds = array_filter($chartData['avg_speed']);
        $avgSpeed = !empty($nonZeroSpeeds) ? 
            round(array_sum($nonZeroSpeeds) / count($nonZeroSpeeds), 1) : 0;

        $chartData['summary'] = [
            'total_distance' => round($totalDistance, 2),
            'total_trips' => $totalTrips,
            'avg_daily_distance' => $avgDailyDistance,
            'avg_speed' => $avgSpeed,
            'top_vehicle' => !empty($topVehicles) ? $topVehicles[0]['number'] : 'N/A',
            'top_driver' => !empty($topDrivers) ? $topDrivers[0]['name'] : 'N/A',
            'vehicle_count' => count($vehicleData),
            'driver_count' => count($driverData),
            'most_active_day' => !empty($chartData['daily_distance']) ? 
                $chartData['dates'][array_search(max($chartData['daily_distance']), $chartData['daily_distance'])] : 'N/A'
        ];

        return $chartData;
    }

    private function getGeofenceAnalyticsData($startDate = null, $endDate = null, $vehicleId = null, $driverId = null)
    {
        // Use provided dates or default to last 30 days
        $startDate = $startDate ? Carbon::parse($startDate) : now()->subDays(30);
        $endDate = $endDate ? Carbon::parse($endDate) : now();

        // Real geofence zones = shop locations (the only geo-fenced "zones" this
        // app tracks). A vehicle is considered to have "entered" a zone whenever
        // its GPS trail comes within $geofenceRadiusMeters of a shop, and to have
        // "exited" once it moves back out — mirroring how the Overstay report
        // already derives idle periods from the same location trail.
        $geofenceRadiusMeters = 150;

        $zonesQuery = Shop::whereNotNull('shop_latitude')
            ->whereNotNull('shop_longitude')
            ->where('shop_latitude', '!=', '')
            ->where('shop_longitude', '!=', '');
        if ($companyId = auth()->user()->companyId()) {
            $zonesQuery->where('company_id', $companyId);
        }
        $zones = $zonesQuery->get(['id', 'shop_name', 'shop_latitude', 'shop_longitude']);

        $geofenceSchedulesQuery = DeliverySchedule::with(['driver', 'vehicle', 'driver.driver.locations']);
        $this->scopeToCompany($geofenceSchedulesQuery, 'vehicle');

        $schedules = $geofenceSchedulesQuery
            ->whereBetween('delivery_date', [$startDate, $endDate])
            ->when($vehicleId, fn ($q) => $q->where('vehicle_id', $vehicleId))
            ->when($driverId, fn ($q) => $q->where('driver_id', $driverId))
            ->orderBy('delivery_date', 'asc')
            ->get();

        $geofenceEntries = [];

        if ($zones->isNotEmpty()) {
            foreach ($schedules as $schedule) {
                $locations = $schedule->driver?->driver?->locations
                    ?->filter(function ($location) use ($schedule) {
                        return Carbon::parse($location->created_at)->isSameDay($schedule->delivery_date);
                    })
                    ->sortBy('created_at') ?? collect();

                if ($locations->count() < 2) {
                    continue;
                }

                $vehicleNumber = $schedule->vehicle?->vehicle_number ?? 'Unknown';

                foreach ($zones as $zone) {
                    $inside = false;
                    $entryTime = null;

                    foreach ($locations as $location) {
                        $distance = $this->calculateDistance(
                            $location->latitude,
                            $location->longitude,
                            $zone->shop_latitude,
                            $zone->shop_longitude
                        );

                        $locationTime = Carbon::parse($location->created_at);

                        if ($distance <= $geofenceRadiusMeters) {
                            if (!$inside) {
                                $inside = true;
                                $entryTime = $locationTime;
                            }
                        } elseif ($inside) {
                            $geofenceEntries[] = [
                                'vehicle' => $vehicleNumber,
                                'zone' => $zone->shop_name ?: 'Unnamed Shop',
                                'date' => Carbon::parse($schedule->delivery_date),
                                'duration_minutes' => max(0, $entryTime->diffInMinutes($locationTime)),
                                'entry_hour' => $entryTime->hour,
                            ];
                            $inside = false;
                            $entryTime = null;
                        }
                    }

                    // Still inside the zone at the end of the trail
                    if ($inside && $entryTime) {
                        $lastLocationTime = Carbon::parse($locations->last()->created_at);
                        $geofenceEntries[] = [
                            'vehicle' => $vehicleNumber,
                            'zone' => $zone->shop_name ?: 'Unnamed Shop',
                            'date' => Carbon::parse($schedule->delivery_date),
                            'duration_minutes' => max(0, $entryTime->diffInMinutes($lastLocationTime)),
                            'entry_hour' => $entryTime->hour,
                        ];
                    }
                }
            }
        }

        $chartData = [
            'dates' => [],
            'daily_entries' => [],
            'zone_activity' => [],
            'vehicle_activity' => [],
            'avg_duration' => [],
            'peak_hours' => [],
            'summary' => []
        ];

        $dailyData = [];
        $zoneData = [];
        $vehicleData = [];
        $hourlyData = array_fill(0, 24, 0);

        foreach ($geofenceEntries as $entry) {
            $date = $entry['date']->format('Y-m-d');
            $zone = $entry['zone'];
            $vehicle = $entry['vehicle'];
            $duration = $entry['duration_minutes'];

            // Initialize daily data
            if (!isset($dailyData[$date])) {
                $dailyData[$date] = [
                    'entries' => 0,
                    'total_duration' => 0,
                    'zones' => [],
                    'vehicles' => []
                ];
            }

            // Initialize zone data
            if (!isset($zoneData[$zone])) {
                $zoneData[$zone] = [
                    'entries' => 0,
                    'total_duration' => 0,
                    'vehicles' => []
                ];
            }

            // Initialize vehicle data
            if (!isset($vehicleData[$vehicle])) {
                $vehicleData[$vehicle] = [
                    'entries' => 0,
                    'total_duration' => 0,
                    'zones' => []
                ];
            }

            // Update daily data
            $dailyData[$date]['entries']++;
            $dailyData[$date]['total_duration'] += $duration;
            $dailyData[$date]['zones'][$zone] = true;
            $dailyData[$date]['vehicles'][$vehicle] = true;

            // Update zone data
            $zoneData[$zone]['entries']++;
            $zoneData[$zone]['total_duration'] += $duration;
            $zoneData[$zone]['vehicles'][$vehicle] = true;

            // Update vehicle data
            $vehicleData[$vehicle]['entries']++;
            $vehicleData[$vehicle]['total_duration'] += $duration;
            $vehicleData[$vehicle]['zones'][$zone] = true;

            // Update hourly data for peak hours
            $hourlyData[$entry['entry_hour']]++;
        }

        // Prepare daily chart data
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $formattedDate = $currentDate->format('M d');

            $chartData['dates'][] = $formattedDate;
            $chartData['daily_entries'][] = $dailyData[$dateStr]['entries'] ?? 0;

            // Calculate average duration for the day
            $entries = $dailyData[$dateStr]['entries'] ?? 0;
            $totalDuration = $dailyData[$dateStr]['total_duration'] ?? 0;
            $avgDuration = $entries > 0 ? round($totalDuration / $entries, 1) : 0;
            $chartData['avg_duration'][] = $avgDuration;

            $currentDate->addDay();
        }

        // Prepare zone activity data (top 6 zones) — uasort() to keep zone names as keys
        uasort($zoneData, function($a, $b) {
            return $b['entries'] <=> $a['entries'];
        });
        $topZones = array_slice($zoneData, 0, 6, true);

        $chartData['zone_activity'] = [
            'labels' => array_keys($topZones),
            'entries' => array_values(array_column($topZones, 'entries')),
            'avg_duration' => array_values(array_map(function($zone) {
                return $zone['entries'] > 0 ? round($zone['total_duration'] / $zone['entries'], 1) : 0;
            }, $topZones))
        ];

        // Prepare vehicle activity data (top 6 vehicles) — uasort() to keep vehicle numbers as keys
        uasort($vehicleData, function($a, $b) {
            return $b['entries'] <=> $a['entries'];
        });
        $topVehicles = array_slice($vehicleData, 0, 6, true);

        $chartData['vehicle_activity'] = [
            'labels' => array_keys($topVehicles),
            'entries' => array_values(array_column($topVehicles, 'entries'))
        ];

        // Prepare peak hours data
        $chartData['peak_hours'] = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $chartData['peak_hours'][] = [
                'hour' => sprintf('%02d:00', $hour), // Format as 00:00, 01:00, etc.
                'count' => $hourlyData[$hour]
            ];
        }

        // Calculate summary statistics
        $totalEntries = array_sum($chartData['daily_entries']);
        $totalDuration = array_sum(array_map(function($day) {
            return $day['total_duration'] ?? 0;
        }, $dailyData));
        $avgDurationOverall = $totalEntries > 0 ? round($totalDuration / $totalEntries, 1) : 0;

        // Find peak hour
        $peakHourIndex = array_search(max($hourlyData), $hourlyData);
        $peakHour = sprintf('%02d:00 - %02d:00', $peakHourIndex, ($peakHourIndex + 1) % 24);

        $chartData['summary'] = [
            'total_entries' => $totalEntries,
            'unique_zones' => count($zoneData),
            'unique_vehicles' => count($vehicleData),
            'avg_duration' => $avgDurationOverall,
            'most_active_zone' => !empty($zoneData) ? array_key_first($zoneData) : 'N/A',
            'most_active_vehicle' => !empty($vehicleData) ? array_key_first($vehicleData) : 'N/A',
            'peak_hour' => $totalEntries > 0 ? $peakHour : 'N/A',
            'busiest_day' => !empty($chartData['daily_entries']) && max($chartData['daily_entries']) > 0 ?
                $chartData['dates'][array_search(max($chartData['daily_entries']), $chartData['daily_entries'])] : 'N/A'
        ];

        return $chartData;
    }

    // In your DashboardController.php
    private function getOverstayAnalyticsData($startDate = null, $endDate = null, $vehicleId = null, $driverId = null)
    {
        // Use provided dates or default to last 30 days
        $startDate = $startDate ? Carbon::parse($startDate) : now()->subDays(30);
        $endDate = $endDate ? Carbon::parse($endDate) : now();

        // Get schedules with relationships
        $overstayQuery = DeliverySchedule::with(['driver', 'vehicle', 'driver.driver.locations']);
        $this->scopeToCompany($overstayQuery, 'vehicle');

        $schedules = $overstayQuery
            ->whereBetween('delivery_date', [$startDate, $endDate])
            ->when($vehicleId, fn ($q) => $q->where('vehicle_id', $vehicleId))
            ->when($driverId, fn ($q) => $q->where('driver_id', $driverId))
            ->orderBy('delivery_date', 'asc')
            ->get();

        $chartData = [
            'dates' => [],
            'daily_overstays' => [],
            'daily_duration' => [],
            'vehicle_overstays' => [],
            'driver_overstays' => [],
            'location_overstays' => [],
            'duration_trend' => [],
            'summary' => []
        ];

        $dailyData = [];
        $vehicleData = [];
        $driverData = [];
        $locationData = [];
        $hourlyData = array_fill(0, 24, 0);

        foreach ($schedules as $schedule) {
            $locations = $schedule->driver?->driver?->locations
                ?->filter(function ($location) use ($schedule) {
                    return Carbon::parse($location->created_at)->isSameDay($schedule->delivery_date);
                })
                ->sortBy('created_at') ?? collect();

            if ($locations->count() < 2) continue;
            // dd($schedule);

            $date = Carbon::parse($schedule->delivery_date)->format('Y-m-d');
            $vehicle = $schedule->vehicle?->vehicle_number ?? 'Unknown';
            $driver = $schedule->driver?->name ?? 'Unknown';

            $previous = null;
            $idleStart = null;
            $overstayEntries = [];

            foreach ($locations as $location) {
                if ($previous) {
                    $distance = $this->calculateDistance(
                        $previous->latitude,
                        $previous->longitude,
                        $location->latitude,
                        $location->longitude
                    );

                    // If vehicle is staying at same spot (less than 30 meters)
                    if ($distance < 30) {
                        if (!$idleStart) {
                            $idleStart = $previous->created_at;
                        }
                    } else {
                        // Vehicle moved — so record overstay if duration was significant
                        if ($idleStart) {
                            $idleEnd = $previous->created_at;
                            $durationMinutes = Carbon::parse($idleStart)->diffInMinutes($idleEnd);

                            // Log only if stay lasted more than 10 minutes (to ignore GPS noise)
                            if ($durationMinutes > 10) {
                                $locationKey = '(' . number_format($previous->latitude, 5) . ', ' . number_format($previous->longitude, 5) . ')';
                                
                                $overstayEntries[] = [
                                    'vehicle' => $vehicle,
                                    'driver' => $driver,
                                    'date' => $schedule->delivery_date,
                                    'location' => $locationKey,
                                    'start_time' => $idleStart,
                                    'end_time' => $idleEnd,
                                    'duration_minutes' => $durationMinutes,
                                    'start_hour' => Carbon::parse($idleStart)->hour
                                ];
                            }

                            $idleStart = null;
                        }
                    }
                }

                $previous = $location;
            }

            // If still idle at end of day
            if ($idleStart && $previous) {
                $idleEnd = $previous->created_at;
                $durationMinutes = Carbon::parse($idleStart)->diffInMinutes($idleEnd);
                if ($durationMinutes > 10) {
                    $locationKey = '(' . number_format($previous->latitude, 5) . ', ' . number_format($previous->longitude, 5) . ')';
                    
                    $overstayEntries[] = [
                        'vehicle' => $vehicle,
                        'driver' => $driver,
                        'date' => $schedule->delivery_date,
                        'location' => $locationKey,
                        'start_time' => $idleStart,
                        'end_time' => $idleEnd,
                        'duration_minutes' => $durationMinutes,
                        'start_hour' => Carbon::parse($idleStart)->hour
                    ];
                }
            }

            // Process the collected overstay entries for this schedule
            foreach ($overstayEntries as $entry) {
                $date = Carbon::parse($entry['date'])->format('Y-m-d');
                $vehicle = $entry['vehicle'];
                $driver = $entry['driver'];
                $location = $entry['location'];
                $duration = $entry['duration_minutes'];
                $startHour = $entry['start_hour'];

                // Update hourly data for peak hours analysis
                $hourlyData[$startHour] += $duration;

                // Initialize daily data with proper default values
                if (!isset($dailyData[$date])) {
                    $dailyData[$date] = [
                        'count' => 0,
                        'total_duration' => 0,
                        'vehicles' => [],
                        'drivers' => [],
                        'locations' => []
                    ];
                }

                // Initialize vehicle data
                if (!isset($vehicleData[$vehicle])) {
                    $vehicleData[$vehicle] = [
                        'count' => 0,
                        'total_duration' => 0,
                        'avg_duration' => 0
                    ];
                }

                // Initialize driver data
                if (!isset($driverData[$driver])) {
                    $driverData[$driver] = [
                        'count' => 0,
                        'total_duration' => 0,
                        'avg_duration' => 0
                    ];
                }

                // Initialize location data
                if (!isset($locationData[$location])) {
                    $locationData[$location] = [
                        'count' => 0,
                        'total_duration' => 0,
                        'avg_duration' => 0
                    ];
                }

                // Update daily data
                $dailyData[$date]['count']++;
                $dailyData[$date]['total_duration'] += $duration;
                $dailyData[$date]['vehicles'][$vehicle] = true;
                $dailyData[$date]['drivers'][$driver] = true;
                $dailyData[$date]['locations'][$location] = true;

                // Update vehicle data
                $vehicleData[$vehicle]['count']++;
                $vehicleData[$vehicle]['total_duration'] += $duration;

                // Update driver data
                $driverData[$driver]['count']++;
                $driverData[$driver]['total_duration'] += $duration;

                // Update location data
                $locationData[$location]['count']++;
                $locationData[$location]['total_duration'] += $duration;
            }
        }

        // Calculate averages for vehicles, drivers, and locations
        foreach ($vehicleData as $vehicle => $data) {
            $vehicleData[$vehicle]['avg_duration'] = $data['count'] > 0 ? 
                round($data['total_duration'] / $data['count'], 1) : 0;
        }
        
        foreach ($driverData as $driver => $data) {
            $driverData[$driver]['avg_duration'] = $data['count'] > 0 ? 
                round($data['total_duration'] / $data['count'], 1) : 0;
        }
        
        foreach ($locationData as $location => $data) {
            $locationData[$location]['avg_duration'] = $data['count'] > 0 ? 
                round($data['total_duration'] / $data['count'], 1) : 0;
        }

        // Prepare daily chart data
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $formattedDate = $currentDate->format('M d');
            
            $chartData['dates'][] = $formattedDate;
            
            // Safely access daily data with null coalescing
            $dailyCount = $dailyData[$dateStr]['count'] ?? 0;
            $dailyTotalDuration = $dailyData[$dateStr]['total_duration'] ?? 0;
            
            $chartData['daily_overstays'][] = $dailyCount;
            $chartData['daily_duration'][] = $dailyTotalDuration;
            
            // Safe duration trend calculation
            $chartData['duration_trend'][] = $dailyCount > 0 ? 
                round($dailyTotalDuration / $dailyCount, 1) : 0;

            $currentDate->addDay();
        }

        // Prepare vehicle overstays data (top 6 vehicles)
        usort($vehicleData, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });
        $topVehicles = array_slice($vehicleData, 0, 6);

        $chartData['vehicle_overstays'] = [
            'labels' => array_keys($topVehicles),
            'count' => array_column($topVehicles, 'count'),
            'avg_duration' => array_column($topVehicles, 'avg_duration')
        ];

        // Prepare driver overstays data (top 6 drivers)
        usort($driverData, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });
        $topDrivers = array_slice($driverData, 0, 6);

        $chartData['driver_overstays'] = [
            'labels' => array_keys($topDrivers),
            'count' => array_column($topDrivers, 'count'),
            'avg_duration' => array_column($topDrivers, 'avg_duration')
        ];

        // Prepare location overstays data (top 6 locations)
        usort($locationData, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });
        $topLocations = array_slice($locationData, 0, 6);

        $chartData['location_overstays'] = [
            'labels' => array_keys($topLocations),
            'count' => array_column($topLocations, 'count'),
            'avg_duration' => array_column($topLocations, 'avg_duration')
        ];

        // Prepare peak hours data
        $chartData['peak_hours'] = array_map(function($duration, $hour) {
            return [
                'hour' => sprintf('%02d:00', $hour),
                'duration' => $duration
            ];
        }, $hourlyData, array_keys($hourlyData));

        // Calculate summary statistics
        $totalOverstays = array_sum($chartData['daily_overstays']);
        $totalDuration = array_sum($chartData['daily_duration']);
        $avgDuration = $totalOverstays > 0 ? round($totalDuration / $totalOverstays, 1) : 0;

        // Find peak hour
        $peakHourIndex = array_search(max($hourlyData), $hourlyData);
        $peakHour = sprintf('%02d:00 - %02d:00', $peakHourIndex, ($peakHourIndex + 1) % 24);

        // Safe array search for busiest day
        $busiestDayIndex = !empty($chartData['daily_overstays']) ? 
            array_search(max($chartData['daily_overstays']), $chartData['daily_overstays']) : false;
        $busiestDay = $busiestDayIndex !== false ? $chartData['dates'][$busiestDayIndex] : 'N/A';

        // Get most problematic vehicle, driver, and location
        $mostProblematicVehicle = !empty($vehicleData) ? 
            array_key_first($vehicleData) : 'N/A';
        $mostProblematicDriver = !empty($driverData) ? 
            array_key_first($driverData) : 'N/A';
        $mostCommonLocation = !empty($locationData) ? 
            array_key_first($locationData) : 'N/A';

        $chartData['summary'] = [
            'total_overstays' => $totalOverstays,
            'total_duration' => $this->formatMinutesToTime($totalDuration),
            'avg_duration' => $avgDuration,
            'most_problematic_vehicle' => $mostProblematicVehicle,
            'most_problematic_driver' => $mostProblematicDriver,
            'most_common_location' => $mostCommonLocation,
            'peak_overstay_hour' => $peakHour,
            'problematic_day' => $busiestDay
        ];

        return $chartData;
    }

    private function getAttendanceAnalyticsData($month = null, $year = null, $startDate = null, $driverId = null)
    {
        // Use provided month/year, or derive from the global date filter, or default to current month
        if (!$month || !$year) {
            $reference = $startDate ? Carbon::parse($startDate) : now();
            $month = $month ?: $reference->month;
            $year = $year ?: $reference->year;
        }

        // Get attendance data grouped by driver and month
        $attendanceQuery = LoginLog::selectRaw('user_id, MONTHNAME(created_at) as month, COUNT(DISTINCT DATE(created_at)) as present_days')
            ->with('driver');
        $this->scopeToCompany($attendanceQuery, 'driver.driver');

        $attendanceData = $attendanceQuery
            ->whereYear('created_at', $year)
            ->when($driverId, fn ($q) => $q->where('user_id', $driverId))
            ->groupBy('user_id', 'month')
            ->get()
            ->map(function ($row) {
                $totalDays = now()->daysInMonth; // Get actual days in month
                return [
                    'driver' => $row->driver->name ?? 'Unknown',
                    'month' => $row->month,
                    'present_days' => $row->present_days,
                    'absent_days' => $totalDays - $row->present_days,
                    'attendance_rate' => round(($row->present_days / $totalDays) * 100, 1)
                ];
            });

        $chartData = [
            'drivers' => [],
            'present_days' => [],
            'absent_days' => [],
            'attendance_rate' => [],
            'monthly_trend' => [],
            'summary' => []
        ];

        $driverData = [];
        $monthlyData = [];

        foreach ($attendanceData as $entry) {
            $driver = $entry['driver'];
            $month = $entry['month'];
            
            // Initialize driver data
            if (!isset($driverData[$driver])) {
                $driverData[$driver] = [
                    'present_days' => 0,
                    'absent_days' => 0,
                    'attendance_rate' => 0,
                    'months' => []
                ];
            }

            // Initialize monthly data
            if (!isset($monthlyData[$month])) {
                $monthlyData[$month] = [
                    'total_present' => 0,
                    'total_absent' => 0,
                    'driver_count' => 0
                ];
            }

            // Update driver data
            $driverData[$driver]['present_days'] += $entry['present_days'];
            $driverData[$driver]['absent_days'] += $entry['absent_days'];
            $driverData[$driver]['months'][] = $month;

            // Update monthly data
            $monthlyData[$month]['total_present'] += $entry['present_days'];
            $monthlyData[$month]['total_absent'] += $entry['absent_days'];
            $monthlyData[$month]['driver_count']++;
        }

        // Calculate attendance rate for each driver
        foreach ($driverData as $driver => $data) {
            $totalDays = $data['present_days'] + $data['absent_days'];
            $driverData[$driver]['attendance_rate'] = $totalDays > 0 ? 
                round(($data['present_days'] / $totalDays) * 100, 1) : 0;
        }

        // Sort drivers by present days (descending)
        uasort($driverData, function($a, $b) {
            return $b['present_days'] <=> $a['present_days'];
        });

        // Prepare driver chart data (top 15 drivers)
        $topDrivers = array_slice($driverData, 0, 15);

        foreach ($topDrivers as $driver => $data) {
            $chartData['drivers'][] = $driver;
            $chartData['present_days'][] = $data['present_days'];
            $chartData['absent_days'][] = $data['absent_days'];
            $chartData['attendance_rate'][] = $data['attendance_rate'];
        }

        // Prepare monthly trend data
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 
                'July', 'August', 'September', 'October', 'November', 'December'];
        
        foreach ($months as $month) {
            if (isset($monthlyData[$month])) {
                $data = $monthlyData[$month];
                $chartData['monthly_trend'][] = $data['driver_count'] > 0 ? 
                    round($data['total_present'] / $data['driver_count'], 1) : 0;
            } else {
                $chartData['monthly_trend'][] = 0;
            }
        }

        // Calculate summary statistics
        $totalPresent = array_sum($chartData['present_days']);
        $totalAbsent = array_sum($chartData['absent_days']);
        $totalDrivers = count($driverData);
        $avgAttendanceRate = $totalDrivers > 0 ? 
            round(array_sum($chartData['attendance_rate']) / $totalDrivers, 1) : 0;

        // Find best and worst performing drivers
        $bestDriver = null;
        $worstDriver = null;
        $bestRate = 0;
        $worstRate = 100;

        foreach ($driverData as $driver => $data) {
            if ($data['attendance_rate'] > $bestRate) {
                $bestRate = $data['attendance_rate'];
                $bestDriver = $driver;
            }
            if ($data['attendance_rate'] < $worstRate && $data['attendance_rate'] > 0) {
                $worstRate = $data['attendance_rate'];
                $worstDriver = $driver;
            }
        }

        $chartData['summary'] = [
            'total_drivers' => $totalDrivers,
            'total_present_days' => $totalPresent,
            'total_absent_days' => $totalAbsent,
            'avg_attendance_rate' => $avgAttendanceRate,
            'best_driver' => $bestDriver ?: 'N/A',
            'best_attendance_rate' => $bestRate,
            'worst_driver' => $worstDriver ?: 'N/A',
            'worst_attendance_rate' => $worstRate,
            'most_consistent_month' => !empty($monthlyData) ? 
                array_search(max(array_column($monthlyData, 'total_present')), array_column($monthlyData, 'total_present')) : 'N/A'
        ];

        return $chartData;
    }

    // In your DashboardController.php
    private function getLoginLogoutAnalyticsData($startDate = null, $endDate = null, $driverId = null)
    {
        // Use provided dates or default to last 30 days
        $startDate = $startDate ? Carbon::parse($startDate) : now()->subDays(30);
        $endDate = $endDate ? Carbon::parse($endDate) : now();

        // Get login/logout data
        $loginLogoutQuery = LoginLog::with('driver');
        $this->scopeToCompany($loginLogoutQuery, 'driver.driver');

        $loginLogs = $loginLogoutQuery
            ->whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->when($driverId, fn ($q) => $q->where('user_id', $driverId))
            ->orderBy('created_at', 'desc')
            ->get();

        $chartData = [
            'dates' => [],
            'daily_logins' => [],
            'daily_logouts' => [],
            'active_sessions' => [],
            'avg_session_duration' => [],
            'driver_activity' => [],
            'login_times' => [],
            'device_usage' => [],
            'summary' => []
        ];

        $dailyData = [];
        $driverData = [];
        $hourlyData = array_fill(0, 24, 0);
        $deviceData = [];
        $sessionDurations = [];

        // Group data by date and driver
        $groupedData = $loginLogs->groupBy(function($log) {
            return Carbon::parse($log->created_at)->format('Y-m-d');
        });

        foreach ($groupedData as $date => $dayLogs) {
            $dailyData[$date] = [
                'logins' => 0,
                'logouts' => 0,
                'active_sessions' => 0,
                'session_durations' => [],
                'drivers' => []
            ];

            // Group by driver for the day
            $driverDayLogs = $dayLogs->groupBy('user_id');
            
            foreach ($driverDayLogs as $driverId => $driverLogs) {
                $driverName = $driverLogs->first()->driver->name ?? 'Unknown';
                
                // Initialize driver data
                if (!isset($driverData[$driverName])) {
                    $driverData[$driverName] = [
                        'total_logins' => 0,
                        'total_logouts' => 0,
                        'total_session_time' => 0,
                        'sessions_count' => 0
                    ];
                }

                // Sort logs by time
                $sortedLogs = $driverLogs->sortBy('created_at');
                $loginTime = null;
                $sessionCount = 0;

                foreach ($sortedLogs as $log) {
                    if ($log->status === 'login') {
                        $loginTime = Carbon::parse($log->created_at);
                        $dailyData[$date]['logins']++;
                        $driverData[$driverName]['total_logins']++;
                        
                        // Track login hour
                        $loginHour = $loginTime->hour;
                        $hourlyData[$loginHour]++;
                        
                        // Track device usage
                        $device = $log->device ?? 'Unknown';
                        if (!isset($deviceData[$device])) {
                            $deviceData[$device] = 0;
                        }
                        $deviceData[$device]++;

                    } elseif ($log->status === 'logout' && $loginTime) {
                        $logoutTime = Carbon::parse($log->created_at);
                        $sessionDuration = $loginTime->diffInMinutes($logoutTime);
                        
                        $dailyData[$date]['logouts']++;
                        $dailyData[$date]['session_durations'][] = $sessionDuration;
                        $driverData[$driverName]['total_logouts']++;
                        $driverData[$driverName]['total_session_time'] += $sessionDuration;
                        $driverData[$driverName]['sessions_count']++;
                        
                        $sessionDurations[] = $sessionDuration;
                        $sessionCount++;
                        
                        $loginTime = null; // Reset for next session
                    }
                }

                // If session started but never logged out, count as active
                if ($loginTime) {
                    $dailyData[$date]['active_sessions']++;
                }
            }
        }

        // Prepare daily chart data
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $formattedDate = $currentDate->format('M d');
            
            $chartData['dates'][] = $formattedDate;
            $chartData['daily_logins'][] = $dailyData[$dateStr]['logins'] ?? 0;
            $chartData['daily_logouts'][] = $dailyData[$dateStr]['logouts'] ?? 0;
            $chartData['active_sessions'][] = $dailyData[$dateStr]['active_sessions'] ?? 0;
            
            // Calculate average session duration for the day
            $durations = $dailyData[$dateStr]['session_durations'] ?? [];
            $avgDuration = !empty($durations) ? round(array_sum($durations) / count($durations), 1) : 0;
            $chartData['avg_session_duration'][] = $avgDuration;

            $currentDate->addDay();
        }

        // Prepare driver activity data (top 10 drivers)
        uasort($driverData, function($a, $b) {
            return $b['total_logins'] <=> $a['total_logins'];
        });
        $topDrivers = array_slice($driverData, 0, 10);

        $chartData['driver_activity'] = [
            'labels' => array_keys($topDrivers),
            'logins' => array_column($topDrivers, 'total_logins'),
            'avg_session_duration' => array_map(function($driver) {
                return $driver['sessions_count'] > 0 ? 
                    round($driver['total_session_time'] / $driver['sessions_count'], 1) : 0;
            }, $topDrivers)
        ];

        // Prepare login times data (24-hour distribution)
        $chartData['login_times'] = array_map(function($count, $hour) {
            return [
                'hour' => sprintf('%02d:00', $hour),
                'count' => $count
            ];
        }, $hourlyData, array_keys($hourlyData));

        // Prepare device usage data
        $chartData['device_usage'] = [
            'labels' => array_keys($deviceData),
            'data' => array_values($deviceData)
        ];

        // Calculate summary statistics
        $totalLogins = array_sum($chartData['daily_logins']);
        $totalLogouts = array_sum($chartData['daily_logouts']);
        $totalSessions = count($sessionDurations);
        $avgSessionDuration = $totalSessions > 0 ? round(array_sum($sessionDurations) / $totalSessions, 1) : 0;

        // Find most active driver
        $mostActiveDriver = !empty($driverData) ? 
            array_key_first($driverData) : 'N/A';

        // Find peak login hour
        $peakHourIndex = array_search(max($hourlyData), $hourlyData);
        $peakHour = sprintf('%02d:00 - %02d:00', $peakHourIndex, ($peakHourIndex + 1) % 24);

        // Find most used device
        $mostUsedDevice = !empty($deviceData) ? 
            array_search(max($deviceData), $deviceData) : 'N/A';

        $chartData['summary'] = [
            'total_logins' => $totalLogins,
            'total_logouts' => $totalLogouts,
            'total_sessions' => $totalSessions,
            'avg_session_duration' => $this->formatMinutesToTime($avgSessionDuration),
            'most_active_driver' => $mostActiveDriver,
            'peak_login_hour' => $peakHour,
            'most_used_device' => $mostUsedDevice,
            'busiest_day' => !empty($chartData['daily_logins']) ? 
                $chartData['dates'][array_search(max($chartData['daily_logins']), $chartData['daily_logins'])] : 'N/A'
        ];

        return $chartData;
    }


    // In your DashboardController.php
    private function getLoginTimeAnalyticsData($startDate = null, $endDate = null, $driverId = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : now()->subDays(30);
        $endDate = $endDate ? Carbon::parse($endDate) : now();

        // Get login time data
        $loginTimeQuery = LoginLog::with('driver')
            ->where('status', 'login');
        $this->scopeToCompany($loginTimeQuery, 'driver.driver');

        $loginLogs = $loginTimeQuery
            ->whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->when($driverId, fn ($q) => $q->where('user_id', $driverId))
            ->latest()
            ->get();

        $chartData = [
            'dates' => [],
            'daily_logins' => [],
            'login_trend' => [],
            'hourly_distribution' => [],
            'driver_early_birds' => [],
            'driver_night_owls' => [],
            'login_consistency' => [],
            'summary' => []
        ];

        $dailyData = [];
        $hourlyData = array_fill(0, 24, 0);
        $driverData = [];
        $driverLoginTimes = [];

        foreach ($loginLogs as $log) {
            $date = $log->created_at->format('Y-m-d');
            $driver = $log->driver->name ?? 'Unknown';
            $hour = $log->created_at->hour;
            $minute = $log->created_at->minute;
            $timeInMinutes = ($hour * 60) + $minute; // Convert to minutes for analysis

            // Initialize daily data
            if (!isset($dailyData[$date])) {
                $dailyData[$date] = [
                    'count' => 0,
                    'login_times' => []
                ];
            }

            // Initialize driver data
            if (!isset($driverData[$driver])) {
                $driverData[$driver] = [
                    'total_logins' => 0,
                    'login_times' => [],
                    'avg_login_time' => 0,
                    'consistency_score' => 0
                ];
            }

            // Update daily data
            $dailyData[$date]['count']++;
            $dailyData[$date]['login_times'][] = $timeInMinutes;

            // Update hourly data
            $hourlyData[$hour]++;

            // Update driver data
            $driverData[$driver]['total_logins']++;
            $driverData[$driver]['login_times'][] = $timeInMinutes;

            // Store individual login times for candlestick chart
            if (!isset($driverLoginTimes[$driver])) {
                $driverLoginTimes[$driver] = [];
            }
            $driverLoginTimes[$driver][] = $timeInMinutes;
        }

        // Calculate driver statistics
        foreach ($driverData as $driver => $data) {
            $loginTimes = $data['login_times'];
            
            // Calculate average login time
            $driverData[$driver]['avg_login_time'] = !empty($loginTimes) ? 
                round(array_sum($loginTimes) / count($loginTimes)) : 0;
            
            // Calculate consistency score (lower standard deviation = more consistent)
            $stdDev = $this->calculateStandardDeviation($loginTimes);
            $driverData[$driver]['consistency_score'] = $stdDev > 0 ? 
                round(100 - min($stdDev / 60, 100)) : 100; // Convert to percentage
        }

        // Prepare daily chart data
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $formattedDate = $currentDate->format('M d');
            
            $chartData['dates'][] = $formattedDate;
            $chartData['daily_logins'][] = $dailyData[$dateStr]['count'] ?? 0;
            
            // Calculate average login time for the day (convert back to hours for trend)
            $loginTimes = $dailyData[$dateStr]['login_times'] ?? [];
            $avgLoginTime = !empty($loginTimes) ? round(array_sum($loginTimes) / count($loginTimes) / 60, 2) : 0;
            $chartData['login_trend'][] = $avgLoginTime;

            $currentDate->addDay();
        }

        // Prepare hourly distribution data
        $chartData['hourly_distribution'] = array_map(function($count, $hour) {
            return [
                'hour' => sprintf('%02d:00', $hour),
                'count' => $count
            ];
        }, $hourlyData, array_keys($hourlyData));

        // Prepare early birds (drivers who login before 8 AM on average)
        $earlyBirds = array_filter($driverData, function($data) {
            return $data['avg_login_time'] < (8 * 60); // Before 8 AM
        });
        uasort($earlyBirds, function($a, $b) {
            return $a['avg_login_time'] <=> $b['avg_login_time'];
        });
        $topEarlyBirds = array_slice($earlyBirds, 0, 8);

        $chartData['driver_early_birds'] = [
            'labels' => array_keys($topEarlyBirds),
            'avg_times' => array_map(function($time) {
                return $this->formatMinutesToTime($time);
            }, array_column($topEarlyBirds, 'avg_login_time')),
            'consistency' => array_column($topEarlyBirds, 'consistency_score')
        ];

        // Prepare night owls (drivers who login after 10 AM on average)
        $nightOwls = array_filter($driverData, function($data) {
            return $data['avg_login_time'] > (10 * 60); // After 10 AM
        });
        uasort($nightOwls, function($a, $b) {
            return $b['avg_login_time'] <=> $a['avg_login_time'];
        });
        $topNightOwls = array_slice($nightOwls, 0, 8);

        $chartData['driver_night_owls'] = [
            'labels' => array_keys($topNightOwls),
            'avg_times' => array_map(function($time) {
                return $this->formatMinutesToTime($time);
            }, array_column($topNightOwls, 'avg_login_time')),
            'consistency' => array_column($topNightOwls, 'consistency_score')
        ];

        // Prepare login consistency data (top 10 most consistent drivers)
        $consistentDrivers = $driverData;
        uasort($consistentDrivers, function($a, $b) {
            return $b['consistency_score'] <=> $a['consistency_score'];
        });
        $topConsistent = array_slice($consistentDrivers, 0, 10);

        $chartData['login_consistency'] = [
            'labels' => array_keys($topConsistent),
            'scores' => array_column($topConsistent, 'consistency_score'),
            'login_counts' => array_column($topConsistent, 'total_logins')
        ];

        // Prepare candlestick data for share market style chart
        $chartData['candlestick_data'] = $this->prepareCandlestickData($driverLoginTimes);

        // Calculate summary statistics
        $totalLogins = array_sum($chartData['daily_logins']);
        $peakHourIndex = array_search(max($hourlyData), $hourlyData);
        $peakHour = sprintf('%02d:00 - %02d:00', $peakHourIndex, ($peakHourIndex + 1) % 24);

        // Find earliest and latest average login times
        $earliestDriver = !empty($earlyBirds) ? array_key_first($earlyBirds) : 'N/A';
        $latestDriver = !empty($nightOwls) ? array_key_first($nightOwls) : 'N/A';

        $chartData['summary'] = [
            'total_logins' => $totalLogins,
            'unique_drivers' => count($driverData),
            'peak_login_hour' => $peakHour,
            'avg_daily_logins' => round($totalLogins / count($chartData['dates']), 1),
            'earliest_driver' => $earliestDriver,
            'latest_driver' => $latestDriver,
            'most_consistent_driver' => !empty($topConsistent) ? array_key_first($topConsistent) : 'N/A',
            'busiest_day' => !empty($chartData['daily_logins']) ? 
                $chartData['dates'][array_search(max($chartData['daily_logins']), $chartData['daily_logins'])] : 'N/A'
        ];

        return $chartData;
    }

    private function getSOSAnalyticsData($startDate = null, $endDate = null, $driverId = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : now()->subDays(30);
        $endDate = $endDate ? Carbon::parse($endDate) : now();

        // Get SOS alerts data
        $sosQuery = SOSAlert::with('driver');
        $this->scopeToCompany($sosQuery, 'driver.driver');

        $sosAlerts = $sosQuery
            ->whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->when($driverId, fn ($q) => $q->where('driver_id', $driverId))
            ->get();

        $chartData = [
            'dates' => [],
            'daily_sos' => [],
            'hourly_distribution' => [],
            'driver_sos_frequency' => [],
            'response_time_trend' => [],
            'location_clusters' => [],
            'severity_analysis' => [],
            'summary' => []
        ];

        $dailyData = [];
        $hourlyData = array_fill(0, 24, 0);
        $driverData = [];
        $locationData = [];
        $responseTimes = [];

        foreach ($sosAlerts as $alert) {
            $date = $alert->created_at->format('Y-m-d');
            $hour = $alert->created_at->hour;
            $driver = $alert->driver->name ?? 'Unknown';
            $location = $this->simplifyCoordinates($alert->latitude, $alert->longitude);

            // Initialize daily data
            if (!isset($dailyData[$date])) {
                $dailyData[$date] = [
                    'count' => 0,
                    'response_times' => []
                ];
            }

            // Initialize driver data
            if (!isset($driverData[$driver])) {
                $driverData[$driver] = [
                    'count' => 0,
                    'locations' => [],
                    'avg_response_time' => 0
                ];
            }

            // Initialize location data
            if (!isset($locationData[$location])) {
                $locationData[$location] = 0;
            }

            // Update daily data
            $dailyData[$date]['count']++;
            
            // Calculate response time (assuming you have a responded_at field)
            if ($alert->responded_at) {
                $responseTime = $alert->created_at->diffInMinutes($alert->responded_at);
                $dailyData[$date]['response_times'][] = $responseTime;
                $responseTimes[] = $responseTime;
            }

            // Update hourly data
            $hourlyData[$hour]++;

            // Update driver data
            $driverData[$driver]['count']++;
            $driverData[$driver]['locations'][] = $location;

            // Update location data
            $locationData[$location]++;
        }

        // Calculate driver average response times
        foreach ($driverData as $driver => $data) {
            // For demo, using random response times. Replace with actual data
            $driverData[$driver]['avg_response_time'] = rand(5, 45);
        }

        // Prepare daily chart data
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $formattedDate = $currentDate->format('M d');
            
            $chartData['dates'][] = $formattedDate;
            $chartData['daily_sos'][] = $dailyData[$dateStr]['count'] ?? 0;
            
            // Calculate average response time for the day
            $responseTimes = $dailyData[$dateStr]['response_times'] ?? [];
            $avgResponseTime = !empty($responseTimes) ? round(array_sum($responseTimes) / count($responseTimes), 1) : 0;
            $chartData['response_time_trend'][] = $avgResponseTime;

            $currentDate->addDay();
        }

        // Prepare hourly distribution data - FIXED: $hourlyData is now defined
        $maxHourlyCount = max($hourlyData); // Get the maximum count for intensity calculation
        $chartData['hourly_distribution'] = array_map(function($count, $hour) use ($maxHourlyCount) {
            return [
                'hour' => sprintf('%02d:00', $hour),
                'count' => $count,
                'intensity' => $this->calculateIntensity($count, $maxHourlyCount)
            ];
        }, $hourlyData, array_keys($hourlyData));

        // Prepare driver SOS frequency (top 10 drivers)
        uasort($driverData, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });
        $topDrivers = array_slice($driverData, 0, 10);

        $chartData['driver_sos_frequency'] = [
            'labels' => array_keys($topDrivers),
            'counts' => array_column($topDrivers, 'count'),
            'response_times' => array_column($topDrivers, 'avg_response_time')
        ];

        // Prepare location clusters (top 8 locations)
        arsort($locationData);
        $topLocations = array_slice($locationData, 0, 8);

        $chartData['location_clusters'] = [
            'labels' => array_keys($topLocations),
            'counts' => array_values($topLocations),
            'heat_levels' => array_map(function($count) use ($topLocations) {
                return round(($count / max($topLocations)) * 100);
            }, array_values($topLocations))
        ];

        // Prepare severity analysis (simulated data for demo)
        $severityLevels = ['Low', 'Medium', 'High', 'Critical'];
        $severityData = [
            'Low' => rand(5, 15),
            'Medium' => rand(10, 25),
            'High' => rand(5, 20),
            'Critical' => rand(2, 10)
        ];

        $chartData['severity_analysis'] = [
            'labels' => array_keys($severityData),
            'counts' => array_values($severityData),
            'colors' => ['#00E396', '#FFB020', '#FF6B6B', '#FF0000']
        ];

        // Calculate summary statistics
        $totalSOS = array_sum($chartData['daily_sos']);
        $uniqueDrivers = count($driverData);
        $peakHourIndex = array_search(max($hourlyData), $hourlyData);
        $peakHour = sprintf('%02d:00 - %02d:00', $peakHourIndex, ($peakHourIndex + 1) % 24);

        // Find most frequent SOS driver and location
        $mostFrequentDriver = !empty($driverData) ? array_key_first($driverData) : 'N/A';
        $mostCommonLocation = !empty($locationData) ? array_key_first($locationData) : 'N/A';

        $chartData['summary'] = [
            'total_sos_alerts' => $totalSOS,
            'unique_drivers_affected' => $uniqueDrivers,
            'peak_sos_hour' => $peakHour,
            'most_frequent_driver' => $mostFrequentDriver,
            'most_common_location' => $mostCommonLocation,
            'avg_daily_alerts' => round($totalSOS / count($chartData['dates']), 1),
            'response_rate' => count($responseTimes) > 0 ? round((count($responseTimes) / $totalSOS) * 100) : 0,
            'critical_day' => !empty($chartData['daily_sos']) ? 
                $chartData['dates'][array_search(max($chartData['daily_sos']), $chartData['daily_sos'])] : 'N/A'
        ];

        return $chartData;
    }

    private function simplifyCoordinates($lat, $lon)
    {
        // Round coordinates to 2 decimal places for clustering
        return round($lat, 2) . ', ' . round($lon, 2);
    }

    private function calculateIntensity($count, $maxCount)
    {
        if ($maxCount === 0) return 0;
        return round(($count / $maxCount) * 100);
    }

    private function prepareCandlestickData($driverLoginTimes)
    {
        $candlestickData = [];
        
        foreach ($driverLoginTimes as $driver => $times) {
            if (count($times) >= 5) { // Only include drivers with sufficient data
                sort($times);
                
                $q1 = $times[floor(count($times) * 0.25)];
                $q3 = $times[floor(count($times) * 0.75)];
                $median = $times[floor(count($times) * 0.5)];
                $min = min($times);
                $max = max($times);
                
                $candlestickData[] = [
                    'x' => $driver,
                    'y' => [
                        $this->formatMinutesToTime($min, true),
                        $this->formatMinutesToTime($q1, true),
                        $this->formatMinutesToTime($median, true),
                        $this->formatMinutesToTime($q3, true),
                        $this->formatMinutesToTime($max, true)
                    ]
                ];
            }
        }
        
        return $candlestickData;
    }

    private function calculateStandardDeviation($array)
    {
        if (count($array) < 2) return 0;
        
        $mean = array_sum($array) / count($array);
        $carry = 0.0;
        
        foreach ($array as $val) {
            $d = ((double) $val) - $mean;
            $carry += $d * $d;
        }
        
        return sqrt($carry / count($array));
    }


    private function formatMinutesToTime($minutes)
    {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        return "{$hours}h {$mins}m";
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $earthRadius * $angle;
    }
}