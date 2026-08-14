<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Shop;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DeliveryScheduleBulkController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Bulk Upload Template', only: ['downloadTemplate']),
            new Middleware('permission:Bulk Upload', only: ['bulkUpload']),
        ];
    }

    /**
     * Download the Excel Template for Bulk Upload
     */
    /*public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Delivery Schedule Template');

        // --- Column headers ---
        $headers = [
            'A1' => 'Delivery Date (YYYY-MM-DD)',
            'B1' => 'Driver',
            'C1' => 'Vehicle',
            'D1' => 'Shop',
            'E1' => 'Invoice No',
            'F1' => 'Delivery Note',
            'G1' => 'Payment Type',
            'H1' => 'Amount',
            'I1' => 'Product Title 1',
            'J1' => 'Product Unit 1',
            'K1' => 'Product Qty 1',
            'L1' => 'Product Title 2',
            'M1' => 'Product Unit 2',
            'N1' => 'Product Qty 2',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        // --- Fetch dropdown data ---
        $drivers = User::role('Driver')->pluck('name')->toArray();
        $vehicles = Vehicle::pluck('vehicle_number')->toArray();
        $shops = Shop::pluck('shop_name')->toArray();
        $paymentTypes = ['Pre-Paid', 'COD'];

        // --- Helper: Create dropdown ---
        $createDropdown = function ($cellRange, $values) use ($sheet) {
            $validation = $sheet->getCell($cellRange)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowDropDown(true);
            $validation->setShowErrorMessage(true);
            $validation->setErrorTitle('Invalid Input');
            $validation->setError('Please select a valid value from the dropdown.');
            $validation->setFormula1('"' . implode(',', $values) . '"');
            $sheet->setDataValidation($cellRange, $validation);
        };

        // --- Apply dropdowns for 50 rows ---
        for ($i = 2; $i <= 50; $i++) {
            $createDropdown("B$i", $drivers);      // Driver
            $createDropdown("C$i", $vehicles);     // Vehicle
            $createDropdown("D$i", $shops);        // Shop
            $createDropdown("G$i", $paymentTypes); // Payment Type
        }

        // --- Add instruction row (row 52) ---
        $sheet->setCellValue('A52', '⚠ NOTE: "Branch/Consignor" will be auto-assigned based on the logged-in user (Employee or Branch).');
        $sheet->mergeCells('A52:N52');
        $sheet->getStyle('A52')->getFont()->getColor()->setARGB('FF0000');
        $sheet->getStyle('A52')->getFont()->setItalic(true);

        // --- Style headers ---
        $sheet->getStyle('A1:N1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1:N1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD9EAF7');
        $sheet->getStyle('A1:N1')->getAlignment()->setHorizontal('center');

        // --- Freeze and Autosize ---
        $sheet->freezePane('A2');
        foreach (range('A', 'N') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // --- Set file for download ---
        $fileName = 'DeliveryScheduleTemplate.xlsx';
        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }*/

    
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Delivery Schedule Template');

        // --- Column headers ---
        $headers = [
            'Delivery Date (YYYY-MM-DD)',
            'Driver Contact (10-digit)',
            'Vehicle Number (no spaces)',
            'Shop Number',
            'Invoice No',
            'Delivery Note',
            'Payment Type',
            'Amount',
            'Product Name',
            'Product Unit',
            'Product Qty',
        ];

        // --- Set headers in first row ---
        foreach ($headers as $index => $text) {
            $col = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue("{$col}1", $text);
        }

        // --- Dropdown lists ---
        $paymentTypes = ['Pre-Paid', 'COD'];
        $productUnits = ['Box', 'Packet', 'Single', 'Cartoon', 'Bag'];

        // --- Helper: Create dropdown ---
        $createDropdown = function ($cellRange, $values) use ($sheet) {
            $validation = $sheet->getCell($cellRange)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowDropDown(true);
            $validation->setShowErrorMessage(true);
            $validation->setErrorTitle('Invalid Input');
            $validation->setError('Please select a valid value from the dropdown.');
            $validation->setFormula1('"' . implode(',', $values) . '"');
            $sheet->setDataValidation($cellRange, $validation);
        };

        // --- Apply validation rules for 50 rows ---
        $totalCols = count($headers);
        for ($i = 2; $i <= 50; $i++) {

            // (1) Driver Contact - must be numeric and exactly 10 digits
            $validation = $sheet->getCell("B$i")->getDataValidation();
            $validation->setType(DataValidation::TYPE_WHOLE);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowErrorMessage(true);
            $validation->setErrorTitle('Invalid Contact');
            $validation->setError('Please enter a valid 10-digit number.');
            $validation->setOperator(DataValidation::OPERATOR_BETWEEN);
            $validation->setFormula1(1000000000);
            $validation->setFormula2(9999999999);
            $sheet->setDataValidation("B$i", $validation);

            // (2) Vehicle Number - no spaces allowed
            $validation = $sheet->getCell("C$i")->getDataValidation();
            $validation->setType(DataValidation::TYPE_CUSTOM);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowErrorMessage(true);
            $validation->setErrorTitle('Invalid Vehicle No');
            $validation->setError('Spaces are not allowed in Vehicle Number.');
            $validation->setFormula1('=ISERROR(FIND(" ",C' . $i . '))');
            $sheet->setDataValidation("C$i", $validation);

            // (3) Payment Type - dropdown
            $createDropdown("G$i", $paymentTypes);

            // (4) Product Unit - dropdown
            $createDropdown("J$i", $productUnits);
        }

        // --- Style headers ---
        $lastHeaderCol = Coordinate::stringFromColumnIndex($totalCols);
        $sheet->getStyle("A1:{$lastHeaderCol}1")->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle("A1:{$lastHeaderCol}1")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD9EAF7');
        $sheet->getStyle("A1:{$lastHeaderCol}1")->getAlignment()->setHorizontal('center');

        // --- Freeze and Autosize ---
        $sheet->freezePane('A2');
        for ($i = 1; $i <= $totalCols; $i++) {
            $col = Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // --- Set file for download ---
        $fileName = 'DeliveryScheduleTemplate.xlsx';
        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }



    /**
     * Handle Bulk Upload
     */
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'bulk_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('bulk_file');
        $user = auth()->user();

        // ✅ Determine consignor ID
        if ($user->hasRole('Employee')) {
            $consignorId = $user->employee?->branch?->id;
        } elseif ($user->hasRole('Branch')) {
            $consignorId = $user->id;
        } else {
            return back()->with('error', 'Unauthorized user role for consignor.');
        }

        // ✅ Read Excel File
        $rows = \Maatwebsite\Excel\Facades\Excel::toCollection(null, $file)->first();

        if ($rows->isEmpty()) {
            return back()->with('error', 'The uploaded file is empty or invalid.');
        }

        // Extract headers and data separately
        $headers = $rows->shift()->toArray(); // first row = headers
        $headers = array_map('trim', $headers); // clean spaces

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // actual Excel row number (header = row 1)
            $row = array_combine($headers, $row->toArray()); // map header names to values

            // Now you can safely access by header text
            $deliveryDate  = $row['Delivery Date (YYYY-MM-DD)'] ?? null;
            $driverContact = $row['Driver Contact (10-digit)'] ?? null;
            $vehicleNumber = $row['Vehicle Number (no spaces)'] ?? null;
            $shopNumber    = $row['Shop Number'] ?? null;
            $invoiceNo     = $row['Invoice No'] ?? null;
            $deliveryNote  = $row['Delivery Note'] ?? null;
            $paymentType   = $row['Payment Type'] ?? null;
            $amount        = $row['Amount'] ?? 0;
            $product_name  = $row['Product Name'] ?? null;
            $product_unit  = $row['Product Unit'] ?? null;
            $product_qty   = $row['Product Qty'] ?? 0;

            // ✅ Required validation
            if (empty($deliveryDate) || empty($driverContact) || empty($vehicleNumber) || empty($shopNumber)) {
                return back()->with('error', "Missing required fields at row $rowNumber.");
            }

            if (is_numeric($deliveryDate)) {
                $deliveryDate = ExcelDate::excelToDateTimeObject($deliveryDate)->format('Y-m-d');
            }

            // ✅ Validate driver
            $driver = \App\Models\User::role('Driver')->where('phone', $driverContact)->first();
            if (!$driver) {
                return back()->with('error', "Driver with contact {$driverContact} not found at row $rowNumber.");
            }

            // ✅ Validate vehicle
            $vehicle = \App\Models\Vehicle::where('vehicle_number', $vehicleNumber)->first();
            if (!$vehicle) {
                return back()->with('error', "Vehicle with number {$vehicleNumber} not found at row $rowNumber.");
            }

            // ✅ Validate shop
            $shop = \App\Models\Shop::where('shop_number', $shopNumber)->first();
            if (!$shop) {
                return back()->with('error', "Shop with number {$shopNumber} not found at row $rowNumber.");
            }

            // ✅ Create delivery schedule
            if(\App\Models\DeliverySchedule::where('delivery_date',$deliveryDate)->where('driver_id',$driver->id)->where('vehicle_id',$vehicle->id)->exists()){
                $deliverySchedule = \App\Models\DeliverySchedule::where('delivery_date',$deliveryDate)->where('driver_id',$driver->id)->where('vehicle_id',$vehicle->id)->first();
            }else{
                $deliverySchedule = \App\Models\DeliverySchedule::create([
                    'delivery_date' => $deliveryDate,
                    'order_id'      => generateOrderNumber(),
                    'driver_id'     => $driver->id,
                    'vehicle_id'    => $vehicle->id,
                    'delivery_note' => $deliveryNote,
                    'payment_type'  => $paymentType,
                    'amount'        => $amount,
                ]);
            }

            if(\App\Models\DeliveryScheduleShop::where('invoice_no',$invoiceNo)->exists()){
                $delivery_schedule = \App\Models\DeliveryScheduleShop::where('invoice_no',$invoiceNo)->first();
            }else{
                // ✅ Create delivery schedule shop
                $maxNumber = \App\Models\DeliveryScheduleShop::max('lr_no');
                $newNumber = $maxNumber ? $maxNumber + 1 : 10000;
    
                $delivery_schedule = \App\Models\DeliveryScheduleShop::create([
                    'lr_no'                => $newNumber,
                    'delivery_schedule_id' => $deliverySchedule->id,
                    'shop_id'              => $shop->id,
                    'sender_branch_id'     => $consignorId,
                    'invoice_no'           => $invoiceNo,
                    'delivery_note'        => $deliveryNote,
                    'payment_type'         => $paymentType,
                    'amount'               => $amount,
                    'otp'                  => 1234,
                ]);
            }

            \App\Models\DeliveryScheduleShopProduct::create([
                'delivery_schedule_shop_id' => $delivery_schedule->id,
                'title' => $product_name,
                'unit_or_box' => $product_unit,
                'qty' => $product_qty,
            ]);
        }

        return back()->with('success', 'Bulk Delivery Schedules uploaded successfully.');
    }
}
