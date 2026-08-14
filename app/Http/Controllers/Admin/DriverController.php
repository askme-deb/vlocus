<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\VerifiesBankUIdentity;
use App\Http\Requests\Admin\VerifyDrivingLicenseRequest;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\VehicleType;
use App\Models\User;

use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use App\Services\BankU\BankUIdentityService;
use App\Services\BankU\Exceptions\BankUConnectionException;

class DriverController extends Controller implements HasMiddleware
{
    use VerifiesBankUIdentity;

    public function __construct(private readonly BankUIdentityService $bankUIdentityService)
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Driver Show', only: ['index','show']),
            new Middleware('permission:Driver Create', only: [
                'create','store','verifyDrivingLicense','verifyPan','sendAadhaarOtp','verifyAadhaarOtp',
            ]),
            new Middleware('permission:Driver Edit', only: ['edit','update']),
            new Middleware('permission:Driver Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $query = User::role('Driver');

        if ($companyId = auth()->user()->companyId()) {
            $query->whereHas('driver', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        }

        $drivers = $query->latest()->get();

        return view('admin.driver.index',compact('drivers'));
    }

    public function create()
    {
        return view('admin.driver.create');
    }

public function storeFromModal(Request $request)
{
    $validator = Validator::make($request->all(), [
        'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
        'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|digits:10|regex:/^[6789]/|unique:users,phone',

        'aadhaar_number' => 'nullable|digits:12|unique:users,aadhar_card_number',
        'pan_card_number' => 'nullable|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/|unique:users,pan_card_number',

        'driving_license_number' => 'required|unique:drivers,driving_license_number',
        'vehicle_type' => 'required',

        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'aadhar_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'pan_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'driver_license_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    DB::beginTransaction();

    try {

        $user = User::create([
            'status' => $request->status ?? 1,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'date_of_birth' => $request->date_of_birth
                ? format_date_for_db($request->date_of_birth)
                : null,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => bcrypt($request->password ?? '12345678'),
            'aadhar_card_number' => $request->aadhaar_number,
            'pan_card_number' => $request->pan_card_number,
        ]);

        $user->syncRoles('Driver');

        if ($request->hasFile('profile_image')) {
            $user->addMedia($request->file('profile_image'))
                ->toMediaCollection('system-user-image');
        }

        if ($request->hasFile('aadhar_image')) {
            $user->addMedia($request->file('aadhar_image'))
                ->toMediaCollection('system-user-aadhar');
        }

        if ($request->hasFile('pan_image')) {
            $user->addMedia($request->file('pan_image'))
                ->toMediaCollection('system-user-pan');
        }

        $driverData = [
            'user_id' => $user->id,
            'driving_license_number' => $request->driving_license_number,
            'vehicle_type' => $request->vehicle_type,
            'driving_exprience' => $request->driving_exprience,
            'vehicle_id' => $request->vehicle_id,
            'company_id' => auth()->user()->companyId(),
        ];

        $drivingLicenseVerificationData = $this->decodeVerificationPayload($request, 'driving_license_verification_data');
        if ($drivingLicenseVerificationData !== null) {
            $driverData['driving_license_verification_data'] = $drivingLicenseVerificationData;
            $driverData['driving_license_verified_at'] = now();
        }

        $driver = Driver::create($driverData);

        if ($request->hasFile('driver_license_image')) {
            $driver->addMedia($request->file('driver_license_image'))
                ->toMediaCollection('driver-license');
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Driver Added Successfully',
            'driver' => [
                'id' => $driver->id,
                'user_id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
            ]
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
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
            'driving_license_number' => 'required|unique:drivers,driving_license_number',
            'password' => 'required|min:8',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'aadhar_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pan_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'driver_license_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:1,0'
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
        $user->syncRoles('Driver');

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
                  // Create Driver Record
            $authUser = auth()->user();
            $driverData = [
                'user_id' => $user->id,
                'driving_license_number' => $request->driving_license_number,
                'driving_exprience' => $request->driving_exprience,
                'company_id' => $authUser->companyId(),
            ];

            $drivingLicenseVerificationData = $this->decodeVerificationPayload($request, 'driving_license_verification_data');
            if ($drivingLicenseVerificationData !== null) {
                $driverData['driving_license_verification_data'] = $drivingLicenseVerificationData;
                $driverData['driving_license_verified_at'] = now();
            }

            $driver = Driver::create($driverData);
        }


        if ($request->hasFile('driver_license_image')) {
            $driver->addMedia($request->file('driver_license_image'))->toMediaCollection('driver-license');
        }

        return redirect()->route('driver.index')->with('success', $driver ? 'Driver Added Successfully' : 'Driver Not Added');
    }


    public function verifyDrivingLicense(VerifyDrivingLicenseRequest $request)
    {
        try {
            $result = $this->bankUIdentityService->verifyDrivingLicense(
                $request->string('driving_license_number'),
                $request->string('dob'),
                (float) $request->input('latitude', 28.6139),
                (float) $request->input('longitude', 77.209),
            );
        } catch (BankUConnectionException $e) {
            return $this->bankUUnavailableResponse();
        }

        return $this->bankUResponse($result, 'Driving license verification failed.');
    }

    public function show(Driver $driver)
    {
        //
    }

    public function edit($id)
    {
        $vehicle_types =  VehicleType::where('is_visible',1)->get();
       $user = User::find($id);
       $authUser = auth()->user();
        $vehicles =  Vehicle::where('is_visible',1)->get();

        return view('admin.driver.edit',compact('user','vehicle_types','vehicles'));

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
            $driver=  Driver::where('user_id',$user->id )->first();
            if ($driver) {
                $driver->driving_license_number = $request->driving_license_number;
                $driver->vehicle_type = $request->vehicle_type;
                $driver->driving_exprience = $request->driving_exprience;
                $driver->vehicle_id = $request->vehicle_id;
                if (!$driver->company_id) {
                    $driver->company_id = $authUser->companyId();
                }

                $driver->save();

            }else{
                $driverData = [
                    'user_id' => $user->id,
                    'driving_license_number' => $request->driving_license_number,
                    'vehicle_type' => $request->vehicle_type,
                    'driving_exprience' => $request->driving_exprience,
                    'vehicle_id' => $request->vehicle_id,
                    'company_id' => $authUser->companyId(),
                ];

                $driver = Driver::create($driverData);
            }




            return back()->with('success','Driver Updated Successfully');

        }
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'Driver not found.'])->withInput();
        }
        $user->delete();
        Driver::where('user_id',$user->id )->delete();
        return response()->json(['success' => 'Driver Deleted Successfully']);
    }
}
