<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\VerifiesBankUIdentity;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use App\Models\Vehicle;
use App\Services\BankU\BankUIdentityService;


class BranchController extends Controller implements HasMiddleware
{
    use VerifiesBankUIdentity;

    public function __construct(private readonly BankUIdentityService $bankUIdentityService)
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Branch Show', only: ['index','show']),
            new Middleware('permission:Branch Create', only: [
                'create','store','verifyPan','sendAadhaarOtp','verifyAadhaarOtp',
            ]),
            new Middleware('permission:Branch Edit', only: ['edit','update']),
            new Middleware('permission:Branch Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        if (Auth::user()->hasRole('Company')) {
            // Show only branches of this company
            $branchs = User::role('Branch')
                ->whereHas('branch', function ($q) {
                    $q->where('company_id', Auth::id());
                })
                ->latest()
                ->get();
        } else {
            // Show all branches
            $branchs = User::role('Branch')
                ->latest()
                ->get();
        }

        return view('admin.branch.index', compact('branchs'));
}


    public function create()
    {
        return view('admin.branch.create');
    }
    

   
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            // 'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits:10|regex:/^[6789]/|unique:users,phone',
            'aadhaar_number' => 'nullable|digits:12|unique:users,aadhar_card_number',
            'pan_card_number' => 'nullable|regex:/[A-Z]{5}[0-9]{4}[A-Z]{1}/|unique:users,pan_card_number',
            // 'driving_license_number' => 'required|unique:drivers,driving_license_number',
            // 'vehicle_type' => 'required',
            'password' => 'nullable|min:8',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'aadhar_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pan_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // 'driver_license_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:1,0',
            'latitude' => 'required',
            'longitude' => 'required'
        ], [
            'profile_image.max' => 'The Profile Image must not be larger than 2 MB.',
            'aadhar_image.max' => 'The Aadhar Image must not be larger than 2 MB.',
            'pan_image.max' => 'The Pan Image must not be larger than 2 MB.',
        ]);

        // Validation Failed
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        // User Data
        $userData = [
            'status' => $request->status,
            'first_name' => $request->company_name,
            // 'last_name' => $request->last_name,
            'name' => $request->company_name,
            // 'date_of_birth' => format_date_for_db($request->date_of_birth),
            // 'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
            'opt_mobile_no' => $request->opt_mobile_no,
            'address' => $request->address,
            'password' => bcrypt($request->password) ?? null,
            'aadhar_card_number' => $request->aadhaar_number,
            'pan_card_number' => $request->pan_card_number,
        ];

        $aadhaarVerificationData = $this->decodeVerificationPayload($request, 'aadhaar_verification_data');
        if ($aadhaarVerificationData !== null) {
            $userData['aadhaar_verification_data'] = $aadhaarVerificationData;
            $userData['aadhaar_verified_at'] = now();
        }

        $panVerificationData = $this->decodeVerificationPayload($request, 'pan_verification_data');
        if ($panVerificationData !== null) {
            $userData['pan_verification_data'] = $panVerificationData;
            $userData['pan_verified_at'] = now();
        }

        // Create User
        $user = User::create($userData);

        // Assign Role (Fix)
        $user->syncRoles('Branch');

        $branch = Branch::create([
            'user_id' => $user->id,
            'company_id' => Auth::user()->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // Handle Profile Image
        if ($request->hasFile('profile_image')) {
            $user->addMedia($request->file('profile_image'))->toMediaCollection('system-user-image');
        }

        if ($request->hasFile('aadhar_image')) {
            $user->addMedia($request->file('aadhar_image'))->toMediaCollection('system-user-aadhar');
        }

        if ($request->hasFile('pan_image')) {
            $user->addMedia($request->file('pan_image'))->toMediaCollection('system-user-pan');
        }


        // if ($request->hasFile('driver_license_image')) {
        //     $driver->addMedia($request->file('driver_license_image'))->toMediaCollection('driver-license');
        // }

        return redirect()->route('branch.index')->with('success', $user ? 'Branch Created Successfully' : 'Branch Not Created');
    }


    public function show(User $user)
    {
        //
    }

    public function edit($id)
    {
        $user = User::find($id);
   
        return view('admin.branch.edit',compact('user'));
        
    }

    public function update(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            // 'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|email|unique:users,email,'. $user->id,
            'phone' => 'required|digits:10|regex:/^[6789]/|unique:users,phone,'. $user->id,
            // 'aadhaar_number' => 'nullable|digits:12|unique:users,aadhar_card_number,'. $user->id,
            'pan_card_number' => 'nullable|regex:/[A-Z]{5}[0-9]{4}[A-Z]{1}/|unique:users,pan_card_number,'. $user->id,
            'password' => 'nullable|min:8',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // 'aadhar_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pan_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:1,0',
            'latitude' => 'required',
            'longitude' => 'required'
        ], [
            'profile_image.max' => 'The Profile Image must not be larger than 2 MB.',
            // 'aadhar_image.max' => 'The Aadhar Image must not be larger than 2 MB.',
            'pan_image.max' => 'The Pan Image must not be larger than 2 MB.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }else{
            $user->first_name = $request->company_name;
            // $user->last_name = $request->last_name;
            $user->name = $request->company_name;
            $user->status = $request->status;
            // $user->date_of_birth = format_date_for_db($request->date_of_birth);
            // $user->gender = $request->gender;
            $user->address = $request->address;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->opt_mobile_no = $request->opt_mobile_no;
            if(isset($request->password)){
                $user->password = bcrypt($request->password) ?? null;
            }
            // $user->aadhar_card_number = $request->aadhaar_number;
            $user->pan_card_number = $request->pan_card_number;

            if ($request->hasFile('profile_image')) {
                $user->clearMediaCollection('system-user-image');
                $user->addMedia($request->file('profile_image'))->toMediaCollection('system-user-image');
            }
        
            // if ($request->hasFile('aadhar_image')) {
            //     $user->clearMediaCollection('system-user-aadhar');
            //     $user->addMedia($request->file('aadhar_image'))->toMediaCollection('system-user-aadhar');
            // }
        
            if ($request->hasFile('pan_image')) {
                $user->clearMediaCollection('system-user-pan');
                $user->addMedia($request->file('pan_image'))->toMediaCollection('system-user-pan');
            }

            $user->save();

            $branch = Branch::where('user_id',$user->id )->first();
            if ($branch) {
                $branch->latitude = $request->latitude;
                $branch->longitude = $request->longitude;
               
                $branch->update();

            }else{
                $branch = Branch::create([
                    'user_id' => $user->id,
                    'company_id' => Auth::user()->id,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
            }

            return back()->with('success','Branch Updated Successfully');
          
        }
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'Branch not found.'])->withInput();
        }
        Branch::where('user_id',$user->id )->delete();
        $user->delete();
        return response()->json(['success' => 'Branch Deleted Successfully']);
    }
}
