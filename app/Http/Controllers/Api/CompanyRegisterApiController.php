<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CompanyRegisterApiController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'required|digits:10|regex:/^[6789]/|unique:users,phone',
            'password'    => 'required|min:8',
            'status'      => 'required|in:1,0',
            'pan_card_number' => 'nullable|regex:/[A-Z]{5}[0-9]{4}[A-Z]{1}/|unique:users,pan_card_number',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pan_image'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Create User
        $user = User::create([
            'status'           => $request->status,
            'first_name'       => $request->company_name,
            'name'             => $request->company_name,
            'email'            => $request->email,
            'phone'            => $request->phone,
            'opt_mobile_no'    => $request->opt_mobile_no,
            'address'          => $request->address,
            'password'         => Hash::make($request->password),
            'pan_card_number'  => $request->pan_card_number,
        ]);

        // Assign Company Role
        $user->syncRoles('Company');

        // Upload profile image
        if ($request->hasFile('profile_image')) {
            $user->addMedia($request->profile_image)->toMediaCollection('system-user-image');
        }

        if ($request->hasFile('pan_image')) {
            $user->addMedia($request->pan_image)->toMediaCollection('system-user-pan');
        }

        // Create API Token
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Company registered successfully',
            'data'    => [
                'user'  => $user,
                'token' => $token,
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Login by Email or Phone
        $user = User::where('email', $request->email)
                    ->orWhere('phone', $request->email)
                    ->first();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found'
            ], 404);
        }

        // Check Password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Incorrect password'
            ], 401);
        }

        // Check user role
        if (!$user->hasRole('Company')) {
            return response()->json([
                'status'  => false,
                'message' => 'You are not authorized as Company'
            ], 403);
        }

        // Create token
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login successful',
            'data'    => [
                'user'  => $user,
                'token' => $token,
            ]
        ], 200);
    }

}
