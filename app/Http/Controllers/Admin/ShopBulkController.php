<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ShopBulkController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Shop Bulk Upload Template', only: ['downloadTemplate']),
            new Middleware('permission:Shop Bulk Upload', only: ['bulkUpload']),
        ];
    }

    /**
     * Download the Excel Template for Bulk Shop Upload
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Shop Template');

        // --- Column headers ---
        $headers = [
            'Shop Name',
            'Shop Address',
            'Contact Person Name',
            'Contact Person Phone (10-digit)',
            'Latitude',
            'Longitude',
            'Status',
        ];

        foreach ($headers as $index => $text) {
            $col = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue("{$col}1", $text);
        }

        // --- Dropdown list ---
        $statusOptions = ['Active', 'Inactive'];

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

        // --- Apply validation rules for 200 rows ---
        $totalCols = count($headers);
        for ($i = 2; $i <= 200; $i++) {
            // Status dropdown
            $createDropdown("G$i", $statusOptions);

            // Contact phone must be a 10-digit number
            $validation = $sheet->getCell("D$i")->getDataValidation();
            $validation->setType(DataValidation::TYPE_WHOLE);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowErrorMessage(true);
            $validation->setErrorTitle('Invalid Contact');
            $validation->setError('Please enter a valid 10-digit number.');
            $validation->setOperator(DataValidation::OPERATOR_BETWEEN);
            $validation->setFormula1(1000000000);
            $validation->setFormula2(9999999999);
            $sheet->setDataValidation("D$i", $validation);
        }

        // --- Style headers ---
        $lastHeaderCol = Coordinate::stringFromColumnIndex($totalCols);
        $sheet->getStyle("A1:{$lastHeaderCol}1")->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle("A1:{$lastHeaderCol}1")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD9EAF7');
        $sheet->getStyle("A1:{$lastHeaderCol}1")->getAlignment()->setHorizontal('center');

        // --- Freeze and Autosize ---
        $sheet->freezePane('A2');
        for ($i = 1; $i <= $totalCols; $i++) {
            $col = Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'ShopBulkUploadTemplate.xlsx';
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
     * Handle Bulk Shop Upload
     */
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'bulk_file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('bulk_file');

        $rows = \Maatwebsite\Excel\Facades\Excel::toCollection(null, $file)->first();

        if (!$rows || $rows->isEmpty()) {
            return back()->with('error', 'The uploaded file is empty or invalid.');
        }

        $headers = $rows->shift()->toArray();
        $headers = array_map('trim', $headers);

        $errors = [];
        $shopsToCreate = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // account for header row
            $row = array_combine($headers, $row->toArray());

            $shopName    = trim($row['Shop Name'] ?? '');
            $shopAddress = trim($row['Shop Address'] ?? '');
            $contactName = trim($row['Contact Person Name'] ?? '');
            $contactPhone = trim($row['Contact Person Phone (10-digit)'] ?? '');
            $latitude    = $row['Latitude'] ?? null;
            $longitude   = $row['Longitude'] ?? null;
            $status      = trim($row['Status'] ?? '');

            // Skip completely blank rows
            if ($shopName === '' && $shopAddress === '' && $contactName === '' && $contactPhone === '') {
                continue;
            }

            if ($shopName === '') {
                $errors[] = "Row {$rowNumber}: Shop Name is required.";
                continue;
            }
            if ($shopAddress === '') {
                $errors[] = "Row {$rowNumber}: Shop Address is required.";
                continue;
            }
            if ($contactName === '') {
                $errors[] = "Row {$rowNumber}: Contact Person Name is required.";
                continue;
            }
            if ($contactPhone === '' || !preg_match('/^\d{10}$/', (string) $contactPhone)) {
                $errors[] = "Row {$rowNumber}: Contact Person Phone must be a valid 10-digit number.";
                continue;
            }
            if ($latitude !== null && $latitude !== '' && !is_numeric($latitude)) {
                $errors[] = "Row {$rowNumber}: Latitude must be numeric.";
                continue;
            }
            if ($longitude !== null && $longitude !== '' && !is_numeric($longitude)) {
                $errors[] = "Row {$rowNumber}: Longitude must be numeric.";
                continue;
            }

            $isVisible = 1;
            if ($status !== '') {
                $normalizedStatus = strtolower($status);
                if (in_array($normalizedStatus, ['active', 'visible', '1', 'yes', 'pending'])) {
                    $isVisible = 1;
                } elseif (in_array($normalizedStatus, ['inactive', 'hidden', '0', 'no'])) {
                    $isVisible = 0;
                } else {
                    $errors[] = "Row {$rowNumber}: Status must be either 'Active' or 'Inactive'.";
                    continue;
                }
            }

            $shopsToCreate[] = [
                'shop_name' => $shopName,
                'shop_address' => $shopAddress,
                'shop_contact_person_name' => $contactName,
                'shop_contact_person_phone' => $contactPhone,
                'shop_latitude' => $latitude !== '' ? $latitude : null,
                'shop_longitude' => $longitude !== '' ? $longitude : null,
                'is_visible' => $isVisible,
            ];
        }

        if (!empty($errors)) {
            $errorMessage = 'Bulk upload failed: ' . implode(' | ', $errors);
            return back()->with('error', $errorMessage);
        }

        if (empty($shopsToCreate)) {
            return back()->with('error', 'No valid shop rows were found in the uploaded file.');
        }

        DB::beginTransaction();
        try {
            $maxNumber = Shop::max('shop_number');
            $nextNumber = $maxNumber ? $maxNumber + 1 : 10000;

            $companyId = auth()->user()->companyId();

            foreach ($shopsToCreate as $shopData) {
                $shopData['shop_number'] = $nextNumber;
                $shopData['company_id'] = $companyId;
                Shop::create($shopData);
                $nextNumber++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Shop bulk upload failed: ' . $e->getMessage());
            return back()->with('error', 'Bulk upload failed: ' . $e->getMessage());
        }

        return back()->with('success', count($shopsToCreate) . ' shop(s) uploaded successfully.');
    }
}
