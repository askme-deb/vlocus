<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, InteractsWithMedia ,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'status',
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'opt_mobile_no',
        'password',
        'date_of_birth',
        'gender',
        'address',
        'pan_card_number',
        'pan_verified_at',
        'pan_verification_data',
        'aadhar_card_number',
        'aadhaar_verified_at',
        'aadhaar_verification_data',
        'otp',
        'otp_timestamp',
        'fcm_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'pan_verified_at' => 'datetime',
            'pan_verification_data' => 'array',
            'aadhaar_verified_at' => 'datetime',
            'aadhaar_verification_data' => 'array',
        ];
    }

    public function business()
    {
        return $this->hasOne(UserBusiness::class, 'user_id', 'id');
    }

    public function audit()
    {
        return $this->hasMany(Audit::class);
    }
    public function driver()
    {
        return $this->hasOne(Driver::class, 'user_id');
    }
    
    public function currentActiveDelivery()
    {
        return $this->hasOne(DeliverySchedule::class, 'driver_id', 'id')
            ->where('is_completed', '!=', 1)
            ->latestOfMany('delivery_date');
    }
    
    public function wallet()
    {
        return $this->hasOne(Wallet::class,'user_id');
    }
    public function conductor()
    {
        return $this->hasOne(Conductor::class, 'user_id');
    }

    public function branch()
    {
        return $this->hasOne(Branch::class, 'user_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'user_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    public function companyEmployees()
    {
        return $this->hasMany(Employee::class, 'company_id');
    }

    public function branchEmployees()
    {
        return $this->hasMany(Employee::class, 'branch_id');
    }

    /**
     * Resolve the id of the company this user belongs to, regardless of
     * whether they are the company account itself, a branch, an employee,
     * or a driver.
     */
    public function companyId(): ?int
    {
        if ($this->hasRole('Company')) {
            return $this->id;
        }

        return $this->branch?->company_id
            ?? $this->employee?->company_id
            ?? $this->driver?->company_id
            ?? null;
    }

    /**
     * Resolve the company (User with role "Company") this user belongs to.
     */
    public function company(): ?self
    {
        if ($this->hasRole('Company')) {
            return $this;
        }

        $companyId = $this->companyId();

        return $companyId ? self::find($companyId) : null;
    }

    public function specialization()
    {
        return $this->belongsToMany(
            CertificationType::class,
            'auditorspecializations',
            'auditor_id',
            'certificate_id'
        );
    }
}
