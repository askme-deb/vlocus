<?php

namespace App\Services\BankU;

use App\Services\BankU\DataTransferObjects\BankUResponse;

/**
 * Domain-facing wrapper around BankU's reseller identity verification APIs.
 *
 * @see https://erp.banku.co.in (BankU Identity Verification APIs collection)
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

    public function verifyRc(string $vehicleRegistrationNumber, float $latitude, float $longitude): BankUResponse
    {
        return $this->client->post('/api/reseller/v1/identity/rc/verify', [
            'vehicleRegistrationNumber' => $vehicleRegistrationNumber,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'consent' => 'Y',
        ]);
    }

    public function verifyPassport(string $passportFileNumber, string $dob, float $latitude, float $longitude): BankUResponse
    {
        return $this->client->post('/api/reseller/v1/identity/passport/verify', [
            'passportFileNumber' => $passportFileNumber,
            'dob' => $dob,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }

    public function verifyVoterId(string $voterId, float $latitude, float $longitude): BankUResponse
    {
        return $this->client->post('/api/reseller/v1/identity/voter-id/verify', [
            'voterId' => $voterId,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'consent' => 'Y',
        ]);
    }

    public function verifyDrivingLicense(string $drivingLicenseNumber, string $dob, float $latitude, float $longitude): BankUResponse
    {
        return $this->client->post('/api/reseller/v1/identity/driving-license/verify', [
            'drivingLicenseNumber' => $drivingLicenseNumber,
            'dob' => $dob,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'consent' => 'Y',
        ]);
    }

    public function verifyGstin(string $gstNumber): BankUResponse
    {
        return $this->client->post('/api/reseller/v1/tax/gstin/verify', [
            'gstNumber' => $gstNumber,
        ]);
    }
}
