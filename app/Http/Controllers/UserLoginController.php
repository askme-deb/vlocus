<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class UserLoginController extends Controller
{
    
    public function create()
    {
        return view('frontend.login');
    }

    public function sendOTP(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|numeric|digits:10' 
        ]);
        $mobileNumber = $request->input('mobile_number');
        $otp = 1234;
        $userDetails= User::where('phone',$mobileNumber)->first();
        if ($userDetails){
            $User=User::find($userDetails->id);
            $User->otp=$otp;
            $User->otp_timestamp= Carbon::now();
            $User->save();
            $User->syncRoles('Customer');
            $User->update();

        }else{

           $user= User::create([
                'phone' => $mobileNumber,
                'otp' => $otp,
                'otp_timestamp' => Carbon::now(),

            ]);
            $user->syncRoles('Customer');
            $user->update();
    
           
        }
        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
        ]);
      
    }

    // public function verifyOTP(Request $request)
    // {
    //     $request->validate([
    //         'mobile_number' => 'required|numeric|digits:10',
    //         'otp' => 'required|numeric|digits:4',
    //     ]);

    //     $mobileNumber = $request->input('mobile_number');
    //     $enteredOtp = $request->input('otp');
    //     $user = User::where('phone', $mobileNumber)->first();

    //     if (!$user) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Authentication failed, user not found.',
    //         ]);
    //     }

    //     if ($user->otp != $enteredOtp) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'OTP does not match!',
    //         ]);
    //     }
    //     $otpTimestamp = Carbon::parse($user->otp_timestamp);
    //     if ($otpTimestamp->addMinutes(1)->isPast()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'OTP has expired. Please request a new OTP.',
    //         ]);
    //     }
    //     Auth::login($user);
    //     $role = $user->getRoleNames()->first();
    //     $redirectUrl = route('dashboard');
    //     if ($role == 'User') {
    //         $redirectUrl = route('front.user.profile');
    //     } 
 

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Logged in successfully!',
    //         'redirect_url' => $redirectUrl,
    //     ]);
    // }
public function verifyOTP(Request $request)
{
    $request->validate([
        'mobile_number' => 'required|numeric|digits:10',
        'otp' => 'required|numeric|digits:4',
    ]);

    $mobileNumber = $request->input('mobile_number');
    $enteredOtp = $request->input('otp');

    Log::info('OTP Verify Request', [
        'mobile_number' => $mobileNumber,
        'entered_otp' => $enteredOtp
    ]);

    $user = User::where('phone', $mobileNumber)->first();

    if (!$user) {

        Log::warning('User not found for OTP verification', [
            'mobile_number' => $mobileNumber
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Authentication failed, user not found.',
        ]);
    }

    Log::info('User OTP Data', [
        'db_otp' => $user->otp,
        'entered_otp' => $enteredOtp,
        'otp_timestamp' => $user->otp_timestamp
    ]);

    if ($user->otp != $enteredOtp) {

        Log::error('OTP mismatch', [
            'db_otp' => $user->otp,
            'entered_otp' => $enteredOtp,
            'user_id' => $user->id
        ]);

        return response()->json([
            'success' => false,
            'message' => 'OTP does not match!',
        ]);
    }

    $otpTimestamp = Carbon::parse($user->otp_timestamp);

    if ($otpTimestamp->addMinutes(1)->isPast()) {

        Log::warning('OTP expired', [
            'user_id' => $user->id,
            'otp_timestamp' => $user->otp_timestamp
        ]);

        return response()->json([
            'success' => false,
            'message' => 'OTP has expired. Please request a new OTP.',
        ]);
    }

    Auth::login($user);

    $role = $user->getRoleNames()->first();
    $redirectUrl = route('dashboard');

    if ($role == 'User') {
        $redirectUrl = route('front.user.profile');
    }

    Log::info('OTP login success', [
        'user_id' => $user->id,
        'mobile_number' => $mobileNumber
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Logged in successfully!',
        'redirect_url' => $redirectUrl,
    ]);
}
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
   

}
