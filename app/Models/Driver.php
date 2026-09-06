<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
}
