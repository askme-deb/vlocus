<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{


    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|digits:10|regex:/^[6789]/'
        ]);

      //  d($request->all());

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::where('phone', $request->phone_number)->first();

        if ($user) {
            if ($request->otp) {
                return $this->verifyOTP($request);
            } else {
                return $this->sendNewOTP($user->phone);
            }
        } else {
  
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

    // protected function verifyOTP(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'phone_number' => 'required|digits:10|regex:/^[6789]/',
    //         'otp' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 422);
    //     }

    //     $user = User::where('phone', $request->phone_number)->first();

    //     if (!$user) {
    //         // return response()->json(['Unauthorized' => 'Please register your account.'], 401);
    //         return response()->json([
    //             'response' => false,
    //             'message' => 'Please register your account.',
    //             'data' => [
                    
    //             ],
    //         ]);
    //     }

    //     if (!$user || $user->otp != $request->otp) {
    //         // return response()->json(['error' => 'Invalid OTP'], 401);
    //         return response()->json([
    //             'response' => false,
    //             'message' => 'Invalid OTP',
    //             'data' => [
                    
    //             ],
    //         ]);
    //     }

      
    //     $otpCreatedAt = Carbon::parse($user->otp_timestamp);
    //     $currentTime = Carbon::now(); 
    //     $otpValidityDuration = 1;

    //     if ($otpCreatedAt->diffInMinutes($currentTime) > $otpValidityDuration) {
    //         return response()->json(['error' => 'OTP has expired.'], 401);
    //     }

    //     $user->tokens()->delete();
    //     $token = $user->createToken('AuthToken')->plainTextToken;

    //     if ($user->hasRole('Driver')) {
    //         $user->load('driver.vehicle.vehicleType');
    //     }

    //     return response()->json(
    //         [
    //         'message' => 'Login successful', 
    //         'token' => $token ,
    //         'user' => $user,
    //         'role' => $user->getRoleNames()->first()
    //         ]);
    // }
protected function verifyOTP(Request $request)
{
    Log::info('OTP Verification Request', [
        'phone' => $request->phone_number,
        'otp_entered' => $request->otp
    ]);

    $validator = Validator::make($request->all(), [
        'phone_number' => 'required|digits:10|regex:/^[6789]/',
        'otp' => 'required',
    ]);

    if ($validator->fails()) {
        Log::error('Validation Failed', $validator->errors()->toArray());
        return response()->json(['error' => $validator->errors()], 422);
    }

    $user = User::where('phone', $request->phone_number)->first();

    Log::info('User Found', [
        'user_id' => $user?->id,
        'db_phone' => $user?->phone,
        'db_otp' => $user?->otp
    ]);

    if (!$user) {
        Log::warning('User not found for phone', ['phone' => $request->phone_number]);

        return response()->json([
            'response' => false,
            'message' => 'Please register your account.',
            'data' => [],
        ]);
    }

    Log::info('OTP Comparison', [
        'entered_otp' => $request->otp,
        'db_otp' => $user->otp
    ]);

    $enteredOtp = is_array($request->otp) ? implode('', $request->otp) : trim((string) $request->otp);
    $storedOtp = trim((string) $user->otp);

    if ($storedOtp !== $enteredOtp) {

        Log::warning('Invalid OTP', [
            'entered' => $enteredOtp,
            'stored' => $storedOtp
        ]);

        return response()->json([
            'response' => false,
            'message' => 'Invalid OTP',
            'data' => [],
        ]);
    }

    $otpCreatedAt = Carbon::parse($user->otp_timestamp);
    $currentTime = Carbon::now();
    $otpValidityDuration = 1;

    Log::info('OTP Time Check', [
        'created_at' => $otpCreatedAt,
        'current_time' => $currentTime
    ]);

    if ($otpCreatedAt->diffInMinutes($currentTime) > $otpValidityDuration) {

        Log::warning('OTP Expired');

        return response()->json(['error' => 'OTP has expired.'], 401);
    }

    $user->tokens()->delete();
    $token = $user->createToken('AuthToken')->plainTextToken;

    if ($user->hasRole('Driver')) {
        $user->load('driver.vehicle.vehicleType');
    }

    Log::info('Login Successful', ['user_id' => $user->id]);

    return response()->json([
        'message' => 'Login successful',
        'token' => $token,
        'user' => $user,
        'role' => $user->getRoleNames()->first()
    ]);
}

    protected function register($phoneNumber)
    {

        $existingUser = User::where('phone', $phoneNumber)->first();
        if ($existingUser) {
            return response()->json(['error' => 'User already exists.'], 400);
        }
        $otp = 1234; 
        $user = User::create([
            'phone' => $phoneNumber,
            'otp' => $otp,
            'otp_timestamp' => Carbon::now(),
        ]);
        $user->syncRoles('Customer');
        $token = $user->createToken('AuthToken')->plainTextToken; 

        return response()->json([
            'status' => true,
            'message' => 'Your account has been created.',
            'sent' => 'OTP sent to your phone number.',
            'note' => 'OTP is valid for 1 minute.',
            'token' => $token,
        ]);
    }

    protected function sendNewOTP($phoneNumber)
    {

        $otp = 1234;
        $user = User::where('phone', $phoneNumber)->first();
        $user->tokens()->delete(); 
        $token = $user->createToken('AuthToken')->plainTextToken; 
        $user->otp=$otp;
        $user->otp_timestamp= Carbon::now();
        $user->save();
        
        
        $phone=9547480822;
        
      $message = "Your one time password is {$otp}. Please use this One Time Password (OTP) within the next ten minutes to proceed. Thank you. AGILTAS SOLUTION";

        // Send SMS
      $response_sms= $this->sendSms($phone, $message);
    
        return response()->json([
            'status' => true,
            'message' => 'New OTP generated successfully.',
            'sent' => 'An OTP has been sent to your phone number.',
            'note' => 'OTP is valid for 1 minute.',
            'token' => $token, 
            'response_sms'=>$response_sms,
        ]);
    }
    
    
    
      public function sendSms($mobile, $message)
    {
        $encodedMessage = urlencode($message);
        
        $response = Http::get('http://sms.cell24x7.com:1111/mspProducerM/sendSMS', [
            'user' => 'agiltas',
            'pwd' => 'apiagiltas',
            'sender' => 'AGILTS',
            'mobile' => $mobile,
            'msg' => $encodedMessage,
            'mt' => 0, // English
        ]);

        return $response->body(); // optional: log or handle response
    }


    public function sendOTP(Request $request)
    {
      
        $request->validate([
            'mobile_number' => 'required|numeric|digits:10' 
        ]);
        $mobileNumber = $request->input('mobile_number');
        $otp = 1234;
    
        $user = User::where('phone', $mobileNumber)->first();
        if ($user){
            $user->otp = $otp;
            $user->otp_timestamp = Carbon::now();
            $user->save();
            return response()->json([
                'response' => true,
                'message' => 'OTP sent successfully',
                'otp' => $otp,
            ]);
        }else{
            $user = new User();
            $user->phone = $mobileNumber;
            $user->otp = $otp;
            $user->otp_timestamp = Carbon::now();
            $user->save();
           
            return response()->json([
                'response' => true,
                'message' => 'OTP sent successfully',
                'otp' => $otp,
            ]);
        }
        
    }

    public function profile_update(Request $request)
    {

        if ($request->isMethod('post')) {
            $name = $request->name;
            $nameParts = explode(' ', $name, 2);
            $user=  User::find($request->user_id);
            $user->name = $request->name;
            $user->first_name = $nameParts[0];
            $user->last_name = $nameParts[1] ?? '';
            $user->date_of_birth = $request->dob ;
            $user->gender = $request->gender ;
            $user->email = $request->email ;
            $user->phone = $request->phone ;
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => $user,
                ],
            ]);
        }        
    }

    public function delete_user_account(Request $request){
        return response()->json([
            'response' => true,
            'message' => 'Abi Mat karo, Jab Dipankar sir bolega tab karo',
            'data' => [
                
            ],
        ]);
        $user = User::findOrFail($request->user()->id); 
        $user->delete();
        return response()->json([
            'response' => true,
            'message' => 'User account Deleted Successfully',
            'data' => [
                
            ],
        ]);
    }
    
}
