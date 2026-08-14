{{--
    Script half of the shared PAN + Aadhaar verification partial. Include
    inside @section('scripts') -- NOT inline in @section('content') -- so
    it runs after jQuery has loaded (jQuery is only pulled in at the very
    bottom of the layout via layouts.admin-include.scripts). Pair with
    admin.partials.pan-aadhaar-verification-fields for the HTML.

    @include('admin.partials.pan-aadhaar-verification-scripts', [
        'panVerifyRoute' => 'company.verifyPan',
        'aadhaarSendOtpRoute' => 'company.aadhaarSendOtp',
        'aadhaarVerifyOtpRoute' => 'company.aadhaarVerifyOtp',
    ])

    Auto-fills name / address / date_of_birth / gender inputs when present
    on the page (by id="first_name"/"last_name"/"address"/"date_of_birth"
    and name="gender") without erroring when they aren't -- jQuery no-ops
    on empty selectors, so this partial is safe to reuse on forms that
    don't collect all of those fields (e.g. Company/Branch).
--}}
<script>
    function normalizeDobForInput(value) {
        if (!value) return '';
        value = String(value).trim();

        if (/^\d{4}-\d{2}-\d{2}$/.test(value)) {
            return value;
        }

        let match = value.match(/^(\d{2})[\/\-](\d{2})[\/\-](\d{4})$/);
        if (match) {
            return `${match[3]}-${match[2]}-${match[1]}`;
        }

        return '';
    }

    // Gender isn't guaranteed from every verification source, so whichever
    // of Aadhaar / PAN responds with a recognizable value first fills it.
    function applyGenderFromApi(rawValue) {
        if (!rawValue) return;

        let genderMap = {
            m: 'male',
            male: 'male',
            f: 'female',
            female: 'female',
            o: 'others',
            others: 'others',
            other: 'others',
            transgender: 'others'
        };

        let mapped = genderMap[String(rawValue).trim().toLowerCase()];
        if (mapped) {
            $('select[name="gender"]').val(mapped);
        }
    }

    let panVerified = false;

    function lockPanField() {
        panVerified = true;
        $('#pan_card_number').prop('readonly', true);
        $('#verifyPanBtn')
            .prop('disabled', true)
            .removeClass('btn-grd-info')
            .addClass('btn-grd-success')
            .html('<i class="bx bx-check"></i> Verified');
    }

    $('#pan_card_number').on('input', function() {
        if (panVerified) return;
        $('#pan_verification_data').val('');
        $('#panVerifyStatus').removeClass('text-success text-danger').text('');
    });

    $(function() {
        if ($('#pan_verification_data').val()) {
            lockPanField();
            $('#panVerifyStatus').removeClass('text-danger').addClass('text-success')
                .text('PAN verified successfully.');
        }
    });

    $('#verifyPanBtn').on('click', function() {
        if (panVerified) return;

        let panNumber = $('#pan_card_number').val().trim().toUpperCase();
        let $btn = $(this);
        let $status = $('#panVerifyStatus');

        $status.removeClass('text-success text-danger').text('');
        $('#pan_verification_data').val('');

        if (!panNumber) {
            round_error_noti('Please enter the PAN number first.');
            return;
        }
        if (!/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(panNumber)) {
            round_error_noti('Please enter a valid PAN number (e.g. ABCDE1234F).');
            return;
        }

        $('#pan_card_number').val(panNumber);

        $.ajax({
            url: '{{ route($panVerifyRoute) }}',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                pan_card_number: panNumber,
            },
            beforeSend: function() {
                $btn.prop('disabled', true).text('Verifying...');
            },
            success: function(response) {
                if (response.success) {
                    let data = response.data || {};
                    console.log('BankU PAN verify response:', data);

                    let fullName = data.name || data.registered_name || data.full_name || '';
                    if (fullName && !$('#first_name').val()) {
                        let parts = fullName.trim().split(/\s+/);
                        $('#first_name').val(parts.shift());
                        $('#last_name').val(parts.join(' '));
                    }

                    applyGenderFromApi(data.gender || data.sex);

                    // Keep the full verified payload so it's persisted with the
                    // user record on submit, even for fields with no matching input.
                    $('#pan_verification_data').val(JSON.stringify(data));

                    $status.removeClass('text-danger').addClass('text-success')
                        .text('PAN verified successfully.');
                    round_success_noti('PAN verified successfully.');

                    // Lock the PAN number so a verified PAN can't be
                    // re-verified or swapped out after the fact.
                    lockPanField();
                } else {
                    $status.removeClass('text-success').addClass('text-danger')
                        .text(response.message || 'PAN verification failed.');
                    round_error_noti(response.message || 'PAN verification failed.');
                }
            },
            error: function(xhr) {
                let errorMessage = 'An unexpected error occurred while verifying the PAN.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                $status.removeClass('text-success').addClass('text-danger').text(errorMessage);
                round_error_noti(errorMessage);
            },
            complete: function() {
                if (!panVerified) {
                    $btn.prop('disabled', false).text('Verify');
                }
            }
        });
    });

    let aadhaarVerified = false;

    function lockAadhaarFields() {
        aadhaarVerified = true;
        $('#aadhaar_number').prop('readonly', true);
        $('#aadhaar_otp').prop('readonly', true);
        $('#sendAadhaarOtpBtn')
            .prop('disabled', true)
            .removeClass('btn-grd-info')
            .addClass('btn-grd-success')
            .html('<i class="bx bx-check"></i> Verified');
        $('#verifyAadhaarOtpBtn')
            .prop('disabled', true)
            .removeClass('btn-grd-info')
            .addClass('btn-grd-success')
            .html('<i class="bx bx-check"></i> Verified');
        $('#aadhaarOtpWrap').hide();
    }

    $('#aadhaar_number').on('input', function() {
        if (aadhaarVerified) return;
        $('#aadhaar_verification_data').val('');
        $('#aadhaar_ref_id').val('');
        $('#aadhaarOtpWrap').hide();
        $('#aadhaarVerifyStatus').removeClass('text-success text-danger').text('');
    });

    $(function() {
        if ($('#aadhaar_verification_data').val()) {
            lockAadhaarFields();
            $('#aadhaarVerifyStatus').removeClass('text-danger').addClass('text-success')
                .text('Aadhaar verified successfully.');
        }
    });

    $('#sendAadhaarOtpBtn').on('click', function() {
        if (aadhaarVerified) return;

        let aadhaarNumber = $('#aadhaar_number').val().trim();
        let $btn = $(this);
        let $status = $('#aadhaarVerifyStatus');

        $status.removeClass('text-success text-danger').text('');
        $('#aadhaar_verification_data').val('');
        $('#aadhaar_ref_id').val('');
        $('#aadhaarOtpWrap').hide();

        if (!/^\d{12}$/.test(aadhaarNumber)) {
            round_error_noti('Please enter a valid 12-digit Aadhaar number.');
            return;
        }

        $.ajax({
            url: '{{ route($aadhaarSendOtpRoute) }}',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                aadhaar_number: aadhaarNumber,
            },
            beforeSend: function() {
                $btn.prop('disabled', true).text('Sending...');
            },
            success: function(response) {
                if (response.success) {
                    let data = response.data || {};
                    console.log('BankU Aadhaar send-otp response:', data);

                    let refId = data.ref_id || data.refId || data.reference_id || data.transaction_id || '';
                    $('#aadhaar_ref_id').val(refId);

                    $('#aadhaarOtpWrap').show();
                    $('#aadhaar_otp').val('').focus();

                    $status.removeClass('text-danger').addClass('text-success')
                        .text('OTP sent to the Aadhaar-registered mobile number.');
                    round_success_noti('OTP sent to the Aadhaar-registered mobile number.');
                } else {
                    $status.removeClass('text-success').addClass('text-danger')
                        .text(response.message || 'Unable to send OTP.');
                    round_error_noti(response.message || 'Unable to send Aadhaar OTP.');
                }
            },
            error: function(xhr) {
                let errorMessage = 'An unexpected error occurred while sending the OTP.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                $status.removeClass('text-success').addClass('text-danger').text(errorMessage);
                round_error_noti(errorMessage);
            },
            complete: function() {
                $btn.prop('disabled', false).text('Send OTP');
            }
        });
    });

    $('#verifyAadhaarOtpBtn').on('click', function() {
        if (aadhaarVerified) return;

        let otp = $('#aadhaar_otp').val().trim();
        let refId = $('#aadhaar_ref_id').val();
        let $btn = $(this);
        let $status = $('#aadhaarVerifyStatus');

        if (!/^\d{6}$/.test(otp)) {
            round_error_noti('Please enter the 6-digit OTP.');
            return;
        }
        if (!refId) {
            round_error_noti('OTP session expired. Please resend the OTP.');
            return;
        }

        $.ajax({
            url: '{{ route($aadhaarVerifyOtpRoute) }}',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                otp: otp,
                ref_id: refId,
            },
            beforeSend: function() {
                $btn.prop('disabled', true).text('Verifying...');
            },
            success: function(response) {
                if (response.success) {
                    let data = response.data || {};
                    console.log('BankU Aadhaar verify-otp response:', data);

                    let fullName = data.name || data.full_name || '';
                    if (fullName && !$('#first_name').val()) {
                        let parts = fullName.trim().split(/\s+/);
                        $('#first_name').val(parts.shift());
                        $('#last_name').val(parts.join(' '));
                    }

                    let address = data.address || data.full_address || '';
                    if (address && !$('#address').val()) {
                        $('#address').val(address);
                    }

                    let dobResp = normalizeDobForInput(data.dob || data.date_of_birth || '');
                    if (dobResp && !$('#date_of_birth').val()) {
                        $('#date_of_birth').val(dobResp);
                    }

                    applyGenderFromApi(data.gender || data.sex);

                    // Keep the full verified payload so it's persisted with the
                    // user record on submit, even for fields with no matching input.
                    $('#aadhaar_verification_data').val(JSON.stringify(data));

                    $status.removeClass('text-danger').addClass('text-success')
                        .text('Aadhaar verified successfully.');
                    round_success_noti('Aadhaar verified successfully.');

                    // Lock the Aadhaar number & OTP so a verified Aadhaar
                    // can't be re-verified or swapped out after the fact.
                    lockAadhaarFields();
                } else {
                    $status.removeClass('text-success').addClass('text-danger')
                        .text(response.message || 'OTP verification failed.');
                    round_error_noti(response.message || 'Aadhaar OTP verification failed.');
                }
            },
            error: function(xhr) {
                let errorMessage = 'An unexpected error occurred while verifying the OTP.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                $status.removeClass('text-success').addClass('text-danger').text(errorMessage);
                round_error_noti(errorMessage);
            },
            complete: function() {
                if (!aadhaarVerified) {
                    $btn.prop('disabled', false).text('Verify OTP');
                }
            }
        });
    });
</script>
