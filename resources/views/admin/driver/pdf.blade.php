<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Driver Profile</title>
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
            width: 150px;
            color: #374151;
            font-weight: bold;
        }

        table.details td.colon {
            width: 12px;
        }

        table.details td.value {
            color: #4b5563;
        }

        .footer-row {
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .footer-row table {
            width: 100%;
        }

        .footer-row .qr-cell {
            text-align: center;
            width: 50%;
        }

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
            <h1>Driver Profile</h1>
        </div>

        @php
            $expiry = $driver->dl_nt_valid_to ?: $driver->dl_tr_valid_to;
            $dob = $driver->dl_dob ?: optional($user)->date_of_birth;
            $address = $driver->dl_address ?: optional($user)->address;

            $rows = [
                'NAME' => optional($user)->name,
                'License No.' => $driver->driving_license_number,
                'Authorization to Drive' => $driver->class_of_vehicle,
                'Date of Issue' => $driver->dl_issue_date ? safe_format_date($driver->dl_issue_date) : null,
                'DOB' => $dob ? safe_format_date($dob) : null,
                'S/W/D' => $driver->father_or_husband_name,
                'Date of Expiry' => $expiry ? safe_format_date($expiry) : null,
                'Address' => $address,
                'Mobile' => optional($user)->phone,
                'Email' => optional($user)->email,
                'Status' => optional($user)->status ? 'Active' : 'Inactive',
            ];
        @endphp

        <table class="details">
            @foreach ($rows as $label => $value)
                <tr>
                    <td class="label">{{ $label }}</td>
                    <td class="colon">:</td>
                    <td class="value">{{ $value ?: '—' }}</td>
                </tr>
            @endforeach
        </table>

        <div class="footer-row">
            <table>
                <tr>
                    <td class="qr-cell">
                        <img class="qr" src="{{ $qrCodeDataUri }}" alt="QR Code">
                        <div class="caption">Scan to view driver record</div>
                    </td>
                    <td class="photo-cell">
                        @if ($photoPath)
                            <img class="photo" src="{{ $photoPath }}" alt="Photo">
                        @endif
                        <div class="caption">{{ optional($user)->name }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="generated">Generated on {{ now()->format('d M Y, h:i A') }}</div>
    </div>
</body>

</html>
