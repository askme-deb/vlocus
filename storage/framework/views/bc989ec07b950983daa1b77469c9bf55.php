<script>
    // Candidate response keys for each form field. BankU's RC payload key
    // names vary a little between records/providers, so each form field is
    // matched against an ordered list and the first non-empty value wins.
    //
    // Shared by the full vehicle create/edit form (_rc_scripts.blade.php) and
    // the "Add New Vehicle" quick-add modal on the delivery-schedule screens.
    window.RC_FIELD_MAP = window.RC_FIELD_MAP || {
        rc_status:              ['rc_status', 'status'],
        vehicle_class:          ['class', 'vehicle_class', 'vehicle_category_description'],
        chassis_number:         ['chassis', 'chassis_number', 'chassis_no'],
        engine_number:          ['engine', 'engine_number', 'engine_no'],
        manufacturer:           ['vehicle_manufacturer_name', 'maker_description', 'manufacturer', 'maker'],
        model_name:             ['model', 'maker_model', 'vehicle_model'],
        colour:                 ['vehicle_colour', 'colour', 'color'],
        fuel_type:              ['type', 'fuel_type', 'fuel_descr', 'fuel'],
        emission_norm:          ['norms_type', 'emission_norm', 'norms_desc', 'norms'],
        owner_name:             ['owner', 'owner_name'],
        registration_date:      ['registration_date', 'reg_date', 'rc_regn_dt'],
        rc_expiry_date:         ['rc_expiry_date', 'fit_up_to', 'rc_expiry', 'expiry_date'],
        tax_upto:               ['tax_upto', 'vehicle_tax_upto', 'tax_up_to'],
        insurance_company:      ['vehicle_insurance_company_name', 'insurance_company', 'vehicle_insurance', 'insurance_comp'],
        insurance_upto:         ['vehicle_insurance_upto', 'insurance_upto', 'insurance_validity'],
        financer:               ['financer', 'financier', 'rc_financer'],
        owner_address:          ['present_address', 'permanent_address', 'address', 'owner_address', 'c_address'],
        cubic_capacity:         ['cubic_capacity', 'vehicle_cubic_capacity', 'cc'],
        gross_weight:           ['gross_vehicle_weight', 'vehicle_gross_weight', 'gross_weight', 'gvw'],
        seat_capacity:          ['vehicle_seat_capacity', 'seating_capacity', 'seat_capacity'],
        sleeper_capacity:       ['vehicle_sleeper_capacity', 'sleeper_capacity', 'sleeper_cap'],
        pucc_number:            ['pucc_number', 'pucc_no'],
        pucc_upto:              ['pucc_upto', 'pucc_valid_upto', 'pucc_validity'],
        permit_type:            ['permit_type'],
        permit_valid_upto:      ['permit_valid_upto', 'permit_validity', 'permit_upto'],
        national_permit_number: ['national_permit_number', 'national_permit_no'],
        national_permit_upto:   ['national_permit_upto', 'national_permit_validity', 'national_permit_upto_date'],
    };

    window.rcPick = window.rcPick || function (data, keys) {
        for (let i = 0; i < keys.length; i++) {
            let v = data[keys[i]];
            if (v !== undefined && v !== null && String(v).trim() !== '') {
                return String(v).trim();
            }
        }
        return '';
    };

    // Fill an editable RC form (or the RC subset present in a modal) with
    // values from a BankU RC verify response. Existing user-entered values
    // are only overwritten when a fetched value is present, so a re-fetch
    // never blanks out manual corrections.
    //
    // opts.payloadTarget - hidden input selector to stash the raw JSON in
    //                      (default '#rc_verification_data'; the modal uses
    //                      '#modal_rc_verification_data').
    window.populateRcFields = window.populateRcFields || function (data, opts) {
        data = data || {};
        opts = opts || {};
        let payloadTarget = opts.payloadTarget || '#rc_verification_data';

        Object.keys(window.RC_FIELD_MAP).forEach(function (fieldId) {
            let $field = $('#' + fieldId);
            if (!$field.length) return;
            let value = window.rcPick(data, window.RC_FIELD_MAP[fieldId]);
            if (value !== '') {
                $field.val(value);
            }
        });

        let regNo = window.rcPick(data, ['reg_no', 'vehicle_number', 'registration_number']);
        if (regNo !== '' && $('#vehicle_number').length && !$('#vehicle_number').val().trim()) {
            $('#vehicle_number').val(regNo.toUpperCase());
        }

        if ($('#is_commercial').length) {
            let commercial = window.rcPick(data, ['is_commercial']);
            if (commercial !== '') {
                $('#is_commercial').val(/^(1|true|yes)$/i.test(commercial) ? '1' : '0');
            } else {
                let cls = ($('#vehicle_class').val() || '').toUpperCase();
                if (/LGV|LMV-CARGO|GOODS|TRANSPORT|COMMERCIAL|MAXI|TRAILER/.test(cls)) {
                    $('#is_commercial').val('1');
                }
            }
        }

        if ($(payloadTarget).length) {
            $(payloadTarget).val(JSON.stringify(data));
        }
    };
</script>
<?php /**PATH D:\projects\vlocus\resources\views/admin/vehicle/_rc_populate.blade.php ENDPATH**/ ?>