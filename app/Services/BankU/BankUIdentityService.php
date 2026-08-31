<?php

namespace App\Services\BankU;

use App\Services\BankU\DataTransferObjects\BankUResponse;
use Illuminate\Support\Str;

/**
 * Domain-facing wrapper around BankU's reseller identity verification APIs.
 *
 * @see https://app.banku.co.in/api/reseller/v1/ (BankU Reseller Identity Verification APIs)
 */
class BankUIdentityService
{
    public function __construct(private readonly BankUClient $client)
    {
    }

    public function sendAadhaarOtp(string $aadhaar): BankUResponse
    {
        return $this->client->post('/api/reseller/v1/aadhaar/send-otp', [
            'aadhaar' => $aadhaar,
        ]);
    }

    public function verifyAadhaarOtp(string $otp, string $refId): BankUResponse
    {
        return $this->client->post('/api/reseller/v1/aadhaar/verify-otp', [
            'otp' => $otp,
            'ref_id' => $refId,
        ]);
    }

    public function verifyPan(string $pan): BankUResponse
    {
        return $this->client->post('/api/reseller/v1/pan/verify', [
            'pan' => $pan,
        ]);
    }

    public function verifyRc(string $vehicleRegistrationNumber, ?string $verificationId = null): BankUResponse
    {
        return $this->client->post('/api/reseller/v1/identity/rc/verify', [
            'vehicleRegistrationNumber' => $vehicleRegistrationNumber,
            'verification_id' => $verificationId ?? ('rc_' . Str::uuid()),
        ]);
    }

    public function verifyDrivingLicense(string $drivingLicenseNumber, string $dob, ?string $verificationId = null): BankUResponse
    {
        return $this->client->post('/api/reseller/v1/identity/driving-license/verify', [
            'drivingLicenseNumber' => $drivingLicenseNumber,
            'dob' => $dob,
            'verification_id' => $verificationId ?? ('dl_' . Str::uuid()),
        ]);
    }

    public function verifyGstin(string $gstNumber): BankUResponse
    {
        return $this->client->post('/api/reseller/v1/tax/gstin/verify', [
            'gstNumber' => $gstNumber,
        ]);
    }
}
