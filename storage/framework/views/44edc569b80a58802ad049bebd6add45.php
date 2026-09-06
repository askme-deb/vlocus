<?php echo $__env->make('admin.driver._dl_populate', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>
    // PAN verification wiring (BankU) for the driver create/edit forms.
    $(function () {
        let panVerified = false;

        function lockPanField() {
            panVerified = true;
            $('#pan_card_number').prop('readonly', true);
            $('#verifyPanBtn').prop('disabled', true)
                .removeClass('btn-grd-info').addClass('btn-grd-success')
                .html('<i class="bx bx-check"></i> Verified');
        }

        $('#pan_card_number').on('input', function () {
            if (panVerified) return;
            $('#pan_verification_data').val('');
            $('#panVerifyStatus').removeClass('text-success text-danger').text('');
        });

        if ($('#pan_verification_data').val()) {
            lockPanField();
            $('#panVerifyStatus').removeClass('text-danger').addClass('text-success')
                .text('PAN verified successfully.');
        }

        $('#verifyPanBtn').on('click', function () {
            if (panVerified) return;

            let panNumber = ($('#pan_card_number').val() || '').trim().toUpperCase();
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
                url: '<?php echo e(route('driver.verifyPan')); ?>',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    pan_card_number: panNumber,
                },
                beforeSend: function () {
                    $btn.prop('disabled', true).text('Verifying...');
                },
                success: function (response) {
                    if (response.success) {
                        let data = response.data || {};
                        console.log('BankU PAN verify response:', data);

                        window.dlSetName(data.name || data.registered_name || data.full_name || '', false);
                        window.applyGenderFromApi(data.gender || data.sex);

                        $('#pan_verification_data').val(JSON.stringify(data));

                        $status.removeClass('text-danger').addClass('text-success')
                            .text('PAN verified successfully.');
                        round_success_noti('PAN verified successfully.');
                        lockPanField();
                    } else {
                        $status.removeClass('text-success').addClass('text-danger')
                            .text(response.message || 'PAN verification failed.');
                        round_error_noti(response.message || 'PAN verification failed.');
                    }
                },
                error: function (xhr) {
                    let msg = 'An unexpected error occurred while verifying the PAN.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    $status.removeClass('text-success').addClass('text-danger').text(msg);
                    round_error_noti(msg);
                },
                complete: function () {
                    if (!panVerified) {
                        $btn.prop('disabled', false).text('Verify');
                    }
                }
            });
        });
    });
</script>

<script>
    // Aadhaar OTP verification wiring (BankU) for the driver create/edit forms.
    $(function () {
        let aadhaarVerified = false;

        function lockAadhaarFields() {
            aadhaarVerified = true;
            $('#aadhaar_number').prop('readonly', true);
            $('#aadhaar_otp').prop('readonly', true);
            $('#sendAadhaarOtpBtn').prop('disabled', true)
                .removeClass('btn-grd-info').addClass('btn-grd-success')
                .html('<i class="bx bx-check"></i> Verified');
            $('#verifyAadhaarOtpBtn').prop('disabled', true)
                .removeClass('btn-grd-info').addClass('btn-grd-success')
                .html('<i class="bx bx-check"></i> Verified');
            $('#aadhaarOtpWrap').hide();
        }

        $('#aadhaar_number').on('input', function () {
            if (aadhaarVerified) return;
            $('#aadhaar_verification_data').val('');
            $('#aadhaar_ref_id').val('');
            $('#aadhaarOtpWrap').hide();
            $('#aadhaarVerifyStatus').removeClass('text-success text-danger').text('');
        });

        if ($('#aadhaar_verification_data').val()) {
            lockAadhaarFields();
            $('#aadhaarVerifyStatus').removeClass('text-danger').addClass('text-success')
                .text('Aadhaar verified successfully.');
        }

        $('#sendAadhaarOtpBtn').on('click', function () {
            if (aadhaarVerified) return;

            let aadhaarNumber = ($('#aadhaar_number').val() || '').trim();
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
                url: '<?php echo e(route('driver.aadhaarSendOtp')); ?>',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    aadhaar_number: aadhaarNumber,
                },
                beforeSend: function () {
                    $btn.prop('disabled', true).text('Sending...');
                },
                success: function (response) {
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
                error: function (xhr) {
                    let msg = 'An unexpected error occurred while sending the OTP.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    $status.removeClass('text-success').addClass('text-danger').text(msg);
                    round_error_noti(msg);
                },
                complete: function () {
                    $btn.prop('disabled', false).text('Send OTP');
                }
            });
        });

        $('#verifyAadhaarOtpBtn').on('click', function () {
            if (aadhaarVerified) return;

            let otp = ($('#aadhaar_otp').val() || '').trim();
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
                url: '<?php echo e(route('driver.aadhaarVerifyOtp')); ?>',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    otp: otp,
                    ref_id: refId,
                },
                beforeSend: function () {
                    $btn.prop('disabled', true).text('Verifying...');
                },
                success: function (response) {
                    if (response.success) {
                        let data = response.data || {};
                        console.log('BankU Aadhaar verify-otp response:', data);

                        window.dlSetName(data.name || data.full_name || '', false);

                        let address = data.address || data.full_address || '';
                        if (address && $('#address').length && !$('#address').val()) {
                            $('#address').val(address);
                        }

                        let dob = window.normalizeDobForInput(data.dob || data.date_of_birth || '');
                        if (dob && $('#date_of_birth').length && !$('#date_of_birth').val()) {
                            $('#date_of_birth').val(dob);
                        }

                        window.applyGenderFromApi(data.gender || data.sex);

                        $('#aadhaar_verification_data').val(JSON.stringify(data));

                        $status.removeClass('text-danger').addClass('text-success')
                            .text('Aadhaar verified successfully.');
                        round_success_noti('Aadhaar verified successfully.');
                        lockAadhaarFields();
                    } else {
                        $status.removeClass('text-success').addClass('text-danger')
                            .text(response.message || 'OTP verification failed.');
                        round_error_noti(response.message || 'Aadhaar OTP verification failed.');
                    }
                },
                error: function (xhr) {
                    let msg = 'An unexpected error occurred while verifying the OTP.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    $status.removeClass('text-success').addClass('text-danger').text(msg);
                    round_error_noti(msg);
                },
                complete: function () {
                    if (!aadhaarVerified) {
                        $btn.prop('disabled', false).text('Verify OTP');
                    }
                }
            });
        });
    });
</script>
<?php /**PATH D:\projects\vlocus\resources\views/admin/driver/_kyc_scripts.blade.php ENDPATH**/ ?>