<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Http\Requests\Admin\SendAadhaarOtpRequest;
use App\Http\Requests\Admin\VerifyAadhaarOtpRequest;
use App\Http\Requests\Admin\VerifyPanRequest;
use App\Services\BankU\Exceptions\BankUConnectionException;

/**
 * Shared PAN / Aadhaar verification endpoints for any admin controller that
 * collects identity documents for a User record (Driver, Company, Branch,
 * Employee, ...). The using class must expose a BankUIdentityService via a
 * $this->bankUIdentityService property (constructor-injected).
 */
trait VerifiesBankUIdentity
{
    use HandlesBankUResponses;

    public function verifyPan(VerifyPanRequest $request)
    {
        try {
            $result = $this->bankUIdentityService->verifyPan($request->string('pan_card_number'));
        } catch (BankUConnectionException $e) {
            return $this->bankUUnavailableResponse();
        }

        return $this->bankUResponse($result, 'PAN verification failed.');
    }

    public function sendAadhaarOtp(SendAadhaarOtpRequest $request)
    {
        try {
            $result = $this->bankUIdentityService->sendAadhaarOtp($request->string('aadhaar_number'));
        } catch (BankUConnectionException $e) {
            return $this->bankUUnavailableResponse();
        }

        return $this->bankUResponse($result, 'Unable to send Aadhaar OTP.');
    }

    public function verifyAadhaarOtp(VerifyAadhaarOtpRequest $request)
    {
        try {
            $result = $this->bankUIdentityService->verifyAadhaarOtp(
                $request->string('otp'),
                $request->string('ref_id'),
            );
        } catch (BankUConnectionException $e) {
            return $this->bankUUnavailableResponse();
        }

        return $this->bankUResponse($result, 'Aadhaar OTP verification failed.');
    }
}
