<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Driver extends Model implements HasMedia
{
    //
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'driving_license_number',
        'driving_license_verified_at',
        'driving_license_verification_data',
        'dl_status',
        'holder_name',
        'father_or_husband_name',
        'dl_dob',
        'dl_issue_date',
        'dl_address',
        'class_of_vehicle',
        'dl_verification_id',
        'dl_transaction_id',
        'issuing_state',
        'dl_nt_valid_from',
        'dl_nt_valid_to',
        'dl_tr_valid_from',
        'dl_tr_valid_to',
        'vehicle_type',
        'driving_exprience',
        'company_id',
        'vehicle_id',
        'is_online',
        'ride_mode',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'driving_license_verified_at' => 'datetime',
        'driving_license_verification_data' => 'array',
    ];

    /**
     * Driving-licence detail fields shared by the create/edit forms and the
     * detail view, in display order. Keyed by column name => human label.
     * `driving_license_number` is rendered separately as the first row.
     */
    public const DL_FIELDS = [
        'dl_status' => 'D.L Status',
        'dl_dob' => 'Date Of Birth',
        'holder_name' => 'Holder Name',
        'father_or_husband_name' => "Father's Name",
        'dl_address' => 'Address',
        'dl_issue_date' => 'D.L Issue Date',
        'class_of_vehicle' => 'Class Of Vehicle',
        'dl_verification_id' => 'Verification ID',
        'dl_transaction_id' => 'Transaction ID',
        'issuing_state' => 'Issuing State',
        'dl_nt_valid_from' => 'Valid From (Non-Transport)',
        'dl_nt_valid_to' => 'Valid Upto (Non-Transport)',
        'dl_tr_valid_from' => 'Valid From (Transport)',
        'dl_tr_valid_to' => 'Valid Upto (Transport)',
    ];

    /**
     * Driving-licence numbers are always shown and stored in upper case.
     */
    protected function drivingLicenseNumber(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === null ? null : strtoupper($value),
            set: fn ($value) => $value === null ? null : strtoupper($value),
        );
    }

    public function bankAccount()
    {
        return $this->hasOne(DriverBankAccount::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'id');
    }
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id','id');
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }
    public function locations()
    {
        return $this->hasMany(DriverLocation::class, 'driver_id');
    }

    public function latestLocation()
    {
        return $this->hasOne(DriverLocation::class, 'driver_id')->latestOfMany();
    }

    public function currentActiveDelivery()
    {
        return $this->hasOne(DeliverySchedule::class, 'driver_id', 'user_id')
            ->where('is_completed', '!=', 1)
            ->latestOfMany('delivery_date');
    }

    /**
     * True when every driving-licence validity date that is present
     * (transport and/or non-transport) has already passed. Dates are stored
     * as free-form strings from the BankU lookup, so parse defensively.
     */
    public function drivingLicenceExpired(): bool
    {
        $dates = collect([$this->dl_tr_valid_to, $this->dl_nt_valid_to])
            ->filter()
            ->map(function ($value) {
                try {
                    return Carbon::parse($value);
                } catch (\Exception $e) {
                    return null;
                }
            })
            ->filter();

        return $dates->isNotEmpty() && $dates->max()->isPast();
    }

    /**
     * Deactivate (users.status = 0) every currently-active driver whose
     * driving-licence validity has fully expired. Returns the affected user ids.
     *
     * @return array<int, int>
     */
    public static function deactivateExpiredLicenceHolders(): array
    {
        $expiredUserIds = static::query()
            ->with('user:id,status')
            ->whereHas('user', fn ($q) => $q->where('status', 1))
            ->get()
            ->filter->drivingLicenceExpired()
            ->pluck('user_id')
            ->filter()
            ->values();

        if ($expiredUserIds->isNotEmpty()) {
            User::whereIn('id', $expiredUserIds)->update(['status' => 0]);
        }

        return $expiredUserIds->all();
    }
}
