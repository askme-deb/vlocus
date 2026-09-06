<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CompanyApiRate extends Model
{
    /**
     * The fixed set of chargeable document-verification APIs, keyed by the
     * value stored in api_key. Mirrors the Vehicle::RC_FIELDS / Driver::DL_FIELDS
     * key => label pattern -- this is the one place a new chargeable API type
     * would be added.
     */
    public const API_TYPES = [
        'pan' => 'PAN',
        'aadhaar' => 'Aadhaar',
        'driving_licence' => 'Driving Licence',
        'rc' => 'RC',
        'gstin' => 'GSTIN',
        'bank' => 'Bank Account',
        'ifsc' => 'IFSC',
    ];

    public const FILTER_TYPES = self::API_TYPES;

    protected $fillable = ['company_id', 'api_key', 'amount', 'is_enabled'];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_enabled' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    /**
     * Idempotently create any missing rate rows for this company (amount 0,
     * disabled) so the Settings page always has all API_TYPES to render.
     */
    public static function ensureDefaultsFor(int $companyId): void
    {
        $now = now();

        $rows = array_map(fn ($key) => [
            'company_id' => $companyId,
            'api_key' => $key,
            'amount' => 0,
            'is_enabled' => false,
            'created_at' => $now,
            'updated_at' => $now,
        ], array_keys(self::API_TYPES));

        DB::table('company_api_rates')->insertOrIgnore($rows);
    }
}
