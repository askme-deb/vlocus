<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Wallet;

class UserController extends Controller
{
    
    public function profile()
    {
        $user = Auth::user();
        if($user->hasRole('Super Admin')){
            return redirect()->route('dashboard');
        }
        $bookings= Booking::where('user_id',$user->id)
        ->where('status', 'confirm')
        ->orderBy('created_at', 'desc')
        ->paginate(6);
        $wallet = Wallet::where('user_id', $user->id)->first();

        return view('frontend.auth.profile',compact('bookings','wallet'));
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
            ]);
        }        
    }


}
