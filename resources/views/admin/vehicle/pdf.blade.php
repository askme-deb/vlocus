<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Vehicle Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #1f2937;
        }

        .card {
            width: 480px;
            margin: 20px auto;
            border: 1px solid #d1d5db;
            border-radius: 14px;
            padding: 22px 26px;
        }

        .header {
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .header .app-name {
            font-size: 13px;
            color: #6b7280;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 0 0 4px;
        }

        .header h1 {
            font-size: 18px;
            margin: 0;
        }

        .header .sub {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        .badge {
            display: inline-block;
            font-size: 10px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 10px;
            margin-top: 6px;
        }

        .badge.ok {
            background: #dcfce7;
            color: #166534;
        }

        .badge.bad {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge.muted {
            background: #e5e7eb;
            color: #374151;
        }

        table.details {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        table.details td {
            padding: 6px 0;
            vertical-align: top;
        }

        table.details td.label {
            width: 170px;
            color: #374151;
            font-weight: bold;
        }

        table.details td.colon {
            width: 12px;
        }

        table.details td.value {
            color: #4b5563;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            margin: 18px 0 6px;
        }

        .expired-note {
            font-size: 11px;
            font-weight: bold;
            color: #991b1b;
            margin-top: 6px;
        }

        .footer-row {
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .footer-row table {
            width: 100%;
        }

        .footer-row .qr-cell,
        .footer-row .photo-cell {
            text-align: center;
            width: 50%;
        }

        .footer-row img.qr {
            width: 100px;
            height: 100px;
        }

        .footer-row img.photo {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #d1d5db;
        }

        .footer-row .caption {
            font-size: 10px;
            color: #6b7280;
            margin-top: 4px;
        }

        .generated {
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            margin-top: 16px;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="header">
            <p class="app-name">{{ config('app.name') }}</p>
            <h1>Vehicle Profile</h1>
            <div class="sub">{{ $data->vehicle_number }}</div>
            @php
                $expiredReasons = $data->expiredComplianceReasons();
            @endphp
            @if ($data->rc_verified_at)
                <span class="badge ok">RC Verified &middot; {{ $data->rc_verified_at->format('d M Y') }}</span>
            @else
                <span class="badge muted">Manually entered</span>
            @endif
            @if (!empty($expiredReasons))
                <span class="badge bad">Inactive &middot; {{ implode(', ', $expiredReasons) }} expired</span>
            @endif
        </div>

        @php
            $rows = [
                'Vehicle RC Number' => $data->vehicle_number,
                'Owner Name' => $data->owner_name,
                'Model' => $data->model_name,
                'Manufacturer' => $data->manufacturer,
                'Vehicle Class' => $data->vehicle_class,
                'Fuel Type' => $data->fuel_type,
                'Colour' => $data->colour,
                'Chassis No.' => $data->chassis_number,
                'Engine No.' => $data->engine_number,
                'Registration Date' => $data->registration_date ? safe_format_date($data->registration_date) : null,
                'RC Status' => $data->rc_status,
                'Financer' => $data->financer,
                'Owner Address' => $data->owner_address,
            ];

            $validityRows = [
                'RC Validity' => $data->rc_expiry_date ? safe_format_date($data->rc_expiry_date) : null,
                'Insurance Company' => $data->insurance_company,
                'Insurance Upto' => $data->insurance_upto ? safe_format_date($data->insurance_upto) : null,
                'PUCC Upto' => $data->pucc_upto ? safe_format_date($data->pucc_upto) : null,
                'Tax Upto' => $data->tax_upto ? safe_format_date($data->tax_upto) : null,
                'Permit Valid Upto' => $data->permit_valid_upto ? safe_format_date($data->permit_valid_upto) : null,
            ];
        @endphp

        <div class="section-title">Registration</div>
        <table class="details">
            @foreach ($rows as $label => $value)
                <tr>
                    <td class="label">{{ $label }}</td>
                    <td class="colon">:</td>
                    <td class="value">{{ $value ?: '—' }}</td>
                </tr>
            @endforeach
        </table>

        <div class="section-title">Validity &amp; Compliance</div>
        <table class="details">
            @foreach ($validityRows as $label => $value)
                <tr>
                    <td class="label">{{ $label }}</td>
                    <td class="colon">:</td>
                    <td class="value">{{ $value ?: '—' }}</td>
                </tr>
            @endforeach
            <tr>
                <td class="label">Status</td>
                <td class="colon">:</td>
                <td class="value">{{ $data->is_visible ? 'Active' : 'Inactive' }}</td>
            </tr>
        </table>

        @if (!empty($expiredReasons))
            <div class="expired-note">
                Reason for inactive: {{ implode(', ', $expiredReasons) }} validity has expired.
            </div>
        @endif

        <div class="footer-row">
            <table>
                <tr>
                    <td class="qr-cell">
                        <img class="qr" src="{{ $qrCodeDataUri }}" alt="QR Code">
                        <div class="caption">Scan to view vehicle record</div>
                    </td>
                    <td class="photo-cell">
                        @if ($photoPath)
                            <img class="photo" src="{{ $photoPath }}" alt="Photo">
                        @endif
                        <div class="caption">{{ $data->vehicle_number }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="generated">Generated on {{ now()->format('d M Y, h:i A') }}</div>
    </div>
</body>

</html>
