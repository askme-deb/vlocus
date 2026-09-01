{{--
    Extra "Add New Driver" quick-modal fields: editable BankU driving-licence
    detail fields (populated by the Fetch step) + Aadhaar / PAN KYC.
    Shared by delivery_schedules/create.blade.php and edit.blade.php.
    All ids are prefixed `modal_` so they don't collide with the full driver
    form; input `name`s match what DriverController@storeFromModal expects.
--}}

@once
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
@endonce

<div class="col-12 mb-2">
    <details id="modalDlDetailsWrap">
        <summary class="fw-semibold text-primary" style="cursor:pointer;">
            Driving Licence Details <small class="text-muted">(filled from the Fetch step &mdash; editable)</small>
        </summary>
        <div class="modal-dl-grid mt-3">
            @foreach (\App\Models\Driver::DL_FIELDS as $dlKey => $dlLabel)
                <div class="mb-2 {{ $dlKey === 'dl_address' ? 'modal-dl-full' : '' }}">
                    <label class="form-label mb-1" for="modal_{{ $dlKey }}">{{ $dlLabel }}</label>
                    @if ($dlKey === 'dl_address')
                        <textarea class="form-control" rows="2" name="{{ $dlKey }}"
                            id="modal_{{ $dlKey }}">{{ old($dlKey) }}</textarea>
                    @else
                        <input type="text" class="form-control" name="{{ $dlKey }}" id="modal_{{ $dlKey }}"
                            value="{{ old($dlKey) }}">
                    @endif
                </div>
            @endforeach
        </div>
    </details>
</div>

<div class="col-12"><hr class="my-2"></div>

<div class="mb-3 col-md-4">
    <label class="form-label">Aadhaar Card Number</label>
    <div class="input-group">
        <input type="text" class="form-control" name="aadhaar_number" id="modal_aadhaar_number"
            value="{{ old('aadhaar_number') }}" maxlength="12">
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
            value="{{ old('pan_card_number') }}" maxlength="10">
        <button type="button" class="btn btn-grd-info text-light" id="modalVerifyPanBtn">Verify</button>
    </div>
    <small id="modalPanVerifyStatus" class="d-block mt-1"></small>
    <input type="hidden" name="pan_verification_data" id="modal_pan_verification_data">
</div>
