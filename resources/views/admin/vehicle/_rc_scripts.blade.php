@include('admin.vehicle._rc_populate')

<script>
    // "Fetch RC Details" button wiring for the full vehicle create/edit form.
    // The field-population logic lives in populateRcFields() (_rc_populate).
    $(function () {
        let $btn = $('#verifyRcBtn');
        let $status = $('#rcVerifyStatus');

        // Re-hydrate the fields after a validation redirect brought the
        // stored payload back in the hidden input.
        if ($('#rc_verification_data').val()) {
            try {
                populateRcFields(JSON.parse($('#rc_verification_data').val()));
            } catch (e) {
                // Ignore malformed stored payload.
            }
        }

        $btn.on('click', function () {
            let regNo = ($('#vehicle_number').val() || '').trim().toUpperCase();

            if (!regNo) {
                if (typeof round_error_noti === 'function') {
                    round_error_noti('Please enter the vehicle registration number first.');
                }
                $status.removeClass('text-success').addClass('text-danger')
                    .text('Please enter the vehicle registration number first.');
                return;
            }

            $('#vehicle_number').val(regNo);

            $.ajax({
                url: '{{ route('vehicle.verifyRc') }}',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    vehicle_registration_number: regNo,
                },
                beforeSend: function () {
                    $btn.prop('disabled', true).text('Fetching...');
                    $status.removeClass('text-success text-danger').text('');
                },
                success: function (response) {
                    if (response.success) {
                        let data = response.data || {};
                        console.log('BankU RC verify response:', data);
                        populateRcFields(data);
                        $status.removeClass('text-danger').addClass('text-success')
                            .text('RC details fetched. Review the fields below and save.');
                        if (typeof round_success_noti === 'function') {
                            round_success_noti('RC details fetched successfully.');
                        }
                    } else {
                        let msg = response.message || 'RC verification failed.';
                        $status.removeClass('text-success').addClass('text-danger').text(msg);
                        if (typeof round_error_noti === 'function') {
                            round_error_noti(msg);
                        }
                    }
                },
                error: function (xhr) {
                    let msg = 'An unexpected error occurred while fetching the RC details.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    $status.removeClass('text-success').addClass('text-danger').text(msg);
                    if (typeof round_error_noti === 'function') {
                        round_error_noti(msg);
                    }
                },
                complete: function () {
                    $btn.prop('disabled', false).text('Fetch RC Details');
                }
            });
        });
    });
</script>
