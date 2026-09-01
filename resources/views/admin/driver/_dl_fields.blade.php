@php
    /**
     * Shared editable DL-detail inputs for the driver create/edit forms.
     * $data is the Driver model on edit, null on create.
     */
    $data = $data ?? null;

    $dlFieldTypes = [
        'dl_status'              => 'text',
        'dl_dob'                => 'date',
        'holder_name'           => 'text',
        'father_or_husband_name'=> 'text',
        'dl_address'            => 'textarea',
        'dl_issue_date'         => 'date',
        'class_of_vehicle'      => 'text',
        'dl_verification_id'    => 'text',
        'dl_transaction_id'     => 'text',
        'issuing_state'         => 'text',
        'dl_nt_valid_from'      => 'date',
        'dl_nt_valid_to'        => 'date',
        'dl_tr_valid_from'      => 'date',
        'dl_tr_valid_to'        => 'date',
    ];
@endphp

@once
    <style>
        /* Compact multi-column layout for the DL block. Scoped to
           .dl-fields-grid so host pages don't flatten it. */
        .dl-fields-grid .row.g-3 {
            display: grid !important;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 2px 24px;
        }
        .dl-fields-grid .row.g-3 > [class*="col-"] {
            flex: none;
            width: auto;
            max-width: none;
        }
        .dl-fields-grid .row.g-3 > .col-md-12 {
            grid-column: 1 / -1;
        }
        .dl-fields-grid .mb-3 {
            display: block !important;
            width: auto !important;
            gap: 0 !important;
            align-items: stretch !important;
            margin-bottom: 12px !important;
        }
        .dl-fields-grid .mb-3 > label {
            flex: none !important;
            display: block;
            width: auto;
            margin-bottom: 4px;
            font-weight: 600;
        }
        .dl-fields-grid .mb-3 .form-control,
        .dl-fields-grid .mb-3 select,
        .dl-fields-grid .mb-3 textarea {
            flex: none !important;
            width: 100% !important;
        }
        @media (max-width: 575.98px) {
            .dl-fields-grid .row.g-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endonce

<div class="dl-fields-grid">
    <div class="row g-3">
        @foreach (\App\Models\Driver::DL_FIELDS as $key => $label)
            @php
                $type = $dlFieldTypes[$key] ?? 'text';
                $current = old($key, $data ? ($data->{$key} ?? null) : null);
            @endphp

            @if ($type === 'textarea')
                <div class="mb-3 col-md-12">
                    <label for="{{ $key }}" class="form-label">{{ $label }}</label>
                    <textarea name="{{ $key }}" id="{{ $key }}" class="form-control js-dl-field" rows="2"
                        placeholder="Enter {{ $label }}">{{ $current }}</textarea>
                </div>
            @else
                <div class="mb-3 col-md-4">
                    <label for="{{ $key }}" class="form-label">{{ $label }}</label>
                    <input type="text" class="form-control js-dl-field" name="{{ $key }}" id="{{ $key }}"
                        value="{{ $current }}" placeholder="{{ $type === 'date' ? 'DD-MM-YYYY' : 'Enter ' . $label }}">
                </div>
            @endif
        @endforeach
    </div>
</div>
