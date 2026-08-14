<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeliverySchedule;
use App\Models\DeliveryScheduleShop;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    //
    public function filter(Request $request)
    {
        $driver_id = $request->user()->id;
        $filter = $request->input('filter');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $baseQuery = DeliveryScheduleShop::whereHas('deliverySchedule', function ($q) use ($driver_id) {
            $q->where('driver_id', $driver_id);
        });

        switch ($filter) {
            case 'today':
                $dateRange = [Carbon::today(), Carbon::today()];
                break;
            case 'yesterday':
                $dateRange = [Carbon::yesterday(), Carbon::yesterday()];
                break;
            case 'last_15_days':
                $dateRange = [Carbon::now()->subDays(15), Carbon::now()];
                break;
            case 'last_month':
                $dateRange = [
                    Carbon::now()->subMonthNoOverflow()->startOfMonth(),
                    Carbon::now()->subMonthNoOverflow()->endOfMonth()
                ];
                break;
            case 'custom':
                if ($fromDate && $toDate) {
                    $dateRange = [Carbon::parse($fromDate), Carbon::parse($toDate)];
                } else {
                    return response()->json([
                        'response' => false,
                        'message' => 'From and To dates are required for custom filter.',
                    ]);
                }
                break;
            default:
                $dateRange = [Carbon::today(), Carbon::today()];
                break;
        }

        // Get delivered shop count
        $deliveredCount = (clone $baseQuery)
            ->where('is_delivered', 1)
            // ->whereBetween('delivered_at', [$dateRange[0]->startOfDay(), $dateRange[1]->endOfDay()])
            ->count();

        // Get accepted delivery count (example: status = 'Accepted', you can adjust as per actual logic)
        $acceptedCount = (clone $baseQuery)
            // ->where('status', 'accepted')
            ->where('is_accepted', 1)
            // ->whereBetween('created_at', [$dateRange[0]->startOfDay(), $dateRange[1]->endOfDay()])
            ->count();

        return response()->json([
            'response' => true,
            'message' => 'Shop delivery stats fetched',
            'data' => [
                'filter' => $filter,
                'delivered_shop_count' => $deliveredCount,
                'accepted_shop_count' => $acceptedCount,
            ],
        ]);
    }
    
    public function dashboard(Request $request)
    {
        $driver_id = $request->user()->id;
        $filter = $request->input('filter');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
    
        $baseQuery = DeliveryScheduleShop::whereHas('deliverySchedule', function ($q) use ($driver_id) {
            $q->where('driver_id', $driver_id);
        });
        
        switch ($filter) {
            case 'today':
                $dateRange = [Carbon::today(), Carbon::today()];
                break;
            case 'yesterday':
                $dateRange = [Carbon::yesterday(), Carbon::yesterday()];
                break;
            case 'last_15_days':
                $dateRange = [Carbon::now()->subDays(15), Carbon::now()];
                break;
            case 'last_month':
                $dateRange = [
                    Carbon::now()->subMonthNoOverflow()->startOfMonth(),
                    Carbon::now()->subMonthNoOverflow()->endOfMonth()
                ];
                break;
            case 'custom':
                if ($fromDate && $toDate) {
                    $dateRange = [Carbon::parse($fromDate), Carbon::parse($toDate)];
                } else {
                    return response()->json([
                        'response' => false,
                        'message' => 'From and To dates are required for custom filter.',
                    ]);
                }
                break;
            default:
                $dateRange = [Carbon::today(), Carbon::today()];
                break;
        }
    
        // $totalDelivered = (clone $baseQuery)->where('is_delivered', 1)->count();
        // $totalCancelled = (clone $baseQuery)->where('is_cancelled', 1)->count();
        // $totalRejected = (clone $baseQuery)->where('status','rejected')->count();
        // $totalReceived = (clone $baseQuery)->count();
        
        $totalDelivered = (clone $baseQuery)
            ->where('is_delivered', 1)
            ->whereBetween('delivered_at', [$dateRange[0]->startOfDay(), $dateRange[1]->endOfDay()])
            ->count();
            
        $totalCancelled = (clone $baseQuery)
            ->where('is_cancelled', 1)
            ->whereBetween('delivered_at', [$dateRange[0]->startOfDay(), $dateRange[1]->endOfDay()])
            ->count();
            
        $totalRejected = (clone $baseQuery)
            ->where('status','rejected')
            ->whereBetween('created_at', [$dateRange[0]->startOfDay(), $dateRange[1]->endOfDay()])
            ->count();
            
        $totalReceived = (clone $baseQuery)
            // ->where('status','accepted')
            ->where('is_accepted', 1)
            ->whereBetween('created_at', [$dateRange[0]->startOfDay(), $dateRange[1]->endOfDay()])
            ->count();
            
            
        return response()->json([
            'response' => true,
            'message' => 'Delivery shop count fetched successfully',
            'data' => [
                'total_received'=>$totalReceived,
                'total_delivered' => $totalDelivered,
                'total_cancelled' => $totalCancelled,
                'total_rejected'=>$totalRejected,
            ]
        ]);
    }

    
    
    public function deliveryStats(Request $request)
    {
        $driver_id = $request->user()->id;
    
        $baseQuery = DeliveryScheduleShop::whereHas('deliverySchedule', function ($q) use ($driver_id) {
            $q->where('driver_id', $driver_id);
        });
    
        // Day-wise stats for last N days (you can adjust range)
    $dailyStats = (clone $baseQuery)
    ->selectRaw("
        DAYNAME(COALESCE(delivered_at, cancelled_at)) as day_name,
        DATE(COALESCE(delivered_at, cancelled_at)) as date,
        YEAR(COALESCE(delivered_at, cancelled_at)) as year,
        COUNT(CASE WHEN is_delivered = 1 THEN 1 END) as delivered_count,
        COUNT(CASE WHEN is_cancelled = 1 THEN 1 END) as cancelled_count
    ")
    ->where(function ($q) {
        $q->whereNotNull('delivered_at')
          ->orWhereNotNull('cancelled_at');
    })
    ->groupByRaw("
        DAYNAME(COALESCE(delivered_at, cancelled_at)),
        DATE(COALESCE(delivered_at, cancelled_at)),
        YEAR(COALESCE(delivered_at, cancelled_at))
    ")
    ->orderByRaw("DATE(COALESCE(delivered_at, cancelled_at)) DESC")
    ->limit(30)
    ->get();

    
        return response()->json([
            'response' => true,
            'message' => 'Daily delivery stats fetched successfully',
            'data' => [
                'weekly_stats' => $dailyStats
            ]
        ]);
    }
    
    
    
  
  public function weeklyDeliveryStats(Request $request)
{
    $driver_id = $request->user()->id;

    $weeklyStats = DeliveryScheduleShop::selectRaw("
            DATE(COALESCE(delivered_at, cancelled_at)) as date,
            COUNT(CASE WHEN is_delivered = 1 THEN 1 END) as delivered_count,
            COUNT(CASE WHEN is_cancelled = 1 THEN 1 END) as cancelled_count
        ")
        ->whereHas('deliverySchedule', function ($q) use ($driver_id) {
            $q->where('driver_id', $driver_id);
        })
        ->where(function ($q) {
            $q->whereNotNull('delivered_at')
              ->orWhereNotNull('cancelled_at');
        })
        ->whereBetween(DB::raw('DATE(COALESCE(delivered_at, cancelled_at))'), [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])
        ->groupBy(DB::raw("DATE(COALESCE(delivered_at, cancelled_at))"))
        ->orderBy(DB::raw("DATE(COALESCE(delivered_at, cancelled_at))"), 'ASC')
        ->get()
        ->map(function ($item) {
            return [
                'day_name' => Carbon::parse($item->date)->format('l'),
                'date' => $item->date,
                'year' => Carbon::parse($item->date)->year,
                'delivered_count' => $item->delivered_count,
                'cancelled_count' => $item->cancelled_count,
            ];
        });

    return response()->json([
        'response' => true,
        'message' => 'Weekly delivery stats fetched successfully',
        'data' => [
            'weekly_stats' => $weeklyStats,
        ],
    ]);
}



// public function dailyStats(Request $request)
// {
//     $driver_id = $request->user()->id;

//     $startDate = Carbon::now()->startOfWeek(); // Monday
//     $endDate = Carbon::now()->endOfWeek();     // Sunday

//     // Get daily delivered + cancelled counts
//     $rawStats = DeliveryScheduleShop::selectRaw("
//             DATE(COALESCE(delivered_at, cancelled_at)) as stat_date,
//             COUNT(CASE WHEN is_delivered = 1 THEN 1 END) as delivered_count,
//             COUNT(CASE WHEN is_cancelled = 1 THEN 1 END) as cancelled_count
//         ")
//         ->whereHas('deliverySchedule', function ($q) use ($driver_id) {
//             $q->where('driver_id', $driver_id);
//         })
//         ->where(function ($q) {
//             $q->whereNotNull('delivered_at')->orWhereNotNull('cancelled_at');
//         })
//         ->whereBetween(DB::raw('DATE(COALESCE(delivered_at, cancelled_at))'), [
//             $startDate->toDateString(),
//             $endDate->toDateString()
//         ])
//         ->groupBy(DB::raw('DATE(COALESCE(delivered_at, cancelled_at))'))
//         ->get()
//         ->keyBy('stat_date');

//     // Build final 7-day response
//     $dailyStats = [];
//     $current = $startDate->copy();

//     while ($current <= $endDate) {
//         $key = $current->toDateString();
//         $stat = $rawStats->get($key);

//         $dailyStats[] = [
//             'date' => $current->format('d M'),
//             'year' => $current->year,
//             'delivered_count' => $stat ? (int) $stat->delivered_count : 0,
//             'cancelled_count' => $stat ? (int) $stat->cancelled_count : 0,
//         ];

//         $current->addDay();
//     }

//     return response()->json([
//         'response' => true,
//         'message' => 'Weekly delivery stats fetched successfully',
//         'data' => [
//             'weekly_stats' => $dailyStats
//         ]
//     ]);
// }

    
public function dailyStats(Request $request)
{
    $driver_id = $request->user()->id;

    $startDate = Carbon::now()->startOfWeek(); // Monday
    $endDate = Carbon::now()->endOfWeek();     // Sunday

    // Fetch actual data grouped by date
    $rawStats = DeliveryScheduleShop::selectRaw("
            DATE(COALESCE(delivered_at, cancelled_at)) as stat_date,
            COUNT(CASE WHEN is_delivered = 1 THEN 1 END) as delivered_count,
            COUNT(CASE WHEN is_cancelled = 1 THEN 1 END) as cancelled_count
        ")
        ->whereHas('deliverySchedule', function ($q) use ($driver_id) {
            $q->where('driver_id', $driver_id);
        })
        ->where(function ($q) {
            $q->whereNotNull('delivered_at')
              ->orWhereNotNull('cancelled_at');
        })
        ->whereBetween(DB::raw('DATE(COALESCE(delivered_at, cancelled_at))'), [
            $startDate->toDateString(),
            $endDate->toDateString()
        ])
        ->groupBy(DB::raw('DATE(COALESCE(delivered_at, cancelled_at))'))
        ->get()
        ->keyBy('stat_date');

    // Format data for each day in the week
    $dailyStats = [];
    $current = $startDate->copy();

    while ($current <= $endDate) {
        $key = $current->toDateString();
        $stat = $rawStats->get($key);

        $dailyStats[] = [
            'day_name' => $current->format('l'), // Full day name like Monday, Tuesday
            'date' => $current->format('d M'),
            'year' => $current->year,
            'delivered_count' => $stat ? (int) $stat->delivered_count : 0,
            'cancelled_count' => $stat ? (int) $stat->cancelled_count : 0,
        ];

        $current->addDay();
    }

    return response()->json([
        'response' => true,
        'message' => 'Weekly delivery stats fetched successfully',
        'data' => [
            'weekly_stats' => $dailyStats
        ]
    ]);
}




     
}
