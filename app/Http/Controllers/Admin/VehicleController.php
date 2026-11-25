<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\HandlesBankUResponses;
use App\Http\Requests\Admin\VerifyRcRequest;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use App\Models\VehicleType;
use App\Models\Brand;
use App\Models\Models;
use App\Models\Color;
use App\Models\Route;

use App\Models\Booking;

use App\Models\Journey;

use App\Services\BankU\BankUIdentityService;
use App\Services\BankU\Exceptions\BankUConnectionException;
use App\Services\Wallet\Exceptions\ApiCallDisabledException;
use App\Services\Wallet\Exceptions\InsufficientWalletBalanceException;

use Carbon\Carbon;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
class VehicleController extends Controller implements HasMiddleware
{
    use HandlesBankUResponses;

    public function __construct(private readonly BankUIdentityService $bankUIdentityService)
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Vehicle Show', only: ['index','show','downloadProfile']),
            new Middleware('permission:Vehicle Create', only: ['create','store','verifyRc']),
            new Middleware('permission:Vehicle Edit', only: ['edit','update']),
            new Middleware('permission:Vehicle Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        // Auto-deactivate vehicles whose insurance / PUCC / RC validity has expired.
        Vehicle::deactivateExpiredCompliance();

        $query = Vehicle::latest();

        if ($companyId = auth()->user()->companyId()) {
            $query->where('company_id', $companyId);
        }

        $vehicles = $query->get();

        return view('admin.vehicle.index',compact('vehicles'));
    }

    public function booking_list($journey_id)
    {

        $bookings = Booking::where('journey_id',$journey_id)->latest()->get();


        return view('admin.vehicle.bookings',compact('bookings'));
    }

    public function create()
    {
        return view('admin.vehicle.create');
    }

    /**
     * Column names populated from the BankU RC verify response. Shared by the
     * create/edit forms, validation and persistence so the three stay in sync.
     *
     * @return array<int, string>
     */
    private function rcFieldKeys(): array
    {
        return [
            'rc_status', 'vehicle_class', 'chassis_number', 'engine_number',
            'manufacturer', 'model_name', 'colour', 'fuel_type', 'emission_norm',
            'owner_name', 'registration_date', 'rc_expiry_date', 'tax_upto',
            'insurance_company', 'insurance_upto', 'financer', 'owner_address',
            'cubic_capacity', 'gross_weight', 'seat_capacity', 'sleeper_capacity',
            'pucc_number', 'pucc_upto', 'permit_type', 'permit_valid_upto',
            'national_permit_number', 'national_permit_upto',
        ];
    }

    /**
     * Translate a raw BankU RC verify payload into our column => value map.
     * Mirrors the client-side RC_FIELD_MAP used by the full create/edit form;
     * used server-side by the quick-add modal, which only posts the raw
     * payload rather than one hidden input per column.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function rcColumnsFromPayload(array $payload): array
    {
        $map = [
            'rc_status'              => ['rc_status', 'status'],
            'vehicle_class'          => ['class', 'vehicle_class', 'vehicle_category_description'],
            'chassis_number'         => ['chassis', 'chassis_number', 'chassis_no'],
            'engine_number'          => ['engine', 'engine_number', 'engine_no'],
            'manufacturer'           => ['vehicle_manufacturer_name', 'maker_description', 'manufacturer', 'maker'],
            'model_name'             => ['model', 'maker_model', 'vehicle_model'],
            'colour'                 => ['vehicle_colour', 'colour', 'color'],
            'fuel_type'              => ['type', 'fuel_type', 'fuel_descr', 'fuel'],
            'emission_norm'          => ['norms_type', 'emission_norm', 'norms_desc', 'norms'],
            'owner_name'             => ['owner', 'owner_name'],
            'registration_date'      => ['registration_date', 'reg_date', 'rc_regn_dt'],
            'rc_expiry_date'         => ['rc_expiry_date', 'fit_up_to', 'rc_expiry', 'expiry_date'],
            'tax_upto'               => ['tax_upto', 'vehicle_tax_upto', 'tax_up_to'],
            'insurance_company'      => ['vehicle_insurance_company_name', 'insurance_company', 'vehicle_insurance', 'insurance_comp'],
            'insurance_upto'         => ['vehicle_insurance_upto', 'insurance_upto', 'insurance_validity'],
            'financer'               => ['financer', 'financier', 'rc_financer'],
            'owner_address'          => ['present_address', 'permanent_address', 'address', 'owner_address', 'c_address'],
            'cubic_capacity'         => ['cubic_capacity', 'vehicle_cubic_capacity', 'cc'],
            'gross_weight'           => ['gross_vehicle_weight', 'vehicle_gross_weight', 'gross_weight', 'gvw'],
            'seat_capacity'          => ['vehicle_seat_capacity', 'seating_capacity', 'seat_capacity'],
            'sleeper_capacity'       => ['vehicle_sleeper_capacity', 'sleeper_capacity', 'sleeper_cap'],
            'pucc_number'            => ['pucc_number', 'pucc_no'],
            'pucc_upto'              => ['pucc_upto', 'pucc_valid_upto', 'pucc_validity'],
            'permit_type'            => ['permit_type'],
            'permit_valid_upto'      => ['permit_valid_upto', 'permit_validity', 'permit_upto'],
            'national_permit_number' => ['national_permit_number', 'national_permit_no'],
            'national_permit_upto'   => ['national_permit_upto', 'national_permit_validity'],
        ];

        $columns = [];
        $flat = $this->flattenPayload($payload);

        foreach ($map as $column => $keys) {
            // Validity dates: take the latest valid date among the listed
            // keys and any key matching the pattern (mirrors rcPickLatestDate
            // in _rc_populate.blade.php), so a renewal beats a lapsed value.
            if (isset(self::RC_DATE_PATTERNS[$column])) {
                $latest = null;
                foreach ($flat as $key => $value) {
                    if (! in_array($key, $keys, true) && ! preg_match(self::RC_DATE_PATTERNS[$column], (string) $key)) {
                        continue;
                    }
                    $date = Vehicle::parseRcDate(is_scalar($value) ? (string) $value : null);
                    if ($date && (! $latest || $date->gt($latest))) {
                        $latest = $date;
                    }
                }
                if ($latest) {
                    $columns[$column] = $latest->format('Y-m-d');
                }
                continue;
            }

            foreach ($keys as $key) {
                $value = $payload[$key] ?? null;
                if (is_scalar($value) && trim((string) $value) !== '') {
                    $columns[$column] = trim((string) $value);
                    break;
                }
            }
        }

        if (array_key_exists('is_commercial', $payload)) {
            $columns['is_commercial'] = filter_var($payload['is_commercial'], FILTER_VALIDATE_BOOLEAN);
        } elseif (isset($columns['vehicle_class'])
            && preg_match('/LGV|LMV-CARGO|GOODS|TRANSPORT|COMMERCIAL|MAXI|TRAILER/i', $columns['vehicle_class'])) {
            $columns['is_commercial'] = true;
        }

        return $columns;
    }

    /**
     * Key patterns for RC validity dates. Must stay in sync with
     * window.RC_DATE_PATTERNS in _rc_populate.blade.php.
     */
    private const RC_DATE_PATTERNS = [
        'insurance_upto'       => '/insurance.*(upto|up_to|valid|expir|end)/i',
        'pucc_upto'            => '/pucc.*(upto|up_to|valid|expir)/i',
        'rc_expiry_date'       => '/(^|_)(fit_up_?to|rc_expiry(_date)?|rc_valid(ity|_upto)?)$/i',
        'tax_upto'             => '/tax.*(upto|up_to|valid)/i',
        'permit_valid_upto'    => '/^(?!.*national).*permit.*(upto|up_to|valid)/i',
        'national_permit_upto' => '/national_permit.*(upto|up_to|valid)/i',
    ];

    /**
     * Flatten a nested payload into ['a_b_0_c' => value] so date key
     * patterns also match values inside nested objects / arrays.
     *
     * @param  array<string|int, mixed>  $payload
     * @return array<string, mixed>
     */
    private function flattenPayload(array $payload, string $prefix = ''): array
    {
        $flat = [];
        foreach ($payload as $key => $value) {
            $path = $prefix === '' ? (string) $key : $prefix . '_' . $key;
            if (is_array($value)) {
                $flat += $this->flattenPayload($value, $path);
            } else {
                $flat[$path] = $value;
            }
        }

        return $flat;
    }

    /**
     * Build the validation rules for the create/edit forms: the registration
     * number is the only required field, everything else is optional so a
     * partial RC lookup can still be saved and completed by hand.
     *
     * @return array<string, string>
     */
    private function rcValidationRules(): array
    {
        $rules = [
            'vehicle_number' => 'required|string|max:255',
            'owner_address'  => 'nullable|string|max:1000',
            'is_commercial'  => 'nullable|boolean',
            'is_visible'     => 'nullable|boolean',
        ];

        foreach ($this->rcFieldKeys() as $key) {
            $rules[$key] ??= 'nullable|string|max:255';
        }

        return $rules;
    }
    public function getModels($brand_id)
    {
        $models = Models::where('brand_id', $brand_id)->get(['id', 'name']);
        return response()->json(['models' => $models]);

    }

    public function verifyRc(VerifyRcRequest $request)
    {
        try {
            $result = $this->bankUIdentityService->verifyRc(
                $request->string('vehicle_registration_number'),
                auth()->user()->companyId(),
                auth()->id(),
            );
        } catch (InsufficientWalletBalanceException $e) {
            return $this->bankUWalletBlockedResponse($e->getMessage());
        } catch (ApiCallDisabledException $e) {
            return $this->bankUWalletBlockedResponse($e->getMessage());
        } catch (BankUConnectionException $e) {
            return $this->bankUUnavailableResponse();
        }

        return $this->bankUResponse($result, 'RC verification failed.');
    }
public function storeFromModal(Request $request)
{
    $validator = Validator::make($request->all(), [
        'vehicle_number'  => 'required|string|max:255',
        'name'            => 'nullable|string|max:255',
        'engine_number'   => 'nullable|string|max:255',
        'fuel_type'       => 'nullable',
        'vehicle_type'    => 'nullable|exists:vehicle_types,id',
        'description'     => 'nullable|string',
        'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors'  => $validator->errors(),
        ], 422);
    }

    $vehicleNumber = strtoupper(trim((string) $request->input('vehicle_number')));

    // RC lookup (when the modal's "Verify" step succeeded) supplies the base
    // set of columns; anything the user typed in the modal then overrides it.
    $rcVerificationData = $this->decodeVerificationPayload($request, 'rc_verification_data');
    $vehicleData = $rcVerificationData !== null
        ? $this->rcColumnsFromPayload($rcVerificationData)
        : [];

    foreach (['engine_number', 'fuel_type', 'vehicle_type', 'description'] as $field) {
        if ($request->filled($field)) {
            $vehicleData[$field] = $request->input($field);
        }
    }

    $vehicleData['company_id']     = auth()->user()->companyId();
    $vehicleData['vehicle_number'] = $vehicleNumber;
    $vehicleData['rwc_number']     = $vehicleNumber;
    $vehicleData['is_visible']     = 1;
    $vehicleData['name']           = $request->filled('name')
        ? $request->input('name')
        : (trim(($vehicleData['manufacturer'] ?? '') . ' ' . ($vehicleData['model_name'] ?? ''))
            ?: $vehicleNumber);

    if ($rcVerificationData !== null) {
        $vehicleData['rc_verification_data'] = $rcVerificationData;
        $vehicleData['rc_verified_at'] = now();
    }

    $vehicle = Vehicle::create($vehicleData);

    if ($request->hasFile('image')) {
        $vehicle->addMedia($request->file('image'))
            ->toMediaCollection('vehicles');
    }

    return response()->json([
        'success' => true,
        'message' => 'Vehicle created successfully.',
        'vehicle' => [
            'id'             => $vehicle->id,
            'name'           => $vehicle->name,
            'vehicle_number' => $vehicle->vehicle_number,
            'brand'          => optional($vehicle->brand)->name
                ?? ($vehicleData['manufacturer'] ?? null),
        ]
    ]);
}
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rcValidationRules());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $data = $this->rcFormData($request);
        $data['company_id'] = auth()->user()->companyId();

        $rcVerificationData = $this->decodeVerificationPayload($request, 'rc_verification_data');
        if ($rcVerificationData !== null) {
            $data['rc_verification_data'] = $rcVerificationData;
            $data['rc_verified_at'] = now();
        }

        $vehicle = Vehicle::create($data);

        if ($vehicle->id) {
            return redirect()->route('vehicle.show', $vehicle->id)->with(['success' => 'Vehicle Created Successfully']);
        }

        return back()->with(['error' => 'Vehicle Not Created']);
    }

    /**
     * Map the submitted RC form into a column => value array for persistence.
     * `name` is mirrored from the registration number so listings and the
     * other modules that still read `vehicles.name` keep working.
     */
    private function rcFormData(Request $request): array
    {
        $data = $request->only(array_merge($this->rcFieldKeys(), ['vehicle_number', 'owner_address']));

        $data['vehicle_number'] = strtoupper(trim((string) $request->input('vehicle_number')));
        $data['name'] = $data['vehicle_number'];
        $data['rwc_number'] = $data['vehicle_number'];
        $data['is_commercial'] = $request->filled('is_commercial')
            ? (bool) $request->boolean('is_commercial')
            : null;
        $data['is_visible'] = $request->filled('is_visible') ? (int) $request->boolean('is_visible') : 1;

        return $data;
    }

    public function show($id)
    {
        $query = Vehicle::query();

        if ($companyId = auth()->user()->companyId()) {
            $query->where('company_id', $companyId);
        }

        $vehicle = $query->findOrFail($id);

        return view('admin.vehicle.show', ['data' => $vehicle]);
    }

    /**
     * Download a printable PDF summary of the vehicle's RC / compliance details.
     */
    public function downloadProfile($id)
    {
        $query = Vehicle::query();

        if ($companyId = auth()->user()->companyId()) {
            $query->where('company_id', $companyId);
        }

        $vehicle = $query->findOrFail($id);

        // SVG, not PNG -- PNG rendering needs the imagick extension, which
        // isn't installed here; SVG is pure XML and needs no image library.
        $qrCodeSvg = QrCode::format('svg')->size(150)->margin(0)->generate(route('vehicle.show', $vehicle->id));

        $photoPath = null;
        $media = $vehicle->getFirstMedia('vehicles');
        if ($media && file_exists($media->getPath())) {
            $photoPath = $media->getPath();
        }

        $html = view('admin.vehicle.pdf', [
            'data' => $vehicle,
            'qrCodeDataUri' => 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg),
            'photoPath' => $photoPath,
        ])->render();

        $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait')->set_option('isHtml5ParserEnabled', true);

        $fileName = 'vehicle-' . ($vehicle->vehicle_number ?: $vehicle->id) . '.pdf';

        return $pdf->download($fileName);
    }

    public function edit($id)
    {
        $data = Vehicle::findOrFail($id);

        return view('admin.vehicle.edit', compact('data'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rcValidationRules() + [
            'id' => 'required|exists:vehicles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $vehicle = Vehicle::findOrFail($request->id);
        $vehicle->fill($this->rcFormData($request));

        $rcVerificationData = $this->decodeVerificationPayload($request, 'rc_verification_data');
        $refetched = $rcVerificationData !== null;

        if ($refetched) {
            $vehicle->rc_verification_data = $rcVerificationData;
            $vehicle->rc_verified_at = now();

            // Validity dates always follow the freshly verified payload (the
            // latest date per document), independent of the browser script.
            $fresh = $this->rcColumnsFromPayload($rcVerificationData);
            $vehicle->fill(array_intersect_key($fresh, self::RC_DATE_PATTERNS));
        }

        // Revalidate the status against the (possibly refreshed) documents.
        // After an RC re-fetch the status follows the verified data exactly:
        // Active only when insurance, PUCC and RC are all valid. On a plain
        // manual save the admin's choice stands, except that a vehicle with
        // an expired document can't be marked Active.
        $issues = $vehicle->complianceIssues();

        if ($refetched) {
            $vehicle->is_visible = empty($issues) ? 1 : 0;
        } elseif ($vehicle->is_visible && $vehicle->expiredComplianceReasons()) {
            $vehicle->is_visible = 0;
        }

        if ($vehicle->save()) {
            if ($vehicle->is_visible) {
                $message = $refetched
                    ? 'RC details refreshed. All documents are valid, so the vehicle is now Active.'
                    : 'Vehicle updated successfully.';

                return redirect()->route('vehicle.index')->with('success', $message);
            }

            $message = ($refetched ? 'RC details refreshed, but the' : 'Vehicle saved. The')
                . ' vehicle is Inactive'
                . ($issues ? ': ' . implode('; ', $issues) . '.' : '.');

            return redirect()->route('vehicle.index')->with($issues ? 'error' : 'success', $message);
        }

        return redirect()->back()->with('error', 'Vehicle update failed.');
    }

    public function destroy(string $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        if (!$vehicle) {
            return redirect()->back()->withErrors(['error' => 'Vehicle not found.'])->withInput();
        }

        $vehicle->delete();
        return response()->json(['success' => 'Vehicle Deleted Successfully']);
    }

      public function journey($vehicle_id)
    {
        $journeys = Journey::where('vehicle_id',$vehicle_id)->get();

        $vehicle = Vehicle::findOrFail($vehicle_id);

        $routes = Route::where('id', $vehicle->route_id)
        ->orWhere('reverse_route_of', $vehicle->route_id)
        ->get();
        return view('admin.vehicle.journey',compact('journeys','vehicle','routes'));
    }
    public function journey_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'route_id' => 'required|exists:routes,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $journey = Journey::create([
            'vehicle_id' => $request->vehicle_id,
            'route_id' => $request->route_id,
        ]);
        $routeBusStops = BusRouteStop::where('bus_route_id', $request->route_id)->orderBy('sl_no')->get();

        if ($routeBusStops->isNotEmpty()) {
            foreach ($routeBusStops as $bus_stop) {
                JourneyStoppage::create([
                    'journey_id' => $journey->id,
                    'stop_id' => $bus_stop->bus_stop_id,
                    'sl_no' => $bus_stop->sl_no,
                ]);
            }
        }

        return back()->with('success', 'Journey Created Successfully');
    }

    public function journey_start($journey_id)
    {
        $journey = Journey::find($journey_id);
        if (!$journey) {
            return back()->with('error', 'Journey not found.');
        }
        if ($journey->start_time) {
            return back()->with('error', 'Journey alredy started.');
        }
        $journey->start_time = Carbon::now();
        $journey->save();
        return back()->with('success', 'Journey Started');
    }
    public function journey_view_map(Request $request)
    {
        $route = Route::with('routeBusStop')->findOrFail($request->route_id);
        $selectedBusStops = BusStop::whereIn('id', $route->routeBusStop->pluck('bus_stop_id'))
            ->orderByRaw("FIELD(id, " . $route->routeBusStop->pluck('bus_stop_id')->implode(',') . ")")
            ->get();

        $busStopsArray = collect();

        foreach ($selectedBusStops as $stop) {
            $busStopsArray->push([
                'id' => $stop->id,
                'name' => $stop->name,
                'type' => 'mid',
                'arrival_time' => JourneyStoppage::where('journey_id', $request->journey_id)
                    ->where('stop_id', $stop->id)
                    ->value('arrival_time')
            ]);
        }

        $routeId = $request->route_id;
        $busRouteStops = BusRouteStop::where('bus_route_id', $routeId)
            ->orderBy('sl_no')
            ->with('busStop')
            ->get();

        return response()->json([
            'success' => true,
            'busStops' => $busStopsArray,
            'normalRoute' => $busRouteStops
        ]);
    }

    public function journey_delete($journey_id)
    {
        $journey = Journey::find($journey_id);

        if (!$journey) {
            return response()->json(['error' => 'Journey not found.'], 404);
        }
        JourneyStoppage::where('journey_id', $journey_id)->delete();
        $journey->delete();

        return response()->json(['success' => 'Journey Deleted Successfully']);
    }
}
