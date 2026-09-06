<?php
    /**
     * Shared Aadhaar + PAN KYC inputs for the driver create/edit forms.
     * $data is the User model on edit, null on create.
     */
    $data = $data ?? null;

    $aadhaarNumber = old('aadhaar_number', optional($data)->aadhar_card_number);
    $panNumber = old('pan_card_number', optional($data)->pan_card_number);

    $aadhaarPayload = old('aadhaar_verification_data');
    if ($aadhaarPayload === null && $data && !empty($data->aadhaar_verification_data)) {
        $aadhaarPayload = json_encode($data->aadhaar_verification_data);
    }

    $panPayload = old('pan_verification_data');
    if ($panPayload === null && $data && !empty($data->pan_verification_data)) {
        $panPayload = json_encode($data->pan_verification_data);
    }
?>

<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label">Aadhaar Card Number</label>
    <div class="col-md-9">
        <div class="input-group">
            <input type="text" class="form-control" name="aadhaar_number" id="aadhaar_number"
                value="<?php echo e($aadhaarNumber); ?>" maxlength="12">
            <button type="button" class="btn btn-grd-info text-light" id="sendAadhaarOtpBtn">Send OTP</button>
        </div>
        <small id="aadhaarVerifyStatus" class="d-block mt-1"></small>
        <input type="hidden" name="aadhaar_verification_data" id="aadhaar_verification_data" value="<?php echo e($aadhaarPayload); ?>">
        <input type="hidden" id="aadhaar_ref_id">
    </div>
</div>

<div class="form-group row mb-3" id="aadhaarOtpWrap" style="display: none;">
    <label class="col-md-3 col-form-label">Aadhaar OTP</label>
    <div class="col-md-9">
        <div class="input-group">
            <input type="text" class="form-control" id="aadhaar_otp" maxlength="6" placeholder="Enter 6-digit OTP">
            <button type="button" class="btn btn-grd-info text-light" id="verifyAadhaarOtpBtn">Verify OTP</button>
        </div>
    </div>
</div>

<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label">PAN Card Number</label>
    <div class="col-md-9">
        <div class="input-group">
            <input type="text" class="form-control text-uppercase" name="pan_card_number" id="pan_card_number"
                value="<?php echo e($panNumber); ?>" maxlength="10">
            <button type="button" class="btn btn-grd-info text-light" id="verifyPanBtn">Verify</button>
        </div>
        <small id="panVerifyStatus" class="d-block mt-1"></small>
        <input type="hidden" name="pan_verification_data" id="pan_verification_data" value="<?php echo e($panPayload); ?>">
    </div>
</div>
<?php /**PATH D:\projects\vlocus\resources\views/admin/driver/_kyc_fields.blade.php ENDPATH**/ ?>