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
use Illuminate\Support\Str;
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

    /**
     * Column names populated from the BankU driving-license verify response.
     * Shared by the create/edit forms, validation and persistence so the three
     * stay in sync. `driving_license_number` is handled separately.
     *
     * @return array<int, string>
     */
    private function dlFieldKeys(): array
    {
        return array_keys(Driver::DL_FIELDS);
    }

    /**
     * Nullable validation rules for the DL detail fields so a partial lookup
     * can still be saved and completed by hand.
     *
     * @return array<string, string>
     */
    private function dlValidationRules(): array
    {
        $rules = ['dl_address' => 'nullable|string|max:1000'];

        foreach ($this->dlFieldKeys() as $key) {
            $rules[$key] ??= 'nullable|string|max:255';
        }

        return $rules;
    }

    /**
     * Translate a raw BankU driving-license verify payload into our
     * column => value map. Mirrors the client-side DL_FIELD_MAP used by the
     * full create/edit form; used server-side by the quick-add modal (which
     * only posts the raw payload) and as a fallback in store()/update().
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function dlColumnsFromPayload(array $payload): array
    {
        $details = is_array($payload['details_of_driving_licence'] ?? null)
            ? $payload['details_of_driving_licence']
            : [];

        $pick = function (array $keys) use ($payload, $details) {
            foreach ($keys as $key) {
                foreach ([$details, $payload] as $source) {
                    $value = $source[$key] ?? null;
                    if (is_scalar($value) && trim((string) $value) !== '') {
                        return trim((string) $value);
                    }
                }
            }

            return null;
        };

        $columns = [
            'dl_status'              => $pick(['status', 'dl_status']),
            'holder_name'            => $pick(['name', 'holder_name']),
            'father_or_husband_name' => $pick(['father_or_husband_name', 'father_name', 'fathers_name']),
            'dl_dob'                 => $pick(['dob', 'date_of_birth']),
            'dl_issue_date'          => $pick(['date_of_issue', 'doi', 'issue_date', 'dl_issue_date']),
            'dl_address'             => $pick(['address', 'permanent_address', 'present_address']),
            'dl_verification_id'     => $pick(['verification_id', 'verificationId', 'client_ref_num']),
            'dl_transaction_id'      => $pick(['transaction_id', 'txn_id', 'transactionId', 'reference_id']),
        ];

        // details_of_driving_licence.split_address.state is an array of
        // [fullName, abbreviation] pairs.
        $stateEntry = $details['split_address']['state'][0] ?? null;
        if (is_array($stateEntry)) {
            $columns['issuing_state'] = trim(implode(' - ', array_filter($stateEntry, 'is_scalar')));
        } elseif (is_scalar($stateEntry) && trim((string) $stateEntry) !== '') {
            $columns['issuing_state'] = trim((string) $stateEntry);
        }

        // badge_details[].class_of_vehicle is an array of COV codes.
        $firstBadge = $payload['badge_details'][0] ?? null;
        $cov = is_array($firstBadge['class_of_vehicle'] ?? null) ? $firstBadge['class_of_vehicle'] : null;
        if ($cov) {
            $columns['class_of_vehicle'] = implode(', ', array_filter($cov, 'is_scalar'));
        } elseif ($covScalar = $pick(['class_of_vehicle', 'cov', 'vehicle_class'])) {
            $columns['class_of_vehicle'] = $covScalar;
        }

        $validity = is_array($payload['dl_validity'] ?? null) ? $payload['dl_validity'] : [];
        $columns['dl_nt_valid_from'] = $validity['non_transport']['from'] ?? null;
        $columns['dl_nt_valid_to']   = $validity['non_transport']['to'] ?? null;
        $columns['dl_tr_valid_from'] = $validity['transport']['from'] ?? null;
        $columns['dl_tr_valid_to']   = $validity['transport']['to'] ?? null;

        return array_filter(
            $columns,
            fn ($value) => $value !== null && $value !== '',
        );
    }

    /**
     * Merge DL columns for persistence: values the user typed/edited in the
     * form win over the raw BankU payload, which in turn fills anything the
     * form left blank.
     *
     * @return array<string, mixed>
     */
    private function dlColumnsForSave(Request $request, ?array $verificationData): array
    {
        $fromPayload = $verificationData !== null
            ? $this->dlColumnsFromPayload($verificationData)
            : [];

        $fromForm = array_filter(
            $request->only($this->dlFieldKeys()),
            fn ($value) => $value !== null && $value !== '',
        );

        return array_merge($fromPayload, $fromForm);
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
    ] + $this->dlValidationRules());

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

        $aadhaarVerificationData = $this->decodeVerificationPayload($request, 'aadhaar_verification_data');
        if ($aadhaarVerificationData !== null) {
            $user->aadhaar_verification_data = $aadhaarVerificationData;
            $user->aadhaar_verified_at = now();
        }

        $panVerificationData = $this->decodeVerificationPayload($request, 'pan_verification_data');
        if ($panVerificationData !== null) {
            $user->pan_verification_data = $panVerificationData;
            $user->pan_verified_at = now();
        }

        if ($aadhaarVerificationData !== null || $panVerificationData !== null) {
            $user->save();
        }

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
        $driverData = array_merge(
            $driverData,
            $this->dlColumnsForSave($request, $drivingLicenseVerificationData),
        );
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
        ] + $this->dlValidationRules(), [
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
            $driverData = array_merge(
                $driverData,
                $this->dlColumnsForSave($request, $drivingLicenseVerificationData),
            );
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
        $verificationId = 'dl_' . Str::uuid();

        try {
            $result = $this->bankUIdentityService->verifyDrivingLicense(
                $request->string('driving_license_number'),
                $request->string('dob'),
                $verificationId,
            );
        } catch (BankUConnectionException $e) {
            return $this->bankUUnavailableResponse();
        }

        if (! $result->success) {
            return response()->json([
                'success' => false,
                'message' => $result->message ?: 'Driving license verification failed.',
            ], 422);
        }

        // Surface the verification id we sent so the form can persist it even
        // when BankU doesn't echo one back in the payload.
        $data = $result->data;
        $data['verification_id'] ??= $verificationId;

        return response()->json([
            'success' => true,
            'data' => $data,
            'verification_id' => $data['verification_id'],
        ]);
    }

    public function show(Driver $driver)
    {
        if ($companyId = auth()->user()->companyId()) {
            abort_unless((int) $driver->company_id === (int) $companyId, 404);
        }

        $driver->load('user');

        return view('admin.driver.show', compact('driver'));
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
        ] + $this->dlValidationRules(), [
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

            $aadhaarVerificationData = $this->decodeVerificationPayload($request, 'aadhaar_verification_data');
            if ($aadhaarVerificationData !== null) {
                $user->aadhaar_verification_data = $aadhaarVerificationData;
                $user->aadhaar_verified_at = now();
            }

            $panVerificationData = $this->decodeVerificationPayload($request, 'pan_verification_data');
            if ($panVerificationData !== null) {
                $user->pan_verification_data = $panVerificationData;
                $user->pan_verified_at = now();
            }

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
            $driver = Driver::firstOrNew(['user_id' => $user->id]);

            $driver->driving_license_number = $request->driving_license_number;
            $driver->vehicle_type = $request->vehicle_type;
            $driver->driving_exprience = $request->driving_exprience;
            $driver->vehicle_id = $request->vehicle_id;
            if (!$driver->company_id) {
                $driver->company_id = $authUser->companyId();
            }

            $drivingLicenseVerificationData = $this->decodeVerificationPayload($request, 'driving_license_verification_data');
            $driver->fill($this->dlColumnsForSave($request, $drivingLicenseVerificationData));
            if ($drivingLicenseVerificationData !== null) {
                $driver->driving_license_verification_data = $drivingLicenseVerificationData;
                $driver->driving_license_verified_at = now();
            }

            $driver->save();

            if ($request->hasFile('driver_license_image')) {
                $driver->clearMediaCollection('driver-license');
                $driver->addMedia($request->file('driver_license_image'))->toMediaCollection('driver-license');
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
