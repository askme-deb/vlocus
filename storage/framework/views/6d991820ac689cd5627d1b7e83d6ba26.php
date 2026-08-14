

<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label">Aadhar Card Number <span class="text-danger"></span></label>
    <div class="col-md-9">
        <div class="input-group">
            <input type="text" class="form-control" name="aadhaar_number"
                id="aadhaar_number" value="<?php echo e(old('aadhaar_number')); ?>">
            <button type="button" class="btn btn-grd-info text-light" id="sendAadhaarOtpBtn">Send OTP</button>
        </div>
        <small id="aadhaarVerifyStatus" class="d-block mt-1"></small>
        <input type="hidden" name="aadhaar_verification_data" id="aadhaar_verification_data" value="<?php echo e(old('aadhaar_verification_data')); ?>">
        <input type="hidden" id="aadhaar_ref_id">
    </div>
</div>
<div class="form-group row mb-3" id="aadhaarOtpWrap" style="display: none;">
    <label class="col-md-3 col-form-label">Aadhaar OTP</label>
    <div class="col-md-9">
        <div class="input-group">
            <input type="text" class="form-control" id="aadhaar_otp" maxlength="6"
                placeholder="Enter 6-digit OTP">
            <button type="button" class="btn btn-grd-info text-light" id="verifyAadhaarOtpBtn">Verify OTP</button>
        </div>
    </div>
</div>

<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label">Pan Card Number <span class="text-danger"></span></label>
    <div class="col-md-9">
        <div class="input-group">
            <input type="text" class="form-control" name="pan_card_number"
                id="pan_card_number" value="<?php echo e(old('pan_card_number')); ?>">
            <button type="button" class="btn btn-grd-info text-light" id="verifyPanBtn">Verify</button>
        </div>
        <small id="panVerifyStatus" class="d-block mt-1"></small>
        <input type="hidden" name="pan_verification_data" id="pan_verification_data" value="<?php echo e(old('pan_verification_data')); ?>">
    </div>
</div>
<?php /**PATH D:\projects\vlocus\resources\views/admin/partials/pan-aadhaar-verification-fields.blade.php ENDPATH**/ ?>