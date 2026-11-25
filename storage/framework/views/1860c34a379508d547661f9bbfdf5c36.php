

<?php if (! $__env->hasRenderedOnce('a5fdb34a-eade-4b96-a507-7bd9fc22ecb7')): $__env->markAsRenderedOnce('a5fdb34a-eade-4b96-a507-7bd9fc22ecb7'); ?>
    <style>
        .modal-dl-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 4px 16px;
        }
        .modal-dl-grid .modal-dl-full {
            grid-column: 1 / -1;
        }
        @media (max-width: 767.98px) {
            .modal-dl-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
<?php endif; ?>

<div class="col-12 mb-2">
    <details id="modalDlDetailsWrap">
        <summary class="fw-semibold text-primary" style="cursor:pointer;">
            Driving Licence Details <small class="text-muted">(filled from the Fetch step &mdash; editable)</small>
        </summary>
        <div class="modal-dl-grid mt-3">
            <?php $__currentLoopData = \App\Models\Driver::DL_FIELDS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dlKey => $dlLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-2 <?php echo e($dlKey === 'dl_address' ? 'modal-dl-full' : ''); ?>">
                    <label class="form-label mb-1" for="modal_<?php echo e($dlKey); ?>"><?php echo e($dlLabel); ?></label>
                    <?php if($dlKey === 'dl_address'): ?>
                        <textarea class="form-control" rows="2" name="<?php echo e($dlKey); ?>"
                            id="modal_<?php echo e($dlKey); ?>"><?php echo e(old($dlKey)); ?></textarea>
                    <?php else: ?>
                        <input type="text" class="form-control" name="<?php echo e($dlKey); ?>" id="modal_<?php echo e($dlKey); ?>"
                            value="<?php echo e(old($dlKey)); ?>">
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </details>
</div>

<div class="col-12"><hr class="my-2"></div>

<div class="mb-3 col-md-4">
    <label class="form-label">Aadhaar Card Number</label>
    <div class="input-group">
        <input type="text" class="form-control" name="aadhaar_number" id="modal_aadhaar_number"
            value="<?php echo e(old('aadhaar_number')); ?>" maxlength="12">
        <button type="button" class="btn btn-grd-info text-light" id="modalSendAadhaarOtpBtn">Send OTP</button>
    </div>
    <small id="modalAadhaarVerifyStatus" class="d-block mt-1"></small>
    <input type="hidden" name="aadhaar_verification_data" id="modal_aadhaar_verification_data">
    <input type="hidden" id="modal_aadhaar_ref_id">
</div>

<div class="mb-3 col-md-4" id="modalAadhaarOtpWrap" style="display:none;">
    <label class="form-label">Aadhaar OTP</label>
    <div class="input-group">
        <input type="text" class="form-control" id="modal_aadhaar_otp" maxlength="6" placeholder="6-digit OTP">
        <button type="button" class="btn btn-grd-info text-light" id="modalVerifyAadhaarOtpBtn">Verify OTP</button>
    </div>
</div>

<div class="mb-3 col-md-4">
    <label class="form-label">PAN Card Number</label>
    <div class="input-group">
        <input type="text" class="form-control text-uppercase" name="pan_card_number" id="modal_pan_card_number"
            value="<?php echo e(old('pan_card_number')); ?>" maxlength="10">
        <button type="button" class="btn btn-grd-info text-light" id="modalVerifyPanBtn">Verify</button>
    </div>
    <small id="modalPanVerifyStatus" class="d-block mt-1"></small>
    <input type="hidden" name="pan_verification_data" id="modal_pan_verification_data">
</div>
<?php /**PATH /home/rqodasyj/app.vlocus.in/resources/views/admin/delivery_schedules/_add_driver_modal_extra.blade.php ENDPATH**/ ?>