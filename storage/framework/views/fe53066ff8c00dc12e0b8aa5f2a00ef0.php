<?php echo $__env->make('admin.driver._dl_populate', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>
    // "Fetch DL Details" button wiring for the driver create/edit forms.
    // Field population lives in populateDlFields() (_dl_populate).
    $(function () {
        let $btn = $('#verifyDlBtn');
        let $status = $('#dlVerifyStatus');
        let $number = $('#driving_license_number');
        let $dob = $('#date_of_birth');
        let $payload = $('#driving_license_verification_data');
        let dlLocked = false;

        function lockDlFields() {
            dlLocked = true;
            $number.prop('readonly', true);
            $dob.prop('readonly', true);
            $btn.prop('disabled', true)
                .removeClass('btn-grd-info')
                .addClass('btn-grd-success')
                .html('<i class="bx bx-check"></i> Verified');
        }

        // Re-hydrate the DL fields when a stored payload is present (edit
        // screen, or a validation redirect). The number/DOB stay editable so
        // the record can be re-fetched; changing the number clears the payload.
        if ($payload.val()) {
            try {
                populateDlFields(JSON.parse($payload.val()));
                $status.removeClass('text-danger').addClass('text-success')
                    .text('Driving licence details loaded. Re-fetch to refresh.');
                $btn.text('Re-fetch DL Details');
            } catch (e) {
                // Ignore malformed stored payload.
            }
        }

        // Invalidate a verified payload if the licence number is edited
        // afterwards, so mismatched data never gets saved.
        $number.on('input', function () {
            if (dlLocked) return;
            $payload.val('');
            $status.removeClass('text-success text-danger').text('');
        });

        $btn.on('click', function () {
            if (dlLocked) return;

            let dlNumber = ($number.val() || '').trim();
            let dob = $dob.val();

            $status.removeClass('text-success text-danger').text('');

            if (!dlNumber) {
                if (typeof round_error_noti === 'function') {
                    round_error_noti('Please enter the Driving Licence number first.');
                }
                return;
            }
            if (!dob) {
                if (typeof round_error_noti === 'function') {
                    round_error_noti('Please enter Date of Birth before fetching the licence.');
                }
                return;
            }

            $.ajax({
                url: '<?php echo e(route('driver.verifyLicense')); ?>',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    driving_license_number: dlNumber,
                    dob: dob,
                },
                beforeSend: function () {
                    $btn.prop('disabled', true).text('Fetching...');
                    $(document).trigger('banku:dl-fetch-start');
                },
                success: function (response) {
                    if (response.success) {
                        let data = response.data || {};
                        console.log('BankU DL verify response:', data);

                        if (response.verification_id && !data.verification_id) {
                            data.verification_id = response.verification_id;
                        }

                        populateDlFields(data);

                        $status.removeClass('text-danger').addClass('text-success')
                            .text('Driving licence verified. Review the fields below and save.');
                        if (typeof round_success_noti === 'function') {
                            round_success_noti('Driving licence verified successfully.');
                        }

                        lockDlFields();

                        // Lets a page opt into its own post-fetch behaviour
                        // (e.g. auto-submitting the create form) without this
                        // shared script assuming what that behaviour is.
                        $(document).trigger('banku:dl-fetched', [data]);
                    } else {
                        let msg = response.message || 'Driving licence verification failed.';
                        $status.removeClass('text-success').addClass('text-danger').text(msg);
                        if (typeof round_error_noti === 'function') {
                            round_error_noti(msg);
                        }
                        $(document).trigger('banku:dl-fetch-failed');
                    }
                },
                error: function (xhr) {
                    let msg = 'An unexpected error occurred while verifying the licence.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    $status.removeClass('text-success').addClass('text-danger').text(msg);
                    if (typeof round_error_noti === 'function') {
                        round_error_noti(msg);
                    }
                    $(document).trigger('banku:dl-fetch-failed');
                },
                complete: function () {
                    if (!dlLocked) {
                        $btn.prop('disabled', false).text('Fetch DL Details');
                    }
                }
            });
        });
    });
</script>
<?php /**PATH D:\projects\vlocus\resources\views/admin/driver/_dl_scripts.blade.php ENDPATH**/ ?>