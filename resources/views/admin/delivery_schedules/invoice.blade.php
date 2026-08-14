<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Note</title>
    <style>
        @media print {
            .print-button {
                display: none !important;
            }
        }
    </style>
</head>
<body style="font-family: Arial, sans-serif; margin:0; padding:0; background-color:#fff;">
<!-- Print Button -->
<div style="text-align: center; margin: 15px 0;">
    <button class="print-button" onclick="window.print();"
            style="background-color: black; color: white; border: none; padding: 10px 20px; 
                   font-weight: bold; cursor: pointer; border-radius: 4px;">
        Print Delivery Note
    </button>
</div>

<div style="width: 600px; margin: 0 auto; border: 2px solid #000; margin-top: 10px;">

    <!-- Header -->
    <div style="background-color: black; color: white; text-align: center; font-weight: bold; padding: 5px; font-size: 16px; border-bottom: 2px solid black;">
        DELIVERY NOTE
    </div>

    <!-- Company Info -->
    <div style="border: 2px solid black; display: flex; margin:10px; text-align: center;">
        <div style="border-right: 2px solid black; padding: 10px; display: flex; align-items: center; justify-content: center;">
            <img src="{{ asset('assets/invoice-assets/image.png') }}" alt="Logo" style="height: 60px;">
        </div>
        <div style="flex: 1;">
            <div style="font-weight: bold; font-size: 15px; background-color: black; color: white; padding: 5px 0;">
                {{-- SML FREIGHT SERVICE PVT. LTD. --}} {{ $invoiceData['consignor']->name }}
            </div>
            <div style="font-size: 10px; padding: 5px; line-height: 1.4;">
                {{ $invoiceData['consignor']->address }}<br>
                @if(!empty($invoiceData['consignor']->pan_card_number))PAN : {{ $invoiceData['consignor']->pan_card_number }},@endif GST: 19ABCPS4610B1Z1, CIN: U60100WB2021PTC243645<br>
                PHONE: {{ $invoiceData['consignor']->phone }}, MAIL ID: {{ $invoiceData['consignor']->email }}
            </div>
        </div>
    </div>

    <!-- Table -->
    <table style="width: calc(100% - 20px); margin: 10px 10px; border-collapse: collapse; font-size: 12px; border: 2px solid black;">
        <tr style="text-align: center; font-weight: bold; background-color: #000; color: white;">
            <th style="width: 40px; border: 2px solid black; padding: 5px;">SL</th>
            <th style="width: 190px; border: 2px solid black; padding: 5px;">DETAILS</th>
            <th style="width: 10px; border: 2px solid black; padding: 5px;"></th>
            <th style="border: 2px solid black; padding: 5px;">PARTICULARS</th>
        </tr>
        <tr>
            <td style="text-align:center; border: 2px solid black;">1</td>
            <td style="border: 2px solid black; padding: 10px 5px;">ORDER NO.</td>
            <td style="border: 2px solid black; padding: 10px 5px;">:</td>
            <td style="border: 2px solid black; padding: 10px 5px;">{{ $invoiceData['order_no'] }}</td>
        </tr>
        <tr>
            <td style="text-align:center; border: 2px solid black;">2</td>
            <td style="border: 2px solid black; padding: 10px 5px;">INVOICE NO.</td>
            <td style="border: 2px solid black; padding: 10px 5px;">:</td>
            <td style="border: 2px solid black; padding: 10px 5px;">{{ $invoiceData['invoice_no'] }}</td>
        </tr>
        <tr>
            <td style="text-align:center; border: 2px solid black;">3</td>
            <td style="border: 2px solid black; padding: 10px 5px;">CONSIGNOR DETAILS</td>
            <td style="border: 2px solid black; padding: 10px 5px;">:</td>
            <td style="border: 2px solid black; padding: 10px 5px;">{{ $invoiceData['consignor']->name }}</td>
        </tr>
        <tr>
            <td style="text-align:center; border: 2px solid black;">4</td>
            <td style="border: 2px solid black; padding: 10px 5px;">CONSIGNEE DETAILS</td>
            <td style="border: 2px solid black; padding: 10px 5px;">:</td>
            <td style="border: 2px solid black; padding: 10px 5px;">{{ $invoiceData['consignee']->shop_name }}<br>Address : {{ $invoiceData['consignee']->shop_address }}</td>
        </tr>
        <tr>
            <td style="text-align:center; border: 2px solid black;">5</td>
            <td style="border: 2px solid black; padding: 10px 5px;">PRODUCTS</td>
            <td style="border: 2px solid black; padding: 10px 5px;">:</td>
            <td style="border: 2px solid black; padding: 10px 5px;">{{ implode(', ', array_column($invoiceData['products'], 'title')) }}</td>
        </tr>
        <tr>
            <td style="text-align:center; border: 2px solid black;">6</td>
            <td style="border: 2px solid black; padding: 10px 5px;">ITEM DESCRIPTION WITH (PCS)</td>
            <td style="border: 2px solid black; padding: 10px 5px;">:</td>
            <td style="border: 2px solid black; padding: 10px 5px;">
                @foreach($invoiceData['products'] as $i => $product)
                    {{ $i+1 }}) {{ $product['title'] }} ({{ $product['qty'] }})<br>
                @endforeach
            </td>
        </tr>
        <tr>
            <td style="text-align:center; border: 2px solid black;">7</td>
            <td style="border: 2px solid black; padding: 10px 5px;">TOTAL ITEM NO. (PCS)</td>
            <td style="border: 2px solid black; padding: 10px 5px;">:</td>
            <td style="border: 2px solid black; padding: 10px 5px;">{{ $invoiceData['total_items'] }}</td>
        </tr>
        
        {{-- <tr><td style="text-align:center; border: 2px solid black;">8</td><td style="border: 2px solid black; padding: 10px 5px;">PAYMENT DETAILS</td><td style="border: 2px solid black;">: {{ $invoiceData['payment'] }}</td></tr> --}}
        <tr>
            <td style="text-align:center; border: 2px solid black;">9</td>
            <td style="border: 2px solid black; padding: 10px 5px;">DELIVERY DATE</td>
            <td style="border: 2px solid black; padding: 10px 5px;">:</td>
            <td style="border: 2px solid black; padding: 10px 5px;">{{ formated_date($invoiceData['delivery_date']) }}</td>
        </tr>
        <tr>
            <td style="text-align:center; border: 2px solid black;">10</td>
            <td style="border: 2px solid black; padding: 10px 5px;">VEHICLE DETAILS</td>
            <td style="border: 2px solid black; padding: 10px 5px;">:</td>
            <td style="border: 2px solid black; padding: 10px 5px;">{{ $invoiceData['vehicle']->name }} - {{ $invoiceData['vehicle']->vehicle_number }}</td>
        </tr>
        <tr>
            <td style="text-align:center; border: 2px solid black;">11</td>
            <td style="border: 2px solid black; padding: 10px 5px;">DRIVER DETAILS</td>
            <td style="border: 2px solid black; padding: 10px 5px;">:</td>
            <td style="border: 2px solid black; padding: 10px 5px;">{{ $invoiceData['driver']['name'] }}</td>
        </tr>
    </table>

    <!-- Location and Receiver -->
    <div style="display: flex; border: 2px solid black; margin: 0 10px;">
        <div style="flex: 1; border-right: 2px solid black; text-align: center;">
            <div style="font-weight: bold; background: #000; color: #fff; padding: 5px 0; font-size: 12px;">LOCATION MAP DETAILS</div>
            <img src="{{ asset('assets/invoice-assets/map.png') }}" alt="Map">
        </div>
        <div style="flex: 1; text-align: center;">
            <div style="font-weight: bold; background: #000; color: #fff; padding: 5px 0; font-size: 12px;">RECEIVER DETAILS</div>
            <img src="{{ asset('assets/invoice-assets/receiver.png') }}" alt="Receiver" style="margin: 10px 0;">
            <div style="border-top: 2px solid black; display: flex; align-items: center; font-weight: bold; font-size: 14px; width: auto;">
                <div style="background-color: black; color: white; padding: 2px 6px;">OTP:</div>
                <div style="padding: 2px 8px; color: #000;">{{ $invoiceData['otp'] }}</div>
            </div>
        </div>
    </div>

    <!-- Footer Signatures -->
    <div style="display: flex; padding: 10px; font-size: 12px; text-align: center; border-bottom: 2px solid black;">
        <div style="flex: 1;">
            For SML Freight Service Pvt. Ltd.<br><br>
            <img src="{{ asset('assets/invoice-assets/sign1.png') }}" alt="">
            <div style="margin-top: 5px;">_________________<br>SENDER SIGNATURE</div>
        </div>
        <div style="flex: 1;">
            For Receiver Details.<br><br>
            <img src="{{ asset('assets/invoice-assets/sign2.png') }}" alt="">
            <div style="margin-top: 5px;">_________________<br>RECEIVER SIGNATURE</div>
        </div>
    </div>

    <!-- Powered By -->
    <div style="background-color: black; color: white; font-size: 11px; text-align: center; padding: 5px;">
        Powered by (VLOCUS) Agiltas Solution Private Limited
    </div>

</div>
</body>
</html>
