<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeliverySchedule;
use App\Models\DeliveryScheduleShop;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Shop;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class DeliveryController extends Controller
{
    // public function index(Request $request){

    //     $deliveries= DeliverySchedule::where('driver_id',$request->user()->id)
    //                                 ->whereDate('delivery_date', date('Y-m-d'))
    //                                 // ->with(['shops', 'vehicle'])
    //                                 ->with(['shops', 'vehicle', 'deliveryScheduleShops' => function ($query) {
    //                                     $query->select('id', 'delivery_schedule_id', 'shop_id', 'is_delivered'); 
    //                                 }])
    //                      ->get();

    //   return response()->json([
    //         'response' => true,
    //         'message' => 'get delivery details',
    //         'data' => [
    //             'deliveries' => $deliveries
    //         ]
    //     ]);
    // }
    
    
    //   public function index(Request $request)
    // {
    //     $deliveries = DeliverySchedule::where('driver_id', $request->user()->id)
    //         ->whereDate('delivery_date', date('Y-m-d'))
    //         ->with([
    //             // 'shops:id,shop_number,shop_name,shop_address,shop_contact_person_name,shop_contact_person_phone,shop_latitude,shop_longitude,is_visible',
    //             'vehicle:id,name,vehicle_number,engine_number,ac_status',
    //             'deliveryScheduleShops.shop' // include shop details inside deliveryScheduleShops
    //         ])
    //         ->get();

    //     return response()->json([
    //         'response' => true,
    //         'message' => 'Delivery details fetched successfully.',
    //         'data' => [
    //             'deliveries' => $deliveries
    //         ]
    //     ]);
    // }


    public function index(Request $request)
    {
        if($request->driver_id){
            $driver_id = $request->driver_id;
        }else{
            $driver_id = $request->user()->id;
        }
        
        // $deliveries = DeliverySchedule::where('driver_id', $driver_id)
        //     ->whereDate('delivery_date', date('Y-m-d'))
        //     ->with([
        //         'vehicle:id,name,vehicle_number,engine_number,ac_status',
        //         'deliveryScheduleShops.shop',
        //         'deliveryScheduleShops.media' // Load media relation for delivery image and signature
        //     ])
        //     ->get();
        $deliveries = DeliverySchedule::where('driver_id', $driver_id)
            ->where(function ($q) {
                $q->whereDate('delivery_date', date('Y-m-d')) // today's schedules
                ->orWhereHas('deliveryScheduleShops', function ($sub) {
                    $sub->where('is_delivered', 0); // previous incomplete ones
                });
            })
            ->with([
                'vehicle:id,name,vehicle_number,engine_number,ac_status',
                'deliveryScheduleShops' => function ($query) {
                    $query->where('is_delivered', 0) // only incomplete shops
                        ->with(['shop', 'media']);
                },
            ])
            ->get();
        // return $deliveries;
    
        // Enhance response to include image URLs
        $deliveries = $deliveries->map(function ($delivery) {
            $delivery->deliveryScheduleShops->transform(function ($shop) {
                $shop->delivery_image_url = $shop->hasMedia('delivery-image') ? $shop->getFirstMediaUrl('delivery-image') : null;
                $shop->signature_url = $shop->hasMedia('signature') ? $shop->getFirstMediaUrl('signature') : null;
                return $shop;
            });
            return $delivery;
        });
    
        return response()->json([
            'response' => true,
            'message' => 'Delivery details fetched successfully.',
            'data' => [
                'deliveries' => $deliveries
            ]
        ]);
    }



    
    
    public function complete_delivery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_id' => 'required|exists:delivery_schedule_shops,id',
            'otp' => 'required_if:status,Delivered',
            'status' => 'required|in:Delivered,Pending',
     
            // 'delivery_image' => ['nullable', function ($attribute, $value, $fail) {
            //     if (!empty($value)) {
            //         $size = (int)(strlen($value) * 3 / 4); // estimate size in bytes
            //         if ($size > 10 * 1024 * 1024) { // 10 MB
            //             $fail('The Delivery Image must not be larger than 10 MB.');
            //         }
            //     }
            // }],
            
            // 'signature' => ['nullable', function ($attribute, $value, $fail) {
            //     if (!empty($value)) {
            //         $size = (int)(strlen($value) * 3 / 4); // estimate size in bytes
            //         if ($size > 10 * 1024 * 1024) { // 10 MB
            //             $fail('The Delivery Image must not be larger than 10 MB.');
            //         }
            //     }
            // }],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // $delivery = DeliveryScheduleShop::where('delivery_schedule_id',$request->delivery_id)->first();
        $delivery = DeliveryScheduleShop::find($request->delivery_id);
        


        if ($request->status === 'Delivered') {
            if ($request->otp == $delivery->otp) {
                $delivery->is_delivered = 1;
                $delivery->status = "delivered";
                $delivery->delivered_at = now();
                $delivery->deliver_lat = $request->latitude;
                $delivery->deliver_long = $request->longitute;

                if ($request->filled('delivery_image')) {
                    $base64Image = $request->input('delivery_image');

                    $delivery->addMediaFromBase64($base64Image)
                        ->usingFileName(Str::random(10).'.png')
                        ->toMediaCollection('delivery-image');
                }
                
                if ($request->filled('signature')) {
                    $base64Image = $request->input('signature');

                    $delivery->addMediaFromBase64($base64Image)
                        ->usingFileName(Str::random(10).'.png')
                        ->toMediaCollection('signature');
                }
       
                 if ($request->filled('route_map_image')) {
                    $base64Image = $request->input('route_map_image');

                    $delivery->addMediaFromBase64($base64Image)
                        ->usingFileName(Str::random(10).'.png')
                        ->toMediaCollection('route_map_image');
                }
                $delivery->save();
                
                $this->is_all_delivery_completed($delivery->delivery_schedule_id);

                return response()->json([
                    'response' => true,
                    'message' => 'OTP Verified & Delivered Successfully',
                    'data' => [
                        'delivery' => $delivery,
                        'delivery_image' => $delivery->getFirstMediaUrl('delivery-image'), // image URL
                        'signature' => $delivery->getFirstMediaUrl('signature'), // image URL
                         'route_map_image' => $delivery->getFirstMediaUrl('route_map_image'), // image URL
                    ],
                ]);
            } else {
                return response()->json(['error' => 'Invalid OTP'], 400);
            }
        }
    }
    
    private function is_all_delivery_completed($delivery_schedule_id){
        $delivery_schedule_count = DeliveryScheduleShop::where('delivery_schedule_id',$delivery_schedule_id)->count();
        $delivery_schedule_completed_count = DeliveryScheduleShop::where('delivery_schedule_id',$delivery_schedule_id)->where('is_delivered',1)->count();
        $delivery_schedule = DeliverySchedule::find($delivery_schedule_id);
        if($delivery_schedule_count == $delivery_schedule_completed_count){
            $delivery_schedule->is_completed = 1;
        }else{
            $delivery_schedule->is_completed = 0;
        }
        $delivery_schedule->update();
    }
    
    public function sendNewOTPToShopWoner(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'delivery_id' => 'required|exists:delivery_schedule_shops,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        
        $delivery = DeliveryScheduleShop::find($request->delivery_id);
        // $otp = 1234;
        $otp = rand(1000, 9999);
        $delivery->otp=$otp;
        $delivery->save();
        
        $shop = Shop::find($delivery->shop_id);
        $phoneNumber=$shop->shop_contact_person_phone;
        
    
        $response_sms= $this->sendOtpMessage($phoneNumber, $otp);
    
        return response()->json([
            'status' => true,
            'message' => 'New OTP generated successfully.',
            'sent' => 'An OTP has been sent to your phone number.',
            'phone_number'=>$phoneNumber,
            'response_sms'=>$response_sms,
        ]);
    }
     protected function sendOtpMessage($mobile, $otp)
    {
        //  $msg = "Dear Customer, We welcome you to the Agiltas family! We would like to give you an update on your newly opened account in MYPOSPAY. We always assure you of our best services. Warm Regards, Agiltas Solution";
        $msg = "Your one time password is .$otp. Please use this One Time Password (OTP) within the next ten minutes to proceeds Thank You .AGILTAS SOLUTION";
        $encodedMsg = urlencode($msg);
    
        $url = "https://sms.cell24x7.in/smsReceiver/sendSMS";
        $postFields = "user=agiltas&pwd=apiagiltas&sender=AGILTS&mobile={$mobile}&msg={$encodedMsg}&mt=0";
    
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // safety timeout
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    
        $result = curl_exec($ch);
    
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            // Optional: log error
            return "CURL Error: " . $error;
        }
    
        curl_close($ch);
        return $result;
    }
    
    
    public function cancel_delivery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_id' => 'required|exists:delivery_schedule_shops,id',
            'cancel_reason' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $delivery = DeliveryScheduleShop::find($request->delivery_id);
        // $delivery = DeliveryScheduleShop::where('delivery_schedule_id',$request->delivery_id)->first();
        
        $delivery->is_cancelled = 1;
        $delivery->is_delivered = 0;
        $delivery->cancelled_at = now();
        $delivery->cancel_reason = $request->cancel_reason;
        $delivery->status = "cancelled";
        $delivery->save();

        return response()->json([
            'response' => true,
            'message' => 'Delivery Cancelled Successfully',
            'data' => [
                'delivery' => $delivery,
            ],
        ]);
    } 
    
    
    public function update_delivery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:delivery_schedule_shops,id',
            'status'=>'required',
        ]);
        
        $driver_latitude  = $request->latitude;
        $driver_longitude = $request->longitude;

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $delivery = DeliveryScheduleShop::find($request->id);

        
        if($request->status =='accepted')
        {
            
            $delivery->status = 'accepted';
            $delivery->is_accepted = 1;
            $delivery->accepted_at = now();
            $delivery->accepted_lat = $request->lat;
            $delivery->accepted_long = $request->long; 
        }
        if($request->status =='rejected')
        {
            
             $delivery->status = 'rejected';
        }
        
        $delivery->save();
        $shop_id = $delivery->shop_i;
      
      
        // Generate tracking URL with signed route (optional)
        $trackingUrl = URL::temporarySignedRoute(
            'track.delivery', now()->addMinutes(30), [
                'delivery_id' => $delivery->id,
                'driver_id'=>$request->user()->id,
                'lat' => $request->latitude,
                'lng' => $request->longitude
            ]
        );
        
        
        // $trackingUrl = URL::temporarySignedRoute(
        //     'track.delivery',     now()->addDays(7), [
        //         'delivery_id' => $delivery->id,
        //         'driver_id'=>$request->user()->id,
        //         'lat' => $request->latitude,
        //         'lng' => $request->longitude
        //     ]
        // );
        
        $shop = Shop::find($delivery->shop_id);
        // $mobile = 9547480822 ;
        $mobile = $shop->shop_contact_person_phone ;
        // $response_message=    $this->sendMessage($mobile, $trackingUrl);

        return response()->json([
            'response' => true,
            'message' => 'Delivery '.ucfirst($request->status) .' Successfully',
            'data' => [
                'delivery' => $delivery,
                'tracking_url'=>$trackingUrl,
                // 'response_message'=>$response_message
            ],
        ]);
    } 
    
    public function updateSerials(Request $request)
    {
        $data = $request->input('tasks');
        
        if (!is_array($data)) {
            return response()->json(['message' => 'Invalid data format'], 422);
        }
    
        foreach ($data as $item) {
            if (isset($item['id']) && isset($item['serial_number'])) {
                DeliveryScheduleShop::where('id', $item['id'])->update([
                    'app_serial' => $item['serial_number']
                ]);
            }
        }
    
        return response()->json(['response' => true,'message' => 'Serial numbers updated successfully.']);
    }
    
     public function sendMessage($mobile, $trackingUrl)
    {
        $shortUrl = shortenUrl($trackingUrl);
        
        // d($shortUrl);
        $otp = 1234;

        // $msg = "Thanks for shopping at pospay. To view invoice, offers, give feedback, click .$shortUrl.. Team Agiltas";
        $msg = "Your one time password is .$shortUrl. Please use this One Time Password (OTP) within the next ten minutes to proceeds Thank You .AGILTAS SOLUTION";

        $encodedMsg = urlencode($msg);
    
        $url = "https://sms.cell24x7.in/smsReceiver/sendSMS";
        $postFields = "user=agiltas&pwd=apiagiltas&sender=AGILTS&mobile={$mobile}&msg={$encodedMsg}&mt=0";
    
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // safety timeout
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    
        $result = curl_exec($ch);
    
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            // Optional: log error
            return "CURL Error: " . $error;
        }
    
        curl_close($ch);
        return $result;
    }
    

    //  public function delivery_history(Request $request)
    // {
    //     $userId = $request->user()->id;
    
    //     $deliveryShops = DeliveryScheduleShop::with([
    //         'shop',
    //         'media',
    //         'deliverySchedule.vehicle'
    //     ])
    //     ->whereHas('deliverySchedule', function ($query) use ($userId) {
    //         $query->where('driver_id', $userId);
    //     })
    //     ->where('status', '!=', 'pending') 
    //     ->orderByDesc('id')
    //     ->get();
    
    //     $data = $deliveryShops->map(function ($shop) {
    //         return [
    //             'id' => $shop->id,
    //             'delivery_schedule_id' => $shop->delivery_schedule_id,
    //             'shop_id' => $shop->shop_id,
    //             'order_serial' => $shop->order_serial,
    //             'otp' => $shop->otp,
    //             'status' => $shop->status,
    //             'is_delivered' => $shop->is_delivered,
    //             'delivered_at' => $shop->delivered_at,
    //             'is_cancelled' => $shop->is_cancelled,
    //             'cancelled_at' => $shop->cancelled_at,
    //             'cancel_reason' => $shop->cancel_reason,
    //             'created_at' => $shop->created_at,
    //             'updated_at' => $shop->updated_at,
    //             'delivery_image_url' => $shop->getFirstMediaUrl('delivery-image') ?? '',
    //             'signature_url' => $shop->getFirstMediaUrl('signature') ?? '',
    //             'shop' => $shop->shop,
    //             'vehicle' => $shop->deliverySchedule?->vehicle,
    //             'media' => $shop->getMedia()->map(function ($media) {
    //                 return [
    //                     'id' => $media->id,
    //                     'url' => $media->getUrl(),
    //                     'file_name' => $media->file_name,
    //                     'mime_type' => $media->mime_type,
    //                 ];
    //             }),
    //         ];
    //     });
    
    //     return response()->json([
    //         'response' => true,
    //         'message' => 'Delivery schedule shops fetched successfully.',
    //         'data' => [
    //             'delivery_schedule_shops' => $data,
    //         ]
    //     ]);
    // }
    
    public function delivery_history(Request $request)
    {
    $userId = $request->user()->id;
    $status = $request->status; // e.g., 'accepted', 'rejected', 'delivered', etc.
    $dateFilter = $request->date_filter;
    $fromDate = $request->from_date;
    $toDate = $request->to_date;
   $search = $request->search;
    $query = DeliveryScheduleShop::with([
        'shop',
        'media',
        'deliverySchedule.vehicle'
    ])
    ->whereHas('deliverySchedule', function ($q) use ($userId) {
        $q->where('driver_id', $userId);
    });


    if ($status === 'delivered') {
        $query->where('is_delivered', 1);
    } elseif ($status === 'cancelled') {
        $query->where('is_cancelled', 1);
    } elseif (!empty($status)) {
        $query->where('status', $status);
    } else {
        // Default: exclude 'pending'
        $query->where('status', '!=', 'pending');
    }
    
       // ✅ Filter by shop name (search)
    if (!empty($search)) {
        $query->whereHas('shop', function ($q) use ($search) {
            $q->where('shop_name', 'like', '%' . $search . '%');
        });
    }
    
        // ✅ Date filter
    if ($dateFilter === 'today') {
        $query->whereDate('created_at', Carbon::today());
    } elseif ($dateFilter === 'yesterday') {
        $query->whereDate('created_at', Carbon::yesterday());
    } elseif ($dateFilter === 'last_15_days') {
        $query->whereBetween('created_at', [Carbon::now()->subDays(15), Carbon::now()]);
    } elseif ($dateFilter === 'last_month') {
        $query->whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()]);
    } elseif ($dateFilter === 'custom' && $fromDate && $toDate) {
        $query->whereBetween('created_at', [Carbon::parse($fromDate)->startOfDay(), Carbon::parse($toDate)->endOfDay()]);
    }

    $deliveryShops = $query->orderByDesc('id')->get();

    $data = $deliveryShops->map(function ($shop) {
        return [
            'id' => $shop->id,
            'delivery_schedule_id' => $shop->delivery_schedule_id,
            'shop_id' => $shop->shop_id,
            'order_serial' => $shop->order_serial,
            'otp' => $shop->otp,
            'status' => $shop->status,
            'is_delivered' => $shop->is_delivered,
            'delivered_at' => $shop->delivered_at,
            'is_cancelled' => $shop->is_cancelled,
            'cancelled_at' => $shop->cancelled_at,
            'cancel_reason' => $shop->cancel_reason,
            'created_at' => $shop->created_at,
            'updated_at' => $shop->updated_at,
            'delivery_image_url' => $shop->getFirstMediaUrl('delivery-image') ?? '',
            'signature_url' => $shop->getFirstMediaUrl('signature') ?? '',
            'shop' => $shop->shop,
            'vehicle' => $shop->deliverySchedule?->vehicle,
            'media' => $shop->getMedia()->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'file_name' => $media->file_name,
                    'mime_type' => $media->mime_type,
                ];
            }),
        ];
    });

    return response()->json([
        'response' => true,
        'message' => 'Delivery schedule shops fetched successfully.',
        'data' => [
            'delivery_schedule_shops' => $data,
        ]
    ]);
}


public function genrateBill(Request $request)
{
    $request->validate([
        'delivery_id' => 'required|exists:delivery_schedule_shops,id'
    ]);

    $userId = $request->user()->id;
    $delivery = DeliveryScheduleShop::with('shop')->find($request->delivery_id);
    $delivery_schedule = DeliverySchedule::with('vehicle')->find($delivery->delivery_schedule_id);
    $driver_details = User::with('driver')->find($userId);
    
    $super_admin = User::role('Super Admin')->first(); // adjust if multiple admins
   

    return response()->json([
        'response' => true,
        'message' => 'Delivery details fetched successfully.',
        'data' => [
            'delivery' => $delivery,
            'delivery_image' => $delivery->getFirstMediaUrl('delivery-image'),
            'signature' => $delivery->getFirstMediaUrl('signature'),
            'delivery_schedule' => $delivery_schedule,
            'driver_details' => $driver_details,
            'shop' => $delivery->shop ?? null,
            'vehicle' => $delivery_schedule->vehicle ?? null,
            'admin' => $super_admin,
            'setting'=> settings(),
        ],
    ]);
}




    // public function genrateBill(Request $request)
    // {
    //     $userId = $request->user()->id;
    //   $delivery=  DeliveryScheduleShop::find($request->delivery_id);
        
        
    //  $delivery_schedule = DeliverySchedule::find($delivery->delivery_schedule_id);
    //  $driver_details = User::with('driver')->where('id',$userId)->first();
    
    //     return response()->json([
    //         'response' => true,
    //         'message' => 'Delivery details fetched successfully.',
    //         'data' => [
    //             'delivery' => $delivery,
    //             'delivery_image' => $delivery->getFirstMediaUrl('delivery-image'), // image URL
    //             'signature' => $delivery->getFirstMediaUrl('signature'), // image URL
    //             'delivery_schedule'=>$delivery_schedule,
    //             'driver_details'=>$driver_details
                
    //         ],
    //     ]);
    // }
    
    public function get_delivery_invoice_data(Request $request)
    {
        $delivery_id = $request->delivery_id;
        $shop_id = $request->shop_id;
    
        // Fetch the delivery schedule with the specific shop
        $deliverySchedule = DeliverySchedule::with([
            'driver.driver',
            'vehicle',
            'deliveryScheduleShops' => function ($query) use ($shop_id) {
                $query->where('shop_id', $shop_id)
                    ->with(['shop', 'products', 'branch']);
            }
        ])->find($delivery_id);
    
        if (!$deliverySchedule) {
            return response()->json([
                'response' => false,
                'message' => 'Delivery schedule not found.'
            ], 404);
        }
    
        $driver = $deliverySchedule->driver;
        $driver_details = $driver?->driver;
    
        // Single shop due to filtering
        $shop = $deliverySchedule->deliveryScheduleShops->first();
        // return $shop;
    
        if (!$shop) {
            return response()->json([
                'response' => false,
                'message' => 'Shop not found in this delivery schedule.'
            ], 404);
        }
    
        $invoiceData = [
            'order_no'        => $shop->invoice_no ?? 'N/A',
            'invoice_no'      => $shop->lr_no ?? 'N/A',
            'consignor'       => $shop->branch?->name ?? 'N/A',
            'consignee'       => $shop->shop->shop_name ?? 'N/A',
            'products'        => $shop->products->map(function ($p) {
                return [
                    'title' => $p->title,
                    'qty'   => $p->qty,
                ];
            }),
            'total_items'     => $shop->products->count(),
            'item_descriptions' => $shop->products->map(function ($p) {
                return "{$p->title} ({$p->qty})";
            }),
            'payment_details' => [ 'payment_type' => $shop->payment_type, 'amount' => $shop->amount ],
            'delivery_date'   => $deliverySchedule->delivery_date ?? 'N/A',
            'vehicle'         => $deliverySchedule->vehicle ?? 'N/A',
            'driver'          => [
                'name'  => $driver->name ?? 'N/A',
                'phone' => $driver->phone ?? 'N/A'
            ],
            'otp'             => $shop->otp ?? 'N/A',
            'delivery_image' => $shop->getFirstMediaUrl('delivery-image'),
            'signature'      => $shop->getFirstMediaUrl('signature'),
            'route_map_image' => $shop->getFirstMediaUrl('route_map_image'),

        ];
    
        return response()->json([
            'response' => true,
            'message' => 'Delivery invoice fetched successfully.',
            'data' => $invoiceData,
            'extra' => [
                'shop' => $shop,
                'driver' => $driver,
                'driver_details' => $driver_details
            ]
        ], 200);
    }





}
