<?php

use App\Models\Fare;
use App\Models\BookingSeat;
use App\Models\Journey;
use App\Models\JourneyStoppage;
use App\Models\BusRouteStop;
use Illuminate\Support\Str;
use App\Models\Settings;
use App\Models\Driver;

if (! function_exists('d')) {
    function d($data)
    {
        echo "<pre>";
        print_r($data);
        die;
    }
}

if(!function_exists('createSlug')) {
    function createSlug($name, $model)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;

        $count = 1;
        while ($model::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}


if (!function_exists('generateBookingNumber')) {
    function generateBookingNumber() {
        $dateTime = date('YmdHis');
        $orderNumber = 'ORD' . $dateTime;
        return $orderNumber;
    }
}

if (!function_exists('generateOrderNumber')) {
    function generateOrderNumber() {
        $dateTime = date('YmdHis');
        // $orderNumber = 'ORD' . $dateTime;
        $orderNumber = 'O' . $dateTime;
        return $orderNumber;
    }
}

if (!function_exists('getFare')) {
    function getFare($route_id, $from_stop_id, $to_stop_id) {
        $fare = Fare::where('route_id', $route_id)
            ->where('from_stop_id', $from_stop_id)
            ->where('to_stop_id', $to_stop_id)
            ->first();

        return $fare ? $fare->price : 0;
    } 
}


if (!function_exists('getDistanceFromAPI')) {
    function getDistanceFromAPI($originLat, $originLng, $destinationLat, $destinationLng)
    {
        $apiKey = 'AlzaSyC7RSr791vm_29LJiUOPJO-sLnBZg6qiGl'; // Move to config if needed
        $url = "https://maps.gomaps.pro/maps/api/distancematrix/json?origins={$originLat},{$originLng}&destinations={$destinationLat},{$destinationLng}&key={$apiKey}";

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Cookie: connect.sid=s%3AFEvNhVmvOeU2O_PrQnHnNUhsfZinMt_O.qawZqONQJjP4tPNqTd9pZ56jbeLb%2FxSpR%2BMVZetM6VY'  // Optional if required
            ],
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            return [
                'status' => 'error',
                'message' => 'cURL Error: ' . $error
            ];
        }

        $data = json_decode($response, true);

        if (!isset($data['status']) || $data['status'] !== 'OK') {
            return [
                'status' => 'error',
                'message' => $data['error_message'] ?? 'Failed to fetch distance'
            ];
        }

        // Extract data from response
        $element = $data['rows'][0]['elements'][0] ?? null;
        if (!$element || $element['status'] !== 'OK') {
            return [
                'status' => 'error',
                'message' => 'No valid distance data found'
            ];
        }

        return [
            'status' => 'success',
            'distance_text' => $element['distance']['text'] ?? 'N/A',
            'distance_value' => $element['distance']['value'] ?? 0,  // in meters
            'duration_text' => $element['duration']['text'] ?? 'N/A',
            'duration_value' => $element['duration']['value'] ?? 0,  // in seconds
            'origin_address' => $data['origin_addresses'][0] ?? '',
            'destination_address' => $data['destination_addresses'][0] ?? ''
        ];
    }
}

if (!function_exists('getBookedSeats')) {
    function getBookedSeats($booking_id, $asString = false) {
        $booked_seats = BookingSeat::where('booking_id', $booking_id)->pluck('seat_number')->toArray();

        return $asString ? implode(', ', $booked_seats) : $booked_seats;
    }
}

if (!function_exists('getJourneyStoppageUpdate')) {
    function getJourneyStoppageUpdate($journey_id) {
        $journey_stoppage = JourneyStoppage::where('journey_id', $journey_id)
            ->whereNotNull('departure_time') 
            ->orderBy('departure_time', 'desc')
            ->first();

        return $journey_stoppage ;
    }
}

if (!function_exists('getStopOrder')) {
    function getStopOrder($bus_route_id, $bus_stop_id) {
        return BusRouteStop::where('bus_route_id', $bus_route_id)
            ->where('bus_stop_id', $bus_stop_id)
            ->value('sl_no');
    }
}

if (!function_exists('isSeatPartiallyAvailable')) {
    function isSeatPartiallyAvailable($seat_number, $from_sl_no, $to_sl_no, $partially_booked_seats) {
        if (!isset($partially_booked_seats[$seat_number])) {
            return false; // Seat is not partially booked at all
        }

        foreach ($partially_booked_seats[$seat_number] as $segment) {
            $existing_start = $segment['start_sl_no'];
            $existing_end = $segment['end_sl_no'];

            // Check if requested range is fully inside any booked segment (Overlap means NOT available)
            if ($from_sl_no >= $existing_start && $to_sl_no <= $existing_end) {
                return false; // Overlapping fully booked segment, not available
            }

            // Check if there is any non-overlapping segment, meaning it's partially available
            if (!($to_sl_no <= $existing_start || $from_sl_no >= $existing_end)) {
                return true; // Some part of the segment is still available
            }
        }

        return false; // No valid partial availability found
    }
}


if (!function_exists('generateVehicleData')) {
    function generateVehicleData($count = 15){
        $vehicles = [];
        $types = ['bus', 'taxi', 'auto', 'bike'];
        $locations = [
            ['lat' => 22.5726, 'lng' => 88.3639], // Kolkata center
            ['lat' => 22.5359, 'lng' => 88.3467], // South Kolkata
            ['lat' => 22.5897, 'lng' => 88.3696], // North Kolkata
            ['lat' => 22.5048, 'lng' => 88.3247], // Behala
            ['lat' => 22.5937, 'lng' => 88.2709], // Howrah
        ];

        for ($i = 0; $i < $count; $i++) {
            $type = $types[array_rand($types)];
            $baseLocation = $locations[array_rand($locations)];
            
            // Add small random variations to locations
            $lat = $baseLocation['lat'] + (rand(-50, 50) / 1000);
            $lng = $baseLocation['lng'] + (rand(-50, 50) / 1000);
            
            $vehicles[] = [
                'id' => $type . '-' . rand(1000, 9999),
                'type' => $type,
                'latitude' => $lat,
                'longitude' => $lng,
                'speed' => rand(5, 80),
                'timestamp' => now()->toIso8601String(),
                'direction' => rand(0, 359),
            ];
        }

        return $vehicles;
    }
}


if (!function_exists('settings')) {
    function settings() {
        $setting = Settings::first();
        return $setting;
    }
}



// if (!function_exists('sendNotificationToDriver')) {
//     function sendNotificationToDriver($fcmToken, $data)
//     {
//         $SERVER_API_KEY = env('FIREBASE_SERVER_KEY');
    
//         $payload = [
//             "to" => $fcmToken,
//             "notification" => [
//                 "title" => $data['title'],
//                 "body" => $data['body'],
//                 "sound" => "default"
//             ],
//             "data" => $data['data'],
//         ];
    
//         $headers = [
//             "Authorization: key=$SERVER_API_KEY",
//             "Content-Type: application/json"
//         ];
    
//         $ch = curl_init();
    
//         curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    
//         $response = curl_exec($ch);
//         curl_close($ch);
    
//         \Log::info('FCM response: ' . $response);
//     }
// }


// function sendNotificationToDriver($player_id, $data)
// {


//     $fields = [
//         'app_id' => env('ONESIGNAL_APP_ID'),
//         'include_player_ids' => ["c7616145-19e7-4b34-a919-46e651171848"],
//         'headings' => ['en' => $data['title']],
//         'contents' => ['en' => $data['body']],
//         'data' => $data['body'] ?? [],
//     ];

//     $headers = [
//         'Content-Type: application/json',
//         'Authorization: Basic ' . env('ONESIGNAL_REST_API_KEY'),
//     ];

//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

//     $response = curl_exec($ch);
//     $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//     curl_close($ch);
    
//     d($response);

//     return response()->json([
//         'status' => $httpCode,
//         'response' => json_decode($response, true)
//     ]);
// }


function sendNotificationToDriver($player_id, $data)
{
    $fields = [
        'app_id' => env('ONESIGNAL_APP_ID'),
        'include_player_ids' => [$player_id], 
        'headings' => ['en' => $data['title']],
        'contents' => ['en' => $data['body']],
        'data' => $data['data'] ?? [],
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

    // \Log::info('OneSignal Notification Response', [
    //     'status' => $httpCode,
    //     'response' => $response,
    //     'payload' => $fields,
    // ]);
    


    return response()->json([
        'status' => $httpCode,
        'response' => json_decode($response, true)
    ]);
}


if (!function_exists('getDriverDetails')) {
    function getDriverDetails($userId) {
        $journey_stoppage = Driver::with('vehicle')->where('user_id', $userId)->first();

        return $journey_stoppage ;
    }
}

if (!function_exists('shortenUrl')) {
    function shortenUrl($longUrl)
    {
        try {
            // Example using TinyURL free API
            $response = Http::get('https://tinyurl.com/api-create.php', [
                'url' => $longUrl
            ]);
    
            if ($response->ok()) {
                return $response->body();
            }
    
            // fallback if something goes wrong
            return $longUrl;
        } catch (\Exception $e) {
            // Optional: Log error
            return $longUrl;
        }
    }
}

// if (!function_exists('shortenUrl')) {
//     function shortenUrl($longUrl) {
//         $response = Http::withToken('7e4074ea01b5a692c66e6596c11951d1147ccd28')
//         ->post('https://api-ssl.bitly.com/v4/shorten', [
//             'long_url' => $longUrl
//         ]);

//         if ($response->successful()) {
//             return $response->json()['link'];
//         }

//         return $longUrl; // fallback to original if Bitly fails

//     }
// }


// if (!function_exists('shortenUrl')) {
//     function shortenUrl($longUrl) {
//         $response = Http::withToken('7e4074ea01b5a692c66e6596c11951d1147ccd28')
//             ->post('https://api-ssl.bitly.com/v4/shorten', [
//                 'long_url' => $longUrl
//             ]);

//         if ($response->successful()) {
//             return $response->json()['link'];
//         }

//         return $longUrl; // fallback if Bitly fails
//     }
// }

