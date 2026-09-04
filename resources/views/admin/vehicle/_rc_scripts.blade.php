@include('admin.vehicle._rc_populate')

@once
    <style>
        #rcFetchOverlay {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, .85);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 1rem;
        }
        #rcFetchOverlay .spinner-border {
            width: 3rem;
            height: 3rem;
        }
        #rcFetchOverlay span {
            font-weight: 600;
            color: #475569;
        }
    </style>
    <div id="rcFetchOverlay">
        <div class="spinner-border text-primary" role="status"></div>
        <span id="rcFetchOverlayText">Fetching RC details&hellip;</span>
    </div>
@endonce

<script>
    // "Fetch RC Details" button wiring for the full vehicle create/edit form.
    // The field-population logic lives in populateRcFields() (_rc_populate).
    //
    // On success the whole page is blocked behind #rcFetchOverlay and the
    // enclosing form is submitted automatically -- the user only has to enter
    // the RC number and the record is saved with the fetched details, no
    // separate review-then-click-Submit step.
    function showRcFetchOverlay(text) {
        $('#rcFetchOverlayText').text(text);
        $('#rcFetchOverlay').css('display', 'flex');
    }

    function hideRcFetchOverlay() {
        $('#rcFetchOverlay').hide();
    }

    $(function () {
        let $btn = $('#verifyRcBtn');
        let $status = $('#rcVerifyStatus');
        let $form = $btn.closest('form');

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
                    showRcFetchOverlay('Fetching RC details…');
                },
                success: function (response) {
                    if (response.success) {
                        let data = response.data || {};
                        console.log('BankU RC verify response:', data);
                        populateRcFields(data);
                        $status.removeClass('text-danger').addClass('text-success')
                            .text('RC details fetched. Submitting…');

                        if ($form.length && $form.attr('action')) {
                            showRcFetchOverlay('RC details fetched. Submitting…');

                            if ($form[0].checkValidity() === false) {
                                $form.addClass('was-validated');
                                hideRcFetchOverlay();
                                if (typeof round_error_noti === 'function') {
                                    round_error_noti('Please complete the required fields before submitting.');
                                }
                                return;
                            }

                            // Kept behind the overlay through navigation --
                            // no need to re-enable the button here.
                            $form.trigger('submit');
                            return;
                        }

                        if (typeof round_success_noti === 'function') {
                            round_success_noti('RC details fetched successfully.');
                        }
                        hideRcFetchOverlay();
                    } else {
                        let msg = response.message || 'RC verification failed.';
                        $status.removeClass('text-success').addClass('text-danger').text(msg);
                        if (typeof round_error_noti === 'function') {
                            round_error_noti(msg);
                        }
                        hideRcFetchOverlay();
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
                    hideRcFetchOverlay();
                },
                complete: function () {
                    $btn.prop('disabled', false).text('Fetch RC Details');
                }
            });
        });
    });
</script>
