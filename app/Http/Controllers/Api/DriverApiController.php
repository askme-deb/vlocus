<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\VehicleType;
use App\Models\User;
use App\Models\Driver;
use App\Models\Company;
use App\Models\Vehicle;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\BusStop;
use App\Models\Brand;
use App\Models\Models;
use App\Models\BookingRequest;
use App\Models\DriverLocation;

use Illuminate\Support\Facades\DB;

use App\Models\BookingRequestRejected;

use Illuminate\Support\Facades\Http;

use App\Models\SOSAlert;
use App\Models\LoginLog;

class DriverApiController extends Controller
{

    public function store(Request $request)
    {
        // return $request->all();
        // d($request->all());
        // Validation Rules
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'nullable|email|unique:users,email',
            'gender' => 'required|in:male,female,others',
            'phone' => 'required|digits:10|regex:/^[6789]/|unique:users,phone',
            'aadhaar_number' => 'nullable|digits:12|unique:users,aadhar_card_number',
            'pan_card_number' => 'nullable|regex:/[A-Z]{5}[0-9]{4}[A-Z]{1}/|unique:users,pan_card_number',
            'driving_license_number' => 'required|unique:drivers,driving_license_number',
            'vehicle_type' => 'nullable',
            'password' => 'nullable|min:8',

            // 'profile_image' => ['nullable', 'regex:/^data:image\/(jpeg|png|jpg);base64,/', function ($attribute, $value, $fail) {
            'profile_image' => ['nullable', function ($attribute, $value, $fail) {
                if (!empty($value)) {
                    $size = (int)(strlen($value) * 3 / 4); // estimate size in bytes
                    if ($size > 2 * 1024 * 1024) {
                        $fail('The Profile Image must not be larger than 2 MB.');
                    }
                }
            }],
            // 'aadhar_image' => ['nullable', 'regex:/^data:image\/(jpeg|png|jpg);base64,/', function ($attribute, $value, $fail) {
            'aadhar_image' => ['nullable', function ($attribute, $value, $fail) {
                if (!empty($value)) {
                    $size = (int)(strlen($value) * 3 / 4);
                    if ($size > 2 * 1024 * 1024) {
                        $fail('The Aadhar Image must not be larger than 2 MB.');
                    }
                }
            }],
            // 'pan_image' => ['nullable', 'regex:/^data:image\/(jpeg|png|jpg);base64,/', function ($attribute, $value, $fail) {
            'pan_image' => ['nullable', function ($attribute, $value, $fail) {
                if (!empty($value)) {
                    $size = (int)(strlen($value) * 3 / 4);
                    if ($size > 2 * 1024 * 1024) {
                        $fail('The Pan Image must not be larger than 2 MB.');
                    }
                }
            }],
            // 'driver_license_image' => ['nullable', 'regex:/^data:image\/(jpeg|png|jpg);base64,/', function ($attribute, $value, $fail) {
            'driver_license_image' => ['nullable', function ($attribute, $value, $fail) {
                if (!empty($value)) {
                    $size = (int)(strlen($value) * 3 / 4);
                    if ($size > 10 * 1024 * 1024) {
                        $fail('The Driver License Image must not be larger than 10 MB.');
                    }
                }
            }],
        ], [
            'profile_image.regex' => 'The Profile Image must be a valid base64 encoded JPEG, PNG, or JPG.',
            'aadhar_image.regex' => 'The Aadhar Image must be a valid base64 encoded JPEG, PNG, or JPG.',
            'pan_image.regex' => 'The Pan Image must be a valid base64 encoded JPEG, PNG, or JPG.',
            'driver_license_image.regex' => 'The Driver License Image must be a valid base64 encoded JPEG, PNG, or JPG.',
        ]);

        // Validation Failed
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // return $request->vehicle_registration;

        // User Data
        $userData = [ 
            'status' => $request->status ?? 1,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'date_of_birth' => format_date_for_db($request->date_of_birth),
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
            'opt_mobile_no' => $request->opt_mobile_no,
            'address' => $request->address,
            'password' => bcrypt($request->password),
            'aadhar_card_number' => $request->aadhaar_number,
            'pan_card_number' => $request->pan_card_number,
        ];

        // Create User
        $user = User::create($userData);

        // Assign Role (Fix)
       
        $user->syncRoles('Driver');
       
       

        // Handle Profile Image
        if ($request->has('profile_image')) {
            // $user->addMedia($request->file('profile_image'))->toMediaCollection('system-user-image');
            $base64Image = $request->input('profile_image');
        
            $user->addMediaFromBase64($base64Image)
                ->usingFileName(Str::random(10).'.png')
                ->toMediaCollection('system-user-image');
        }

        if ($request->has('aadhar_image')) {
            // $user->addMedia($request->file('aadhar_image'))->toMediaCollection('system-user-aadhar');
            $base64Image = $request->input('aadhar_image');
        
            $user->addMediaFromBase64($base64Image)
                ->usingFileName(Str::random(10).'.png')
                ->toMediaCollection('system-user-aadhar');
        }

        if ($request->has('pan_image')) {
            // $user->addMedia($request->file('pan_image'))->toMediaCollection('system-user-pan');
            $base64Image = $request->input('pan_image');
        
            $user->addMediaFromBase64($base64Image)
                ->usingFileName(Str::random(10).'.png')
                ->toMediaCollection('system-user-pan');
        }


        if ($user) {
                  // Create Driver Record
            $driverData = [
                'user_id' => $user->id,
                'driving_license_number' => $request->driving_license_number,
                'vehicle_type' => $request->vehicle_type,
                'driving_exprience' => $request->driving_exprience,
                'vehicle_id' => $request->vehicle_id,
            ];
            // $authUser = auth()->user();
    
            // if ($authUser->hasRole('Super Admin')) {
            //     $driverData['company_id'] = $request->company_id; 
            // } elseif ($authUser->hasRole('Company')) {
            //     $driverData['company_id'] = $authUser->id; 
            // }
            
            $driver = Driver::create($driverData);
        }


        if ($request->has('driver_license_image')) {
            // $driver->addMedia($request->file('driver_license_image'))->toMediaCollection('driver-license');
            $base64Image = $request->input('driver_license_image');
        
            $driver->addMediaFromBase64($base64Image)
                ->usingFileName(Str::random(10).'.png')
                ->toMediaCollection('driver-license');
        }


        $data = $request->vehicle_registration;
        // return $data['name'];
        // return $data['vehicles_image'];


        $data=[
            'name'             => $data['name']??'',
            'vehicle_number'   => $data['vehicle_number']??'',
            'rwc_number'       => $data['rwc_number']??'',
            'engine_number'    => $data['engine_number']??'',
            'brand_id'         => $data['brand_id']??'',
            'model_id'         => $data['model_id']??'',
            'is_visible'       => 1,
            'vehicle_type'     => $data['vehicle_type']??'',
            'ac_status'        => $data['ac_status']??''
        ];

        $vehicle = Vehicle::create($data);

        if ($request->has('vehicle_registration.vehicles_image')) {
            $base64Image = $request->input('vehicle_registration.vehicles_image');
        
            $vehicle->addMediaFromBase64($base64Image)
                ->usingFileName(Str::random(10).'.png')
                ->toMediaCollection('vehicles');
        }

        $vehicle->update();

        $driver->vehicle_id = $vehicle->id;
        $driver->update();

        if($driver){
            return response()->json([
                'response' => true,
                'message' => 'Driver Added Successfully',
                'data' => [
                    'driver' => $user->load('driver.vehicleType')
                ],
            ]);
        }else{
            return response()->json([
                'response' => false,
                'message' => 'Driver Not Added',
                'data' => [
                    'driver' => []
                ],
            ]);
        }
    }
    
    
    
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|digits:10|regex:/^[6789]/'
        ]);

        // d($request->all());

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::where('phone', $request->phone_number)->first();

        if ($user) {

           if (!($user->hasRole('Driver'))) {
                return response()->json([
                    'response' => false,
                    'message' => 'Access denied: Only drivers  person are allowed to log in.',
                    'data' => []
                ], 403);
            }
            if ($request->otp) {
                return $this->verifyOTP($request);
            } else {
                return $this->sendNewOTP($user->phone);
            }
        } else {
            return response()->json([
                'response' => false,
                'message' => 'Please register your account.',
                'data' => [
                    
                ],
            ]);
  
            if ($request->otp) {
                // return response()->json(['error' => 'Unauthorized: Please register your account.'], 401);
                return response()->json([
                    'response' => false,
                    'message' => 'Unauthorized: Please register your account.',
                    'data' => [
                        
                    ],
                ]);
            } else {
                return $this->register($request->phone_number);
                // return response()->json(['error' => 'Please register your account.'], 401);
                return response()->json([
                    'response' => false,
                    'message' => 'Please register your account.',
                    'data' => [
                        
                    ],
                ]);
            }
        }
    }

    protected function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|digits:10|regex:/^[6789]/',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::where('phone', $request->phone_number)->first();

        if (!$user) {
            // return response()->json(['Unauthorized' => 'Please register your account.'], 401);
            return response()->json([
                'response' => false,
                'message' => 'Please register your account.',
                'data' => [
                    
                ],
            ]);
        }

        if (!$user || $user->otp != $request->otp) {
            // return response()->json(['error' => 'Invalid OTP'], 401);
            return response()->json([
                'response' => false,
                'message' => 'Invalid OTP',
                'data' => [
                    
                ],
            ]);
        }

      
        $otpCreatedAt = Carbon::parse($user->otp_timestamp);
        $currentTime = Carbon::now(); 
        $otpValidityDuration = 1;

        if ($otpCreatedAt->diffInMinutes($currentTime) > $otpValidityDuration) {
            return response()->json(['error' => 'OTP has expired.'], 401);
        }

        $user->tokens()->delete();
        $token = $user->createToken('AuthToken')->plainTextToken;
        // $user->fcm_token = $request->fcm_token;
        $user->device_id = $request->device_id;
        $user->save();

        if ($user->hasRole('Driver')) {
            $driver = Driver::firstOrCreate(
                ['user_id' => $user->id],
                ['is_online' => 1]
            );
            if (!$driver->wasRecentlyCreated && $driver->is_online == 0) {
                $driver->is_online = 1;
                $driver->save();
            }
            $user->load('driver.vehicle.vehicleType');
        }
        
        
        LoginLog::create([
            'user_id' => $user->id, // not Auth::id()
            'device' => $request->header('User-Agent'),
            'ip_address' => $request->ip(),
            'status' => 'login',
        ]);


        return response()->json(
            [
            'message' => 'Login successful', 
            'token' => $token ,
            'user' => $user,
            'role' => $user->getRoleNames()->first()
            ]);
    }

    protected function sendNewOTP($phoneNumber)
    {

        // $otp = 1234;
        $otp = rand(1000, 9999);
        $user = User::where('phone', $phoneNumber)->first();
        $user->tokens()->delete(); 
        $token = $user->createToken('AuthToken')->plainTextToken;
        $user->otp= $otp;
        $user->otp_timestamp= Carbon::now();
        $user->save();
        
        // $phoneNumber=9547480822;
        
    
        $response_sms= $this->sendOtpMessage($phoneNumber, $otp);
        $response_sms = [];
    
        return response()->json([
            'status' => true,
            'message' => 'New OTP generated successfully.',
            'sent' => 'An OTP has been sent to your phone number.',
            'note' => 'OTP is valid for 1 minute.',
            'token' => $token, 
            'response_sms'=>$response_sms,
        ]);
    }
    
    

    public function sendOTP(Request $request)
    {
      
        $request->validate([
            'mobile_number' => 'required|numeric|digits:10' 
        ]);
        $mobileNumber = $request->input('mobile_number');
        // $otp = rand(1000, 9999);
        $otp = 1234;
        return response()->json([
            'response' => true,
            'message' => 'OTP sent successfully',
            'otp' => $mobileNumber,
        ]);
    
       $userDetails= User::where('mobile_number',$mobileNumber)->first();
       if ($userDetails){
            $User=User::find($userDetails->id);
            $User->otp=$otp;
            $User->otp_timestamp= Carbon::now();
            $User->save();
            $response_sms= $this->sendOtpMessage($mobileNumber, $otp);
            return response()->json([
                'response' => true,
                'message' => 'OTP sent successfully',
                'otp' => $otp,
            ]);
        }else{
            $user = new User();

            $user->mobile_number = $request->input('mobile_number');
            $user->otp = $otp;
            $user->otp_timestamp = Carbon::now();
            $user->save();
            $response_sms= $this->sendOtpMessage($mobileNumber, $otp);
            return response()->json([
                'response' => true,
                'message' => 'OTP sent successfully',
                'otp' => $otp,
            ]);
        }
        
    }
    
    
      public function sendOtpMessage($mobile, $otp)
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
    
    


    
    
    

    public function update(Request $request)
    {
        $user = User::findOrFail($request->user()->id); 
        // return $user;
        $userId = $user->id;
        // return $request->all();
        // d($user->driver);
        // Validation Rules
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => "nullable|email|unique:users,email,".$userId,
            'gender' => 'required|in:male,female,others',
            'phone' => "required|digits:10|regex:/^[6789]/|unique:users,phone,".$userId,
            'aadhaar_number' => "nullable|digits:12|unique:users,aadhar_card_number,".$userId,
            'pan_card_number' => "nullable|regex:/[A-Z]{5}[0-9]{4}[A-Z]{1}/|unique:users,pan_card_number,".$userId,
            'driving_license_number' => "required|unique:drivers,driving_license_number,". optional($user->driver)->id,

            'password' => 'nullable|min:8',

            // 'profile_image' => ['nullable', 'regex:/^data:image\/(jpeg|png|jpg);base64,/', function ($attribute, $value, $fail) {
            'profile_image' => ['nullable', function ($attribute, $value, $fail) {
                if (!empty($value)) {
                    $size = (int)(strlen($value) * 3 / 4); // estimate size in bytes
                    if ($size > 2 * 1024 * 1024) {
                        $fail('The Profile Image must not be larger than 2 MB.');
                    }
                }
            }],
            // 'aadhar_image' => ['nullable', 'regex:/^data:image\/(jpeg|png|jpg);base64,/', function ($attribute, $value, $fail) {
            'aadhar_image' => ['nullable', function ($attribute, $value, $fail) {
                if (!empty($value)) {
                    $size = (int)(strlen($value) * 3 / 4);
                    if ($size > 2 * 1024 * 1024) {
                        $fail('The Aadhar Image must not be larger than 2 MB.');
                    }
                }
            }],
            // 'pan_image' => ['nullable', 'regex:/^data:image\/(jpeg|png|jpg);base64,/', function ($attribute, $value, $fail) {
            'pan_image' => ['nullable', function ($attribute, $value, $fail) {
                if (!empty($value)) {
                    $size = (int)(strlen($value) * 3 / 4);
                    if ($size > 2 * 1024 * 1024) {
                        $fail('The Pan Image must not be larger than 2 MB.');
                    }
                }
            }],
            // 'driver_license_image' => ['nullable', 'regex:/^data:image\/(jpeg|png|jpg);base64,/', function ($attribute, $value, $fail) {
            'driver_license_image' => ['nullable', function ($attribute, $value, $fail) {
                if (!empty($value)) {
                    $size = (int)(strlen($value) * 3 / 4);
                    if ($size > 2 * 1024 * 1024) {
                        $fail('The Driver License Image must not be larger than 2 MB.');
                    }
                }
            }],
        ], [
            'profile_image.regex' => 'The Profile Image must be a valid base64 encoded JPEG, PNG, or JPG.',
            'aadhar_image.regex' => 'The Aadhar Image must be a valid base64 encoded JPEG, PNG, or JPG.',
            'pan_image.regex' => 'The Pan Image must be a valid base64 encoded JPEG, PNG, or JPG.',
            'driver_license_image.regex' => 'The Driver License Image must be a valid base64 encoded JPEG, PNG, or JPG.',
        ]);

        // Validation Failed
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // User Data
        $userData = [ 
            'status' => $request->status ?? $user->status,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'date_of_birth' => format_date_for_db($request->date_of_birth),
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
            'opt_mobile_no' => $request->opt_mobile_no,
            'address' => $request->address,
            'password' => $request->filled('password') ? bcrypt($request->password) : $user->password,
            'aadhar_card_number' => $request->aadhaar_number,
            'pan_card_number' => $request->pan_card_number,
        ];
        
       

        // Create User
        $user->update($userData);

        // Assign Role (Fix)
        // $user->syncRoles('Driver');

        // Handle Profile Image
        if ($request->has('profile_image')) {
            // $user->addMedia($request->file('profile_image'))->toMediaCollection('system-user-image');
            $base64Image = $request->input('profile_image');
        
            $user->clearMediaCollection('system-user-image');
            $user->addMediaFromBase64($base64Image)
                ->usingFileName(Str::random(10).'.png')
                ->toMediaCollection('system-user-image');
        }

        if ($request->has('aadhar_image')) {
            // $user->addMedia($request->file('aadhar_image'))->toMediaCollection('system-user-aadhar');
            $base64Image = $request->input('aadhar_image');
        
            $user->clearMediaCollection('system-user-aadhar');
            $user->addMediaFromBase64($base64Image)
                ->usingFileName(Str::random(10).'.png')
                ->toMediaCollection('system-user-aadhar');
        }

        if ($request->has('pan_image')) {
            // $user->addMedia($request->file('pan_image'))->toMediaCollection('system-user-pan');
            $base64Image = $request->input('pan_image');
        
            $user->clearMediaCollection('system-user-pan');
            $user->addMediaFromBase64($base64Image)
                ->usingFileName(Str::random(10).'.png')
                ->toMediaCollection('system-user-pan');
        }

        $driver_id = $user->driver->id;

        $driver = Driver::findOrFail($driver_id); 

     
       
        if ($driver) {
            $driver->update([
                'driving_license_number' => $request->driving_license_number,
                'driving_exprience' => $request->driving_exprience,
            ]);
        } else {
            $driver = Driver::create([
                'user_id' => $user->id,
                'driving_license_number' => $request->driving_license_number,
                'vehicle_type' => $request->vehicle_type,
                'driving_exprience' => $request->driving_exprience,
                'vehicle_id' => $request->vehicle_id,
            ]);
        }



        if ($request->has('driver_license_image')) {
            // $driver->addMedia($request->file('driver_license_image'))->toMediaCollection('driver-license');
            $base64Image = $request->input('driver_license_image');
        
            $driver->clearMediaCollection('driver-license');
            $driver->addMediaFromBase64($base64Image)
                ->usingFileName(Str::random(10).'.png')
                ->toMediaCollection('driver-license');
        }
        
       

        if($driver){
            return response()->json([
                'response' => true,
                'message' => 'Driver Updated Successfully',
                'data' => [
                    'driver' => $user->load('driver'),
                    'profile_image'=> $user->getFirstMediaUrl('system-user-image') ? $user->getFirstMediaUrl('system-user-image') : NULL,
                    'driver_license_image'=> $driver->getFirstMediaUrl('driver-license') ? $driver->getFirstMediaUrl('driver-license') : NULL,
                ],
            ]);
        }else{
            return response()->json([
                'response' => false,
                'message' => 'Driver Not Updated',
                'data' => [
                    'driver' => []
                ],
            ]);
        }
    }
    
    public function vehicle_update(Request $request){
        
        
     
         
        if ($request->isMethod('post')) {
         
            $vehicle = Vehicle::findOrFail($request->vehicle_id); 
            $data = $request->vehicle_registration;
            $vehicle->name = $request->name;
            $vehicle->vehicle_number   = $request->vehicle_number;
            $vehicle->rwc_number       = $request->rwc_number;
            $vehicle->engine_number    = $request->engine_number;
            $vehicle->brand_id         = $request->brand_id;
            $vehicle->model_id         = $request->model_id;
            $vehicle->is_visible       = 1;
            $vehicle->vehicle_type     = $request->vehicle_type;
            $vehicle->ac_status        = $request->ac_status;
        
            if ($request->has('vehicles_image')) {
                $base64Image = $request->input('vehicles_image');
            
                $vehicle->clearMediaCollection('vehicles');
                $vehicle->addMediaFromBase64($base64Image)
                    ->usingFileName(Str::random(10).'.png')
                    ->toMediaCollection('vehicles');
            }
    
    
            $vehicle->save();

            
             return response()->json([
                'response' => true,
                'message' => 'Vehicle Updated Successfully',
                'data' => [
                    'vehicle' => $vehicle,
                    'vehicles_image'=> $vehicle->getFirstMediaUrl('vehicles') ? $vehicle->getFirstMediaUrl('vehicles') : NULL,
                    
                ],
            ]);
        }
         
         
    }
     

    public function get_driver_data(Request $request){
        $user = User::findOrFail($request->user()->id); 
        $firstImageUrl = $user->getFirstMediaUrl('system-user-image');
        $aadhar_image = $user->getFirstMediaUrl('system-user-aadhar');
        $pan_image = $user->getFirstMediaUrl('system-user-pan');
       
       
        
        $vehicle = Vehicle::findOrFail($user->driver->vehicle_id); 
        $driver_vehicle_image = $vehicle->getFirstMediaUrl('vehicles');
        
        
        $driver_id = $user->driver->id;
        $driver = Driver::findOrFail($driver_id);
        $driver_license_image = $driver->getFirstMediaUrl('driver-license');
     
        
     
        return response()->json([
            'response' => true,
            'message' => 'Fatched Driver Data',
            'data' => [
                'driver_data' => $user->load('driver.vehicle.vehicleType'),
                'profile_image_url' => $firstImageUrl != "" ? $firstImageUrl : NULL,
                'aadhar_image_url' => $aadhar_image != "" ? $aadhar_image : NULL,
                'pan_image_url' => $pan_image != "" ? $pan_image : NULL,
                'driver_license_image_url' => $driver_license_image != "" ? $driver_license_image : NULL,
                'driver_vehicle_image'=>$driver_vehicle_image != "" ? $driver_vehicle_image : NULL,
            ],
        ]);
    }
    
    
    
    
    
    public function proceed_booking(Request $request)
    {
  
        $validator = Validator::make($request->all(), [
            'from_stop_id' => 'required',
            'to_stop_id' => 'required',
            'route_id' => 'required',
            'vehicle_id' => 'required',
        ]);      
        if ($validator->fails()) {
       
            return response()->json(['response' => false, 'message' => $validator->errors()], 422);
  
        }
        // $wallet = Wallet::where('user_id', $request->user()->id)->first();

        // if ($wallet && $wallet->balance >= $request->totalAmount){

            $booking_number = generateBookingNumber();
            $booking = Booking::create([
                'booking_number'=> $booking_number,
                'user_id'=>$request->user()->id,
                'vehicle_id'=>$request->vehicle_id,
                'route_id'=>$request->route_id,
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
                'user_id' => $request->user()->id,
                'booking_id' => $booking->id,
                'amount' => $request->totalAmount,
                'debit' => $request->totalAmount,
                'payment_id' =>'788955' ,
                'type' => 'debit',
                'payment_method' => 'wallet',
                'payment_status' => 'completed',
                'remarks' => 'Ticket Booking via wallet',
            ]);

    

            $booked_seats = BookingSeat::where('booking_id',$booking->id)->pluck('seat_number')->toArray();
        

            return response()->json([
                'response' => true,
                'message' => 'Booking confirm',
                'data' => [
                'booking_details' => $booking,
                'booked_seats' => $booked_seats,
                ],
            ]);
        // }else{
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Please Recharge your wallet',
        //     ]);
        // }


    }
    
    
    public function bus_stop_search(Request $request)
    {
        $search = $request->name;

        $results = BusStop::where('name', 'LIKE', '%' . $search . '%')
            ->take(10)
            ->get(['id', 'name','latitude','longitude']);
            
            
        // return response()->json([
        //     'response' => true,
        //     'message' => 'Get all active busstops',
        //     'data' => [
        //         'bus_stops' => $results,
        //     ],
        // ]);

        return response()->json($results);
    }
    

    
    public function get_all_brand()
    {
        $results = Brand::where('is_visible', 1)->get(['id', 'name']);
    
        if ($results->isNotEmpty()) {
            return response()->json([
                'response' => true,
                'message' => 'Get all active brands',
                'data' => [
                    'brands' => $results,
                ],
            ]);
        } else {
            return response()->json([
                'response' => false,
                'message' => 'No brand found',
                'data' => [
                    'brands' => [],
                ],
            ]);
        }
    }

    public function get_brand_model($brand_id)
    {
        $results = Models::where('brand_id', $brand_id)
            ->get(['id', 'name', 'brand_id']);
    
        if ($results->isNotEmpty()) {
            return response()->json([
                'response' => true,
                'message' => 'Get brand associated models',
                'data' => [
                    'models' => $results,
                ],
            ]);
        } else {
            return response()->json([
                'response' => false,
                'message' => 'No models found for this brand',
                'data' => [
                    'models' => [],
                ],
            ]);
        }
    }
    
    
    public function update_driver_location(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['response' => false, 'errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        if (!$user->hasRole('Driver')) {
            return response()->json([
                'response' => false,
                'message' => 'Only drivers can update location.',
            ], 403);
        }

        $driver = Driver::firstOrCreate(
            ['user_id' => $user->id],
            ['latitude' => $request->latitude, 'longitude' => $request->longitude]
        );
        if (!$driver->wasRecentlyCreated) {
            $driver->latitude = $request->latitude;
            $driver->longitude = $request->longitude;
            $driver->save();
        }

        return response()->json([
            'response' => true,
            'message' => 'Driver location updated successfully.',
            'data' => [
                'driver' => $driver,
            ],
        ]);
    }
    
    public function get_driver_current_location(Request $request,string $driver_id){
        $driver = Driver::where('user_id',$driver_id)->first();
        
        if($driver){
            return response()->json([
                'response' => true,
                'message' => 'Driver location fetch successfully.',
                'data' => [
                    'latitude' => $driver->latitude,
                    'longitude' => $driver->longitude
                ],
            ]);
        }else{
            return response()->json([
                'response' => false,
                'message' => 'Driver location not fetch.',
                'data' => [],
            ]);   
        }

    }
    
    public function update_ride_mode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'ride_mode' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['response' => false, 'errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        if (!$user->hasRole('Driver')) {
            return response()->json([
                'response' => false,
                'message' => 'Only drivers can update ride mode.',
            ], 403);
        }

        $driver = Driver::firstOrCreate(
            ['user_id' => $user->id],
            ['latitude' => $request->latitude, 'longitude' => $request->longitude],
            ['ride_mode' => $request->ride_mode],
        );
        if (!$driver->wasRecentlyCreated) {
            $driver->latitude = $request->latitude;
            $driver->longitude = $request->longitude;
            $driver->ride_mode = $request->ride_mode;
            $driver->save();
        }

        return response()->json([
            'response' => true,
            'message' => 'Driver mode updated successfully.',
            'data' => [
                'driver' => $driver,
            ],
        ]);
    }
    
    
    
    public function getBookingRequest($id, Request $request)
    {
        $booking = BookingRequest::where('id', $id)
            ->where('status', 'pending')
            ->first();
    
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking request not found or already handled.'
            ]);
        }
    
        return response()->json([
            'success' => true,
            'booking' => [
                'id' => $booking->id,
                'pickup_lat' => $booking->pickup_lat,
                'pickup_lng' => $booking->pickup_lng,
                'drop_lat' => $booking->drop_lat,
                'drop_lng' => $booking->drop_lng,
                'amount' => $booking->amount,
                'status' => $booking->status,
                'type' => $booking->type,
                'user' => [
                    'id' => $booking->user->id ?? null,
                    'name' => $booking->user->name ?? null,
                    'phone' => $booking->user->phone ?? null,
                ]
            ]
        ]);
    }






// public function getAllBookingRequest(Request $request)
// {
//     $driverId = $request->user()->id;

//     $rejectedBookingIds = BookingRequestRejected::where('driver_id', $driverId)
//         ->pluck('booking_request_id');

//     $bookings = BookingRequest::with('user')
//         ->where('status', 'pending')
//         ->whereDate('created_at', Carbon::today())
//         ->whereNotIn('id', $rejectedBookingIds)
//         ->get();

//     if ($bookings->isEmpty()) {
//         return response()->json([
//             'success' => false,
//             'message' => 'No pending booking requests found.'
//         ]);
//     }

//     $bookingData = $bookings->map(function ($booking) {
//         return [
//             'id' => $booking->id,
//             'pickup_lat' => $booking->pickup_lat,
//             'pickup_lng' => $booking->pickup_lng,
//             'drop_lat' => $booking->drop_lat,
//             'drop_lng' => $booking->drop_lng,
//             'amount' => $booking->amount,
//             'status' => $booking->status,
//             'type' => $booking->type,
//             'user' => [
//                 'id' => $booking->user->id ?? null,
//                 'name' => $booking->user->name ?? null,
//                 'phone' => $booking->user->phone ?? null,
//             ]
//         ];
//     });

//     return response()->json([
//         'success' => true,
//         'bookings' => $bookingData
//     ]);
// }



public function getAllBookingRequest(Request $request)
{
    $driverId = $request->user()->id;

    // ✅ Step 1: Check if the driver has an active (not completed) ride
    $hasOngoingRide = Booking::where('driver_id', $driverId)
        ->where('is_journey_completed', false)
        ->whereIn('status', ['ongoing', 'accepted']) // status can vary based on your system
        ->exists();

    if ($hasOngoingRide) {
        return response()->json([
            'success' => false,
            'message' => 'You have an active ride. Complete it before accepting new requests.'
        ]);
    }

    // ✅ Step 2: Get all booking requests this driver rejected
    $rejectedBookingIds = BookingRequestRejected::where('driver_id', $driverId)
        ->pluck('booking_request_id');

    // ✅ Step 3: Get today's pending booking requests excluding rejected ones
    $bookings = BookingRequest::with('user')
        ->where('status', 'pending')
        ->whereDate('created_at', now()->toDateString())
        ->whereNotIn('id', $rejectedBookingIds)
        ->get();

    if ($bookings->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No pending booking requests found.'
        ]);
    }

    // ✅ Step 4: Format for API response
    $bookingData = $bookings->map(function ($booking) {
        return [
            'id' => $booking->id,
            'pickup_lat' => $booking->pickup_lat,
            'pickup_lng' => $booking->pickup_lng,
            'drop_lat' => $booking->drop_lat,
            'drop_lng' => $booking->drop_lng,
            'amount' => $booking->amount,
            'status' => $booking->status,
            'type' => $booking->type,
            'user' => [
                'id' => $booking->user->id ?? null,
                'name' => $booking->user->name ?? null,
                'phone' => $booking->user->phone ?? null,
            ]
        ];
    });

    return response()->json([
        'success' => true,
        'bookings' => $bookingData
    ]);
}


    
    
    public function acceptBookingRequest(Request $request)
    {
        $request->validate([
            'booking_request_id' => 'required|exists:booking_requests,id',
        ]);

        $bookingRequest = BookingRequest::find($request->booking_request_id);

        if ($bookingRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Booking request already accepted or no longer available.',
            ]);
        }

        DB::beginTransaction();

        try {
            $bookingRequest = BookingRequest::lockForUpdate()->find($request->booking_request_id);

            // Check if already accepted
            if ($bookingRequest->status === 'accepted') {
                return response()->json([
                    'success' => false,
                    'message' => 'This ride request has already been accepted.',
                ], 409);
            }

            // Mark request as accepted by driver
            $bookingRequest->update([
                'driver_id' => $request->user()->id,
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);

            $driver= Driver::where('user_id',$request->user()->id)->first();
            // Generate OTPs
            // $pickupOtp = rand(100000, 999999);
            // $dropOtp = rand(100000, 999999);
             $dropOtp = 1234;
            $pickupOtp = 1234;
            // Create booking
            $booking = Booking::create([
                'booking_number' => generateBookingNumber(),
                'user_id' => $bookingRequest->user_id,
                'driver_id'=>$request->user()->id,
                'vehicle_id' => $driver->vehicle_id,
                'booking_request_id' => $bookingRequest->id,
                'total_amount' => 200,
                'status' => 'accepted',
                'booking_date' => now(),
                'booking_type' => 'cab',
                'otp_pickup' => $pickupOtp,
                'otp_drop' => $dropOtp, 
                
            ]);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ride request accepted successfully',
                'booking' => $booking,
                'booking_request'=>$bookingRequest
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to accept ride request',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    
     public function verifyPickupOtp(Request $request)
    {
        // $request->validate([
        //     'booking_id' => 'required|exists:bookings,id',
        //     'pickup_otp' => 'required|string',
        // ]);
        
        // d($request->all());


        DB::beginTransaction();

        try {
            $booking= Booking::find($request->booking_id);
            $bookingRequest = BookingRequest::lockForUpdate()->find($booking->booking_request_id);
            
            // d($booking);
            // d($request->pickup_otp);

            if ($bookingRequest->status === 'started') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ride has already started.',
                ], 409);
            }

            if ($booking->otp_pickup !== $request->pickup_otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP.',
                ], 401);
            }

            // Update RideRequest status and timestamp
            $bookingRequest->update([
                'status' => 'started',
                // 'started_at' => now(),
            ]);

            // Optional: If booking is linked, update it too

            $booking->update([
                'pickup_time' => now(),
                'status' => 'ongoing',
            ]);
            

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pickup OTP verified. Ride started.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while verifying OTP.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    
    
     public function verifyDropOtp(Request $request)
    {
        // $request->validate([
        //     'booking_id' => 'required|exists:bookings,id',
        //     'pickup_otp' => 'required|string',
        // ]);
        
        // d($request->all());


        DB::beginTransaction();

        try {
            $booking= Booking::find($request->booking_id);
            $bookingRequest = BookingRequest::lockForUpdate()->find($booking->booking_request_id);
            
            // d($booking);
            // d($request->pickup_otp);

            if ($bookingRequest->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ride has already completed.',
                ], 409);
            }

            if ($booking->otp_drop !== $request->drop_otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP.',
                ], 401);
            }

            // Update RideRequest status and timestamp
            $bookingRequest->update([
                'status' => 'completed',
                // 'started_at' => now(),
            ]);

            // Optional: If booking is linked, update it too

            $booking->update([
                'drop_time' => now(),
                'status' => 'completed',
                'is_journey_completed'=>1,
            ]);
            

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Drop OTP verified. Ride ended.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while verifying OTP.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    
    
     public function logout(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = User::find($request->user()->id);
    
            if ($user) {
                $driver = Driver::where('user_id', $user->id)->first();
    
                if ($driver) {
                    $driver->update([
                        'is_online' => 0,
                    ]);
                }
    
                // $request->user()->currentAccessToken()->delete();
                
                LoginLog::create([
                    'user_id' => $user->id,
                    'device' => $request->header('User-Agent'),
                    'ip_address' => $request->ip(),
                    'status' => 'logout',
                ]);
                $user->tokens()->delete();
    
                DB::commit();
    
                return response()->json([
                    'success' => true,
                    'message' => 'Logout successfully',
                ]);
            }
    
            DB::rollBack();
    
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
    
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    
    public function login_logs(Request $request)
    {
        $logs = LoginLog::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'response' => true,
            'message' => 'Login logs fetched successfully.',
            'data' => $logs
        ]);
    }
    
    
    
    
     public function rejectedBookingRequest(Request $request)
{
    $request->validate([
        'booking_request_id' => 'required|exists:booking_requests,id',
    ]);

    $bookingRequest = BookingRequest::find($request->booking_request_id);

    // ❌ Fix logic here: allow rejection only if status is 'pending'
    if ($bookingRequest->status !== 'pending') {
        return response()->json([
            'success' => false,
            'message' => 'Booking request already handled or not available for rejection.',
        ]);
    }

    DB::beginTransaction();

    try {
        $bookingRequest = BookingRequest::lockForUpdate()->find($request->booking_request_id);

        // Re-check inside transaction
        if ($bookingRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This ride request has already been handled.',
            ], 409);
        }

        // Update booking request status to rejected
        $bookingRequest->update([
            'driver_id' => $request->user()->id,
            'status' => 'rejected',
        ]);

        // Save rejection log
        $booking_request_rejected = BookingRequestRejected::create([
            'booking_request_id' => $bookingRequest->id,
            'user_id' => $bookingRequest->user_id,
            'driver_id' => $request->user()->id,
            'rejected_at' => now(),
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Ride request rejected successfully.',
            'booking_request' => $bookingRequest,
            'booking_request_rejected' => $booking_request_rejected
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Failed to reject ride request.',
            'error' => $e->getMessage(),
        ], 500);
    }
}



    public function update_current_location(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['response' => false, 'errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        if (!$user->hasRole('Driver')) {
            return response()->json([
                'response' => false,
                'message' => 'Only drivers can update cureent location.',
            ], 403);
        }
        
        $driver = Driver::where('user_id',$user->id)->first();
        $driver->latitude = $request->latitude;
        $driver->longitude = $request->longitude;
        $driver->save();
        
        DriverLocation::create([
            'driver_id' => $driver->id,
            'vehicle_id' => null,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'speed' => null,
            'timestamp' => now(),
        ]);


        return response()->json([
            'response' => true,
            'message' => 'Driver location updated successfully.',
            'data' => [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ],
        ]);
    }
    
    
    
    public function sendSOS(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'message' => 'nullable|string|max:500',
        ]);
    
        $user = $request->user();
    
        if (!$user->hasRole('Driver')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        // Optional: Save to DB
        SOSAlert::create([
            'driver_id' => $user->id,
            'message' => $request->message,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);
    
        // Get admin phone
        // $admin = User::role('Admin')->first(); // or loop through all admins
    
        // if ($admin && $admin->phone) {
        //     $sosMessage = "🚨 SOS ALERT 🚨\nDriver: {$user->name}\nPhone: {$user->phone}\n"
        //         . "Location: https://www.google.com/maps?q={$request->latitude},{$request->longitude}\n"
        //         . "Message: " . ($request->message ?? 'No message provided');
    
        //     $this->sendMessage($admin->phone, $sosMessage);
        // }
    
        return response()->json([
            'response' => true,
            'message' => 'SOS alert sent to admin.',
        ]);
    }


    

    
    

}