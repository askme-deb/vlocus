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

use App\Models\Employee;
use App\Services\BankU\BankUIdentityService;


class EmployeeController extends Controller implements HasMiddleware
{
    use VerifiesBankUIdentity;

    public function __construct(private readonly BankUIdentityService $bankUIdentityService)
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Employee Show', only: ['index','show']),
            new Middleware('permission:Employee Create', only: [
                'create','store','verifyPan','sendAadhaarOtp','verifyAadhaarOtp',
            ]),
            new Middleware('permission:Employee Edit', only: ['edit','update']),
            new Middleware('permission:Employee Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $query = User::role('Employee');

        if ($companyId = auth()->user()->companyId()) {
            $query->whereHas('employee', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        }

        $employees = $query->latest()->get();

        return view('admin.employee.index',compact('employees'));
    }

    public function create()
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

        return view('admin.employee.create',compact('branchs'));
    }
    

   
    public function store(Request $request)
    {
        // d($request->all());
        // Validation Rules
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits:10|regex:/^[6789]/|unique:users,phone',
            'aadhaar_number' => 'nullable|digits:12|unique:users,aadhar_card_number',
            'pan_card_number' => 'nullable|regex:/[A-Z]{5}[0-9]{4}[A-Z]{1}/|unique:users,pan_card_number',
            'password' => 'required|min:8',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'aadhar_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pan_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:1,0',
            'branch_id' => 'required|exists:users,id'
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
        $user->syncRoles('Employee');

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


        if ($user) {
            $branch = User::find($request->branch_id);
            $employeeData = [
                'user_id' => $user->id,
                'company_id' => $branch->branch?->company?->id,
                'branch_id' => $request->branch_id,
            ];
            $authUser = auth()->user();

            
            $employee = Employee::create($employeeData);
        }

        return redirect()->route('employee.index')->with('success', $employee ? 'Employee Added Successfully' : 'Employee Not Added');
    }


    public function show(Driver $driver)
    {
        //
    }

    public function edit($id)
    {
    //     $vehicle_types =  VehicleType::where('is_visible',1)->get();
        $user = User::find($id);
    //    $authUser = auth()->user();
    //     $vehicles =  Vehicle::where('is_visible',1)->get();

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
   
        return view('admin.employee.edit',compact('user','branchs'));
    }

    public function update(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|email|unique:users,email,'. $user->id,
            'phone' => 'required|digits:10|regex:/^[6789]/|unique:users,phone,'. $user->id,
            'aadhaar_number' => 'nullable|digits:12|unique:users,aadhar_card_number,'. $user->id,
            'pan_card_number' => 'nullable|regex:/[A-Z]{5}[0-9]{4}[A-Z]{1}/|unique:users,pan_card_number,'. $user->id,
            'password' => 'nullable|min:8',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'aadhar_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pan_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:1,0'
        ], [
            'profile_image.max' => 'The Profile Image must not be larger than 2 MB.',
            'aadhar_image.max' => 'The Aadhar Image must not be larger than 2 MB.',
            'pan_image.max' => 'The Pan Image must not be larger than 2 MB.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }else{
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->name = $request->first_name.' '.$request->last_name;
            $user->status = $request->status;
            $user->date_of_birth = format_date_for_db($request->date_of_birth);
            $user->gender = $request->gender;
            $user->address = $request->address;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->opt_mobile_no = $request->opt_mobile_no;
            if(isset($request->password)){
                $user->password = bcrypt($request->password);
            }
            $user->aadhar_card_number = $request->aadhaar_number;
            $user->pan_card_number = $request->pan_card_number;

            if ($request->hasFile('profile_image')) {
                $user->clearMediaCollection('system-user-image');
                $user->addMedia($request->file('profile_image'))->toMediaCollection('system-user-image');
            }
        
            if ($request->hasFile('aadhar_image')) {
                $user->clearMediaCollection('system-user-aadhar');
                $user->addMedia($request->file('aadhar_image'))->toMediaCollection('system-user-aadhar');
            }
        
            if ($request->hasFile('pan_image')) {
                $user->clearMediaCollection('system-user-pan');
                $user->addMedia($request->file('pan_image'))->toMediaCollection('system-user-pan');
            }

            $user->save();
            $authUser = auth()->user();
            $employee = Employee::where('user_id',$user->id)->first();

            $branch = User::find($request->branch_id);
            if ($employee) {
                $employee->user_id = $user->id;
                $employee->company_id = $branch->branch?->company?->id;
                $employee->branch_id = $request->branch_id;
                $employee->save();
            }else{
                $employeeData = [
                    'user_id' => $user->id,
                    'company_id' => $branch->branch?->company?->id,
                    'branch_id' => $request->branch_id,
                ];
                $employee = Employee::create($employeeData);
            }
            return back()->with('success','Employee Updated Successfully');
        }
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'Employee not found.'])->withInput();
        }
        $user->delete();
        Driver::where('user_id',$user->id )->delete();
        return response()->json(['success' => 'Employee Deleted Successfully']);
    }
}
