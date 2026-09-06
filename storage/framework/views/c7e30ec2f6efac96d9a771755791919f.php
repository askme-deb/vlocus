<?php if (! $__env->hasRenderedOnce('d57e873c-e2e7-43b0-a406-6e8c1b8cfe11')): $__env->markAsRenderedOnce('d57e873c-e2e7-43b0-a406-6e8c1b8cfe11'); ?>
<script>
    // Shared helpers for turning a BankU identity payload (driving licence,
    // Aadhaar or PAN) into the driver form fields. Loaded once and reused by
    // _dl_scripts and _kyc_scripts on both the create and edit screens.

    window.normalizeDobForInput = window.normalizeDobForInput || function (value) {
        if (!value) return '';
        value = String(value).trim();

        // Already yyyy-mm-dd, as <input type="date"> expects.
        if (/^\d{4}-\d{2}-\d{2}$/.test(value)) {
            return value;
        }

        // Common dd/mm/yyyy or dd-mm-yyyy.
        let match = value.match(/^(\d{2})[\/\-](\d{2})[\/\-](\d{4})$/);
        if (match) {
            return `${match[3]}-${match[2]}-${match[1]}`;
        }

        return '';
    };

    // Gender isn't returned by every source (BankU's DL API omits it), so
    // whichever of DL / Aadhaar / PAN responds with a usable value first fills
    // the select.
    window.applyGenderFromApi = window.applyGenderFromApi || function (rawValue) {
        if (!rawValue) return;

        let genderMap = {
            m: 'male', male: 'male',
            f: 'female', female: 'female',
            o: 'others', other: 'others', others: 'others', transgender: 'others',
        };

        let mapped = genderMap[String(rawValue).trim().toLowerCase()];
        if (mapped) {
            $('select[name="gender"]').val(mapped);
        }
    };

    window.dlSetName = window.dlSetName || function (fullName, force) {
        fullName = (fullName || '').trim();
        if (!fullName) return;
        if (!force && ($('#first_name').val() || '').trim()) return;

        let parts = fullName.split(/\s+/);
        $('#first_name').val(parts.shift());
        $('#last_name').val(parts.join(' '));
    };

    // Fill the editable DL form (or the DL subset present in a modal) from a
    // BankU driving-license verify response. Fetched values overwrite existing
    // field values so a re-fetch on the edit screen refreshes stale data;
    // fields with no fetched value are left untouched.
    //
    // opts.payloadTarget - hidden input to stash the raw JSON in
    //                      (default '#driving_license_verification_data').
    window.populateDlFields = window.populateDlFields || function (data, opts) {
        data = data || {};
        opts = opts || {};
        let payloadTarget = opts.payloadTarget || '#driving_license_verification_data';

        let details = data.details_of_driving_licence || {};

        function pick(keys) {
            for (let i = 0; i < keys.length; i++) {
                let k = keys[i];
                let v = (details[k] !== undefined ? details[k] : data[k]);
                if (v !== undefined && v !== null && String(v).trim() !== '') {
                    return String(v).trim();
                }
            }
            return '';
        }

        function setField(id, value) {
            if (value === '' || value === null || value === undefined) return;
            let $f = $('#' + id);
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

        // details.split_address.state is an array of [fullName, abbr] pairs.
        let stateEntry = details.split_address && details.split_address.state && details.split_address.state[0];
        if (Array.isArray(stateEntry)) {
            setField('issuing_state', stateEntry.filter(Boolean).join(' - '));
        } else if (stateEntry) {
            setField('issuing_state', String(stateEntry).trim());
        }

        // badge_details[0].class_of_vehicle is an array of COV codes.
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

        // Personal fields shared with the Basic Information card.
        window.dlSetName(pick(['name', 'holder_name']), true);

        let address = pick(['address', 'permanent_address', 'present_address']);
        if (address && $('#address').length) {
            $('#address').val(address);
        }

        let dob = window.normalizeDobForInput(pick(['dob', 'date_of_birth']));
        if (dob && $('#date_of_birth').length) {
            $('#date_of_birth').val(dob);
        }

        window.applyGenderFromApi(pick(['gender', 'sex']));

        // Profile picture is sourced from the DL photo instead of a manual
        // upload -- only touched when the page actually has that hidden
        // field (i.e. opts in to this behaviour).
        let $photoHidden = $('#profile_photo_source');
        if ($photoHidden.length) {
            let photo = pick([
                'photo', 'photograph', 'user_photo', 'profile_photo',
                'holder_photo', 'dl_photo', 'image', 'photo_base64', 'image_base64',
            ]);
            if (photo) {
                // BankU returns this as a hosted image URL, not base64 --
                // but fall back to treating it as base64 if that ever changes.
                let photoSrc = /^(data:|https?:\/\/)/i.test(photo) ? photo : ('data:image/jpeg;base64,' + photo);
                $photoHidden.val(photoSrc);
                $('#blah').attr('src', photoSrc).css('display', 'block');
            }
        }

        if ($(payloadTarget).length) {
            $(payloadTarget).val(JSON.stringify(data));
        }
    };
</script>
<?php endif; ?>
<?php /**PATH D:\projects\vlocus\resources\views/admin/driver/_dl_populate.blade.php ENDPATH**/ ?>