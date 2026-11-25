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

    // Validity dates: BankU can return more than one value for the same
    // document (e.g. the lapsed policy and the renewed one, or a nested
    // insurance history). For these fields every candidate is collected --
    // the listed keys plus any key, at any depth, matching the pattern --
    // and the LATEST valid date wins, so a renewal is never hidden behind
    // an older value. Must stay in sync with VehicleController::RC_DATE_PATTERNS.
    window.RC_DATE_PATTERNS = window.RC_DATE_PATTERNS || {
        insurance_upto:       /insurance.*(upto|up_to|valid|expir|end)/i,
        pucc_upto:            /pucc.*(upto|up_to|valid|expir)/i,
        rc_expiry_date:       /(^|_)(fit_up_?to|rc_expiry(_date)?|rc_valid(ity|_upto)?)$/i,
        tax_upto:             /tax.*(upto|up_to|valid)/i,
        permit_valid_upto:    /^(?!.*national).*permit.*(upto|up_to|valid)/i,
        national_permit_upto: /national_permit.*(upto|up_to|valid)/i,
    };

    // Parse the RC date formats BankU uses into a Date (null when invalid).
    window.rcParseDate = window.rcParseDate || function (value) {
        let s = String(value || '').trim(), m, y, mo, d;
        const months = {jan:1,feb:2,mar:3,apr:4,may:5,jun:6,jul:7,aug:8,sep:9,oct:10,nov:11,dec:12};

        if ((m = s.match(/^(\d{4})[-\/.](\d{1,2})[-\/.](\d{1,2})(?:$|[T\s])/))) {
            y = +m[1]; mo = +m[2]; d = +m[3];
        } else if ((m = s.match(/^(\d{1,2})[-\/.](\d{1,2})[-\/.](\d{4})$/))) {
            d = +m[1]; mo = +m[2]; y = +m[3];
        } else if ((m = s.match(/^(\d{1,2})[-\s]([A-Za-z]{3})[A-Za-z]*[-\s,]+(\d{4})$/))) {
            d = +m[1]; mo = months[m[2].toLowerCase()]; y = +m[3];
        } else {
            return null;
        }

        if (!mo || y < 1950 || y > 2200) return null;
        let date = new Date(y, mo - 1, d);
        return date.getMonth() === mo - 1 && date.getDate() === d ? date : null;
    };

    // Flatten a nested payload into {"a_b_0_c": value} so key patterns can
    // match dates that sit inside nested objects / arrays.
    window.rcFlatten = window.rcFlatten || function (obj, prefix, out) {
        out = out || {};
        Object.keys(obj || {}).forEach(function (k) {
            let key = prefix ? prefix + '_' + k : k, v = obj[k];
            if (v && typeof v === 'object') {
                window.rcFlatten(v, key, out);
            } else {
                out[key] = v;
            }
        });
        return out;
    };

    // Latest valid date among the listed keys and pattern-matching keys,
    // returned as YYYY-MM-DD ('' when none found).
    window.rcPickLatestDate = window.rcPickLatestDate || function (data, keys, pattern) {
        let flat = window.rcFlatten(data), best = null;
        let candidates = keys.map(function (k) { return flat[k]; });

        if (pattern) {
            Object.keys(flat).forEach(function (k) {
                if (pattern.test(k)) candidates.push(flat[k]);
            });
        }

        candidates.forEach(function (v) {
            let date = window.rcParseDate(v);
            if (date && (!best || date > best)) best = date;
        });

        if (!best) return '';
        let pad = function (n) { return String(n).padStart(2, '0'); };
        return best.getFullYear() + '-' + pad(best.getMonth() + 1) + '-' + pad(best.getDate());
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
            let keys = window.RC_FIELD_MAP[fieldId];
            let value = window.RC_DATE_PATTERNS[fieldId]
                ? window.rcPickLatestDate(data, keys, window.RC_DATE_PATTERNS[fieldId])
                : window.rcPick(data, keys);
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
