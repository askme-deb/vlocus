<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Ticket</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            width: 2in;
            margin: 0;
            padding: 10px;
            background-color: #fff;
        }

        .header,
        .footer {
            text-align: center;
        }

        .header h2 {
            margin: 0;
            font-size: 14px;
        }

        .header p,
        .footer p {
            margin: 2px 0;
            font-size: 10px;
        }

        .divider {
            border: 1px dashed #000;
            margin: 5px 0;
        }

        .info {
            font-size: 10px;
            margin-bottom: 5px;
        }

        .info p {
            margin: 2px 0;
            text-align: left;
        }

        .table {
            width: 100%;
            font-size: 10px;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            text-align: left;
            padding: 2px 0;
        }

        .amount {
            font-size: 12px;
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- Ticket Header -->
    <div class="header">
        <h2>Sarothi Bus Services</h2>
        <p>Kolkata</p>
        <p>+91 9547480822 | sarothi@gmail.com</p>
    </div>

    <hr class="divider">

    <!-- Passenger & Booking Details -->
    <div class="info">
        <p><strong>Passenger Details</strong></p>
        <p>Name: <strong>{{ $booking->user->name }}</strong></p>
        <p>Mobile: <strong>+91 {{ $booking->user->phone }}</strong></p>
        <p>Address: <strong>{{ $booking->user->address }}, India</strong></p>
    </div>

    <hr class="divider">

    <div class="info">
        <p><strong>Booking Details</strong></p>
        <p>Booking No: <strong>#{{ $booking->booking_number }}</strong></p>
        <p>Date: <strong>{{ $booking->created_at->format('d F Y h:i a') }}</strong></p>
    </div>

    <hr class="divider">

    @php
        $fromstoppage = $timeTable->stoppageTimeForStop($booking->fromStop->id)->first();
        $tostoppage = $timeTable->stoppageTimeForStop($booking->toStop->id)->first();
    @endphp
    <!-- Trip Details -->
    <div class="info">
        <p><strong>Trip Details</strong></p>
        <p>Route: <strong>{{ $booking->fromStop->name }} - {{ $booking->toStop->name }}</strong></p>
        <p>Bus No: <strong>{{ $booking->vehicle->vehicle_number }}</strong></p>
        <p>Departure: <strong>{{ format_time($fromstoppage->time) }}</strong></p>
        <p>Arrival: <strong>{{ format_time($tostoppage->time) }}</strong></p>
    </div>

    <hr class="divider">

    <!-- Seat & Fare Details -->
    {{-- <table class="table">
        <tr>
            <th>Seat No</th>
            <th>Fare</th>
        </tr>
        <tr>
            <td><strong>{{ implode(', ', $booked_seats) }}</strong></td>
            <td style="text-align: right;"><strong>{{ $booking->total_amount }}</strong></td>
        </tr>
    </table> --}}
    <table class="table">
        <tr>
            <th>Seat No</th>
            <th>Fare</th>
        </tr>
        @foreach($booked_seats as $booked_seat)
        <tr>
            <td><strong>{{ $booked_seat->seat_number }} @if(!empty($booked_seat->avaliable_from_stop_text))<small>( {{ $booked_seat->avaliable_from_stop_text }} )</small>@endif</strong></td>
            <td style="text-align: right;"><strong>{{ $booked_seat->amount }}</strong></td>
        </tr>
        @endforeach
    </table>

    <hr class="divider">

    <!-- Payment Summary -->
    <div class="info">
        <p class="amount">Total Amount Paid: {{ $booking->total_amount }}</p>
        {{-- <p class="amount">Wallet Balance: ₹600</p> --}}
    </div>

    <hr class="divider">


    <!-- QR Code Section -->
    <div style="text-align: center; margin: 5px 0; font-size:10px">
        <p><strong>Scan QR Code for Booking Details</strong></p>
        <img src="{{ storage_path('app/public/' . $booking->qr_code) }}" alt="QR Code"
            style="width: 50px; height: 50px;">
    </div>


    <!-- Ticket Footer -->
    <div class="footer">
        <p>Thank You for choosing Sarothi Bus Services!</p>
        <p>Have a safe journey.</p>
    </div>

</body>

</html>
