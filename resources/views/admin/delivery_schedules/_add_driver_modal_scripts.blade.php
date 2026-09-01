{{--
    "Add New Driver" quick-modal BankU wiring: driving-licence fetch + field
    population, and Aadhaar / PAN KYC. Shared by delivery_schedules
    create.blade.php and edit.blade.php. Modal-scoped (`#modal_*` ids).
--}}
<script>
    let modalDlVerified = false;

    function normalizeModalDobForInput(value) {
        if (!value) return '';
        value = String(value).trim();
        if (/^\d{4}-\d{2}-\d{2}$/.test(value)) return value;
        let match = value.match(/^(\d{2})[\/\-](\d{2})[\/\-](\d{4})$/);
        if (match) return `${match[3]}-${match[2]}-${match[1]}`;
        return '';
    }

    function lockModalDlFields() {
        modalDlVerified = true;
        $('#modal_driving_license_number').prop('readonly', true);
        $('#modal_date_of_birth').prop('readonly', true);
        $('#modalVerifyDlBtn')
            .prop('disabled', true)
            .removeClass('btn-grd-info')
            .addClass('btn-grd-success')
            .html('<i class="bx bx-check"></i> Verified');
    }

    // Fill the modal's editable DL detail fields from a BankU DL verify payload.
    function populateModalDlFields(data) {
        data = data || {};
        let details = data.details_of_driving_licence || {};

        function pick(keys) {
            for (let i = 0; i < keys.length; i++) {
                let k = keys[i];
                let v = (details[k] !== undefined ? details[k] : data[k]);
                if (v !== undefined && v !== null && String(v).trim() !== '') return String(v).trim();
            }
            return '';
        }

        function setField(id, value) {
            if (value === '' || value === null || value === undefined) return;
            let $f = $('#modal_' + id);
            if ($f.length) $f.val(value);
        }

        setField('dl_status', pick(['status', 'dl_status']));
        setField('holder_name', pick(['name', 'holder_name']));
        setField('father_or_husband_name', pick(['father_or_husband_name', 'father_name', 'fathers_name']));
        setField('dl_dob', pick(['dob', 'date_of_birth']));
        setField('dl_issue_date', pick(['date_of_issue', 'doi', 'issue_date', 'dl_issue_date']));
        setField('dl_address', pick(['address', 'permanent_address', 'present_address']));
        setField('dl_verification_id', pick(['verification_id', 'verificationId', 'client_ref_num']));
        setField('dl_transaction_id', pick(['transaction_id', 'txn_id', 'transactionId', 'reference_id']));

        let stateEntry = details.split_address && details.split_address.state && details.split_address.state[0];
        if (Array.isArray(stateEntry)) {
            setField('issuing_state', stateEntry.filter(Boolean).join(' - '));
        } else if (stateEntry) {
            setField('issuing_state', String(stateEntry).trim());
        }

        let firstBadge = Array.isArray(data.badge_details) ? data.badge_details[0] : null;
        let cov = firstBadge && Array.isArray(firstBadge.class_of_vehicle) ? firstBadge.class_of_vehicle : null;
        if (cov && cov.length) {
            setField('class_of_vehicle', cov.join(', '));
        } else {
            setField('class_of_vehicle', pick(['class_of_vehicle', 'cov', 'vehicle_class']));
        }

        let validity = data.dl_validity || {};
        setField('dl_nt_valid_from', (validity.non_transport || {}).from || '');
        setField('dl_nt_valid_to', (validity.non_transport || {}).to || '');
        setField('dl_tr_valid_from', (validity.transport || {}).from || '');
        setField('dl_tr_valid_to', (validity.transport || {}).to || '');

        let mdw = document.getElementById('modalDlDetailsWrap');
        if (mdw) mdw.open = true;
    }

    // Full reset of the modal's DL + KYC verification state. Called from the
    // "Save Driver" success handler after $('#addDriverForm')[0].reset().
    function resetModalDlVerification() {
        modalDlVerified = false;
        $('#modal_dl_verification_data').val('');
        $('#modalDlVerifyStatus').removeClass('text-success text-danger').text('');
        $('#modal_driving_license_number').prop('readonly', false);
        $('#modal_date_of_birth').prop('readonly', false);
        $('#modalVerifyDlBtn')
            .prop('disabled', false)
            .removeClass('btn-grd-success')
            .addClass('btn-grd-info')
            .text('Fetch DL Details');

        $('#modalDlDetailsWrap .modal-dl-grid').find('input, textarea').val('');
        let mdw = document.getElementById('modalDlDetailsWrap');
        if (mdw) mdw.open = false;

        if (typeof resetModalKyc === 'function') resetModalKyc();
    }

    $('#modal_driving_license_number').on('input', function() {
        if (modalDlVerified) return;
        $('#modal_dl_verification_data').val('');
        $('#modalDlVerifyStatus').removeClass('text-success text-danger').text('');
    });

    $('#modalVerifyDlBtn').text('Fetch DL Details').on('click', function() {
        if (modalDlVerified) return;

        let dlNumber = $('#modal_driving_license_number').val().trim();
        let dob = $('#modal_date_of_birth').val();
        let $btn = $(this);
        let $status = $('#modalDlVerifyStatus');

        $status.removeClass('text-success text-danger').text('');

        if (!dlNumber) {
            round_error_noti('Please enter the Driving Licence number first.');
            return;
        }
        if (!dob) {
            round_error_noti('Please select Date of Birth before fetching the licence.');
            return;
        }

        $.ajax({
            url: '{{ route('driver.verifyLicense') }}',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                driving_license_number: dlNumber,
                dob: dob,
            },
            beforeSend: function() {
                $btn.prop('disabled', true).text('Fetching...');
            },
            success: function(response) {
                if (response.success) {
                    let data = response.data || {};
                    console.log('BankU DL verify response:', data);

                    if (response.verification_id && !data.verification_id) {
                        data.verification_id = response.verification_id;
                    }

                    let details = data.details_of_driving_licence || {};

                    let fullName = details.name || data.name || '';
                    if (fullName) {
                        let parts = fullName.trim().split(/\s+/);
                        $('#modal_first_name').val(parts.shift());
                        $('#modal_last_name').val(parts.join(' '));
                    }

                    let address = details.address || data.address || '';
                    if (address) $('#modal_address').val(address);

                    let dobResp = normalizeModalDobForInput(data.dob || details.dob || '');
                    if (dobResp) $('#modal_date_of_birth').val(dobResp);

                    populateModalDlFields(data);

                    $('#modal_dl_verification_data').val(JSON.stringify(data));

                    $status.removeClass('text-danger').addClass('text-success')
                        .text('Driving licence verified. Review the details below and save.');
                    round_success_noti('Driving licence verified successfully.');

                    lockModalDlFields();
                } else {
                    $status.removeClass('text-success').addClass('text-danger')
                        .text(response.message || 'Verification failed.');
                    round_error_noti(response.message || 'Driving licence verification failed.');
                }
            },
            error: function(xhr) {
                let msg = 'An unexpected error occurred while verifying the licence.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                $status.removeClass('text-success').addClass('text-danger').text(msg);
                round_error_noti(msg);
            },
            complete: function() {
                if (!modalDlVerified) $btn.prop('disabled', false).text('Fetch DL Details');
            }
        });
    });
</script>

<script>
    // Aadhaar + PAN KYC for the "Add New Driver" quick modal.
    let modalPanVerified = false;
    let modalAadhaarVerified = false;

    function resetModalKyc() {
        modalPanVerified = false;
        modalAadhaarVerified = false;
        $('#modal_pan_verification_data, #modal_aadhaar_verification_data, #modal_aadhaar_ref_id').val('');
        $('#modalPanVerifyStatus, #modalAadhaarVerifyStatus').removeClass('text-success text-danger').text('');
        $('#modalAadhaarOtpWrap').hide();
        $('#modal_pan_card_number, #modal_aadhaar_number, #modal_aadhaar_otp').prop('readonly', false);
        $('#modalVerifyPanBtn').prop('disabled', false).removeClass('btn-grd-success').addClass('btn-grd-info')
            .text('Verify');
        $('#modalSendAadhaarOtpBtn').prop('disabled', false).removeClass('btn-grd-success').addClass('btn-grd-info')
            .text('Send OTP');
        $('#modalVerifyAadhaarOtpBtn').prop('disabled', false).removeClass('btn-grd-success').addClass('btn-grd-info')
            .text('Verify OTP');
    }

    function lockModalPan() {
        modalPanVerified = true;
        $('#modal_pan_card_number').prop('readonly', true);
        $('#modalVerifyPanBtn').prop('disabled', true).removeClass('btn-grd-info').addClass('btn-grd-success')
            .html('<i class="bx bx-check"></i> Verified');
    }

    function lockModalAadhaar() {
        modalAadhaarVerified = true;
        $('#modal_aadhaar_number, #modal_aadhaar_otp').prop('readonly', true);
        $('#modalSendAadhaarOtpBtn, #modalVerifyAadhaarOtpBtn')
            .prop('disabled', true).removeClass('btn-grd-info').addClass('btn-grd-success')
            .html('<i class="bx bx-check"></i> Verified');
        $('#modalAadhaarOtpWrap').hide();
    }

    $('#modal_pan_card_number').on('input', function() {
        if (modalPanVerified) return;
        $('#modal_pan_verification_data').val('');
        $('#modalPanVerifyStatus').removeClass('text-success text-danger').text('');
    });

    $('#modalVerifyPanBtn').on('click', function() {
        if (modalPanVerified) return;
        let pan = ($('#modal_pan_card_number').val() || '').trim().toUpperCase();
        let $btn = $(this);
        let $status = $('#modalPanVerifyStatus');
        $status.removeClass('text-success text-danger').text('');

        if (!/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(pan)) {
            round_error_noti('Please enter a valid PAN number (e.g. ABCDE1234F).');
            return;
        }
        $('#modal_pan_card_number').val(pan);

        $.ajax({
            url: '{{ route('driver.verifyPan') }}',
            type: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content'), pan_card_number: pan },
            beforeSend: function() { $btn.prop('disabled', true).text('Verifying...'); },
            success: function(response) {
                if (response.success) {
                    let data = response.data || {};
                    let fullName = data.name || data.registered_name || data.full_name || '';
                    if (fullName && !$('#modal_first_name').val()) {
                        let parts = fullName.trim().split(/\s+/);
                        $('#modal_first_name').val(parts.shift());
                        $('#modal_last_name').val(parts.join(' '));
                    }
                    $('#modal_pan_verification_data').val(JSON.stringify(data));
                    $status.removeClass('text-danger').addClass('text-success').text('PAN verified successfully.');
                    round_success_noti('PAN verified successfully.');
                    lockModalPan();
                } else {
                    $status.removeClass('text-success').addClass('text-danger')
                        .text(response.message || 'PAN verification failed.');
                    round_error_noti(response.message || 'PAN verification failed.');
                }
            },
            error: function(xhr) {
                let msg = 'An unexpected error occurred while verifying the PAN.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                $status.removeClass('text-success').addClass('text-danger').text(msg);
                round_error_noti(msg);
            },
            complete: function() { if (!modalPanVerified) $btn.prop('disabled', false).text('Verify'); }
        });
    });

    $('#modal_aadhaar_number').on('input', function() {
        if (modalAadhaarVerified) return;
        $('#modal_aadhaar_verification_data, #modal_aadhaar_ref_id').val('');
        $('#modalAadhaarOtpWrap').hide();
        $('#modalAadhaarVerifyStatus').removeClass('text-success text-danger').text('');
    });

    $('#modalSendAadhaarOtpBtn').on('click', function() {
        if (modalAadhaarVerified) return;
        let aadhaar = ($('#modal_aadhaar_number').val() || '').trim();
        let $btn = $(this);
        let $status = $('#modalAadhaarVerifyStatus');
        $status.removeClass('text-success text-danger').text('');

        if (!/^\d{12}$/.test(aadhaar)) {
            round_error_noti('Please enter a valid 12-digit Aadhaar number.');
            return;
        }

        $.ajax({
            url: '{{ route('driver.aadhaarSendOtp') }}',
            type: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content'), aadhaar_number: aadhaar },
            beforeSend: function() { $btn.prop('disabled', true).text('Sending...'); },
            success: function(response) {
                if (response.success) {
                    let data = response.data || {};
                    let refId = data.ref_id || data.refId || data.reference_id || data.transaction_id || '';
                    $('#modal_aadhaar_ref_id').val(refId);
                    $('#modalAadhaarOtpWrap').show();
                    $('#modal_aadhaar_otp').val('').focus();
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
                let msg = 'An unexpected error occurred while sending the OTP.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                $status.removeClass('text-success').addClass('text-danger').text(msg);
                round_error_noti(msg);
            },
            complete: function() { $btn.prop('disabled', false).text('Send OTP'); }
        });
    });

    $('#modalVerifyAadhaarOtpBtn').on('click', function() {
        if (modalAadhaarVerified) return;
        let otp = ($('#modal_aadhaar_otp').val() || '').trim();
        let refId = $('#modal_aadhaar_ref_id').val();
        let $btn = $(this);
        let $status = $('#modalAadhaarVerifyStatus');

        if (!/^\d{6}$/.test(otp)) {
            round_error_noti('Please enter the 6-digit OTP.');
            return;
        }
        if (!refId) {
            round_error_noti('OTP session expired. Please resend the OTP.');
            return;
        }

        $.ajax({
            url: '{{ route('driver.aadhaarVerifyOtp') }}',
            type: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content'), otp: otp, ref_id: refId },
            beforeSend: function() { $btn.prop('disabled', true).text('Verifying...'); },
            success: function(response) {
                if (response.success) {
                    let data = response.data || {};
                    let fullName = data.name || data.full_name || '';
                    if (fullName && !$('#modal_first_name').val()) {
                        let parts = fullName.trim().split(/\s+/);
                        $('#modal_first_name').val(parts.shift());
                        $('#modal_last_name').val(parts.join(' '));
                    }
                    let address = data.address || data.full_address || '';
                    if (address && !$('#modal_address').val()) $('#modal_address').val(address);

                    let dob = normalizeModalDobForInput(data.dob || data.date_of_birth || '');
                    if (dob && !$('#modal_date_of_birth').val()) $('#modal_date_of_birth').val(dob);

                    $('#modal_aadhaar_verification_data').val(JSON.stringify(data));
                    $status.removeClass('text-danger').addClass('text-success').text('Aadhaar verified successfully.');
                    round_success_noti('Aadhaar verified successfully.');
                    lockModalAadhaar();
                } else {
                    $status.removeClass('text-success').addClass('text-danger')
                        .text(response.message || 'OTP verification failed.');
                    round_error_noti(response.message || 'Aadhaar OTP verification failed.');
                }
            },
            error: function(xhr) {
                let msg = 'An unexpected error occurred while verifying the OTP.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                $status.removeClass('text-success').addClass('text-danger').text(msg);
                round_error_noti(msg);
            },
            complete: function() { if (!modalAadhaarVerified) $btn.prop('disabled', false).text('Verify OTP'); }
        });
    });
</script>
