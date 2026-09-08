<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Vehicle extends Model implements HasMedia
{
    //
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'vehicle_number',
        'rwc_number',
        'rc_verified_at',
        'rc_verification_data',
        'engine_number',
        'brand_id',
        'model_id',
        'color_id',
        'seating_capacity',
        'body_type',
        'vehicle_condition',
        'transmisssion',
        'fuel_type',
        'left_hand_drive',
        'hybird',
        'engine_type',
        'description',
        'is_visible',
        'layout_id',
        'vehicle_type',
        'ac_status',
        'seat_booking_price',
        'route_id',
        'company_id',

        // RC registration details (BankU RC verify)
        'rc_status',
        'vehicle_class',
        'chassis_number',
        'manufacturer',
        'model_name',
        'colour',
        'emission_norm',
        'owner_name',
        'registration_date',
        'rc_expiry_date',
        'tax_upto',
        'insurance_company',
        'insurance_upto',
        'financer',
        'owner_address',
        'cubic_capacity',
        'gross_weight',
        'seat_capacity',
        'sleeper_capacity',
        'pucc_number',
        'pucc_upto',
        'permit_type',
        'permit_valid_upto',
        'national_permit_number',
        'national_permit_upto',
        'is_commercial',
    ];

    protected $casts = [
        'rc_verified_at' => 'datetime',
        'rc_verification_data' => 'array',
        'is_commercial' => 'boolean',
    ];

    /**
     * RC detail fields shared by the create/edit forms and the detail view,
     * in display order. Keyed by column name => human label.
     */
    public const RC_FIELDS = [
        'rc_status' => 'Vehicle RC Status',
        'vehicle_class' => 'Vehicle Class',
        'chassis_number' => 'Chassis No.',
        'engine_number' => 'Engine No.',
        'manufacturer' => 'Manufacturer',
        'model_name' => 'Model',
        'colour' => 'Colour',
        'fuel_type' => 'Type',
        'emission_norm' => 'Vehicle Type',
        'owner_name' => 'Owner Name',
        'registration_date' => 'Registration Date',
        'rc_expiry_date' => 'RC Expiry Date',
        'tax_upto' => 'Vehicle Tax Upto',
        'insurance_company' => 'Vehicle Insurance',
        'insurance_upto' => 'Insurance Upto',
        'financer' => 'Vehicle Financer',
        'owner_address' => 'Address',
        'cubic_capacity' => 'Vehicle Capacity',
        'gross_weight' => 'Vehicle Weight',
        'seat_capacity' => 'Vehicle Seat Capacity',
        'sleeper_capacity' => 'Sleeper Capacity',
        'pucc_number' => 'Vehicle PUCC Number',
        'pucc_upto' => 'Vehicle PUCC Upto',
        'permit_type' => 'Vehicle Permit Type',
        'permit_valid_upto' => 'Vehicle Permit Valid',
        'national_permit_number' => 'National Permit Number',
        'national_permit_upto' => 'National Permit Upto',
        'is_commercial' => 'Vehicle Is Commercial',
    ];

    /**
     * Subset of RC_FIELDS that hold dates. Stored as plain strings (upstream
     * BankU formats vary), so display code should run them through
     * safe_format_date() rather than assuming a Carbon-castable column.
     */
    public const RC_DATE_FIELDS = [
        'registration_date',
        'rc_expiry_date',
        'tax_upto',
        'insurance_upto',
        'pucc_upto',
        'permit_valid_upto',
        'national_permit_upto',
    ];

    public function layout()
    {
        return $this->belongsTo(VehicleLayout::class, 'layout_id', 'id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type', 'id');
    }
    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id','id');
    }

    public function timeTable()
    {
        return $this->hasMany(TimeTable::class, 'vehicle_id');
    }

    public function journeys()
    {
        return $this->hasMany(Journey::class, 'vehicle_id');
    }

    /**
     * Compliance validity dates checked before a vehicle may be dispatched,
     * keyed by column name => human label.
     */
    public const COMPLIANCE_DATES = [
        'insurance_upto' => 'Insurance',
        'pucc_upto'      => 'PUCC',
        'rc_expiry_date' => 'RC',
    ];

    /**
     * Labels of the compliance dates that have already passed (Insurance / PUCC
     * / RC). Dates are stored as free-form strings from the RC lookup, so parse
     * defensively. Empty array = nothing expired.
     *
     * @return array<int, string>
     */
    public function expiredComplianceReasons(): array
    {
        $expired = [];

        foreach (self::COMPLIANCE_DATES as $column => $label) {
            $value = $this->{$column};

            if (blank($value)) {
                continue;
            }

            try {
                if (Carbon::parse($value)->isPast()) {
                    $expired[] = $label;
                }
            } catch (\Exception $e) {
                // Unparseable date - ignore rather than block the vehicle.
            }
        }

        return $expired;
    }

    /**
     * Deactivate (is_visible = 0) every currently-visible vehicle whose
     * insurance, PUCC or RC validity has expired. Returns the affected ids.
     *
     * @return array<int, int>
     */
    public static function deactivateExpiredCompliance(): array
    {
        $expiredIds = static::query()
            ->where('is_visible', 1)
            ->get()
            ->filter(fn (self $vehicle) => ! empty($vehicle->expiredComplianceReasons()))
            ->pluck('id')
            ->values();

        if ($expiredIds->isNotEmpty()) {
            static::whereIn('id', $expiredIds)->update(['is_visible' => 0]);
        }

        return $expiredIds->all();
    }
}
