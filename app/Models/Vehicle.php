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
            $date = self::parseRcDate($this->{$column});

            // Blank or unparseable dates are ignored here rather than
            // blocking the vehicle; complianceIssues() is the strict check.
            if ($date && $date->isPast()) {
                $expired[] = $label;
            }
        }

        return $expired;
    }

    /**
     * Strict compliance check used to decide whether a vehicle may be
     * (re)activated after an RC re-fetch: every compliance date must be
     * present, a real date and not yet passed, and the RC itself must be
     * ACTIVE when BankU reports a status. Returns human-readable problems;
     * an empty array means the vehicle is fully compliant.
     *
     * @return array<int, string>
     */
    public function complianceIssues(): array
    {
        $issues = [];

        foreach (self::COMPLIANCE_DATES as $column => $label) {
            $value = $this->{$column};

            if (blank($value)) {
                $issues[] = "{$label} validity date is missing";
                continue;
            }

            $date = self::parseRcDate($value);

            if (! $date) {
                $issues[] = "{$label} validity date \"{$value}\" is invalid";
            } elseif ($date->isPast()) {
                $issues[] = "{$label} expired on " . $date->format('d-m-Y');
            }
        }

        if (filled($this->rc_status) && strtoupper(trim((string) $this->rc_status)) !== 'ACTIVE') {
            $issues[] = "RC status is {$this->rc_status}";
        }

        return $issues;
    }

    /**
     * Parse a free-form RC date string (BankU formats vary: 2027-10-19,
     * 19-10-2027, 19/10/2027, 19-Oct-2027, ...). Returns the END of that day,
     * since a document is valid through its "upto" date, or null when the
     * value is blank, unparseable or has an implausible year (e.g. a typo
     * like "20267-10-19").
     */
    public static function parseRcDate(mixed $value): ?Carbon
    {
        if (blank($value)) {
            return null;
        }

        $value = trim((string) $value);
        $date = null;

        foreach (['Y-m-d', 'd-m-Y', 'd/m/Y', 'Y/m/d', 'd.m.Y', 'd-M-Y', 'd M Y', 'd-M-y'] as $format) {
            try {
                $parsed = Carbon::createFromFormat('!' . $format, $value);
            } catch (\Throwable $e) {
                continue;
            }

            if ($parsed && $parsed->format($format) === $value) {
                $date = $parsed;
                break;
            }
        }

        if (! $date) {
            // Free-form fallback. Reject values with a 5+ digit number or a
            // year that isn't actually in the text (PHP reads "20267-10-19"
            // as 2007-10-19, which would be silently wrong).
            if (preg_match('/\d{5,}/', $value)) {
                return null;
            }

            try {
                $date = Carbon::parse($value);
            } catch (\Throwable $e) {
                return null;
            }

            if (! str_contains($value, (string) $date->year)) {
                return null;
            }
        }

        if ($date->year < 1950 || $date->year > 2200) {
            return null;
        }

        return $date->endOfDay();
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
