<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Brand;
use App\Models\Models;
use App\Models\Color;
use App\Models\VehicleType;
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

class VehicleBulkController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Vehicle Bulk Upload Template', only: ['downloadTemplate']),
            new Middleware('permission:Vehicle Bulk Upload', only: ['bulkUpload']),
        ];
    }

    /**
     * Download the Excel Template for Bulk Vehicle Upload
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Vehicle Template');

        $headers = [
            'Name',
            'Vehicle Number',
            'RWC Number',
            'Engine Number',
            'Brand',
            'Model',
            'Color',
            'Body Type',
            'Vehicle Condition',
            'Transmission',
            'Fuel Type',
            'Left Hand Drive (Yes/No)',
            'Hybrid (Yes/No)',
            'Engine Type',
            'Vehicle Type',
            'AC Status',
            'Description',
            'Status',
        ];

        foreach ($headers as $index => $text) {
            $col = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue("{$col}1", $text);
        }

        $brands = Brand::pluck('name')->filter()->values()->toArray();
        $colors = Color::pluck('name')->filter()->values()->toArray();
        $vehicleTypes = VehicleType::pluck('name')->filter()->values()->toArray();
        $yesNo = ['Yes', 'No'];
        $statusOptions = ['Active', 'Inactive'];

        $createDropdown = function ($cellRange, $values) use ($sheet) {
            if (empty($values)) {
                return;
            }
            $validation = $sheet->getCell($cellRange)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowDropDown(true);
            $validation->setShowErrorMessage(true);
            $validation->setErrorTitle('Invalid Input');
            $validation->setError('Please select a valid value from the dropdown.');
            $validation->setFormula1('"' . implode(',', $values) . '"');
            $sheet->setDataValidation($cellRange, $validation);
        };

        $totalCols = count($headers);
        for ($i = 2; $i <= 200; $i++) {
            $createDropdown("E$i", $brands);         // Brand
            $createDropdown("G$i", $colors);          // Color
            $createDropdown("L$i", $yesNo);           // Left Hand Drive
            $createDropdown("M$i", $yesNo);           // Hybrid
            $createDropdown("O$i", $vehicleTypes);    // Vehicle Type
            $createDropdown("R$i", $statusOptions);   // Status
        }

        $lastHeaderCol = Coordinate::stringFromColumnIndex($totalCols);
        $sheet->getStyle("A1:{$lastHeaderCol}1")->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle("A1:{$lastHeaderCol}1")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD9EAF7');
        $sheet->getStyle("A1:{$lastHeaderCol}1")->getAlignment()->setHorizontal('center');

        $sheet->freezePane('A2');
        for ($i = 1; $i <= $totalCols; $i++) {
            $col = Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'VehicleBulkUploadTemplate.xlsx';
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
     * Handle Bulk Vehicle Upload
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
        $vehiclesToCreate = [];
        $vehicleNumbersSeen = [];
        $rwcNumbersSeen = [];
        $engineNumbersSeen = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $row = array_combine($headers, $row->toArray());

            $name          = trim($row['Name'] ?? '');
            $vehicleNumber = trim($row['Vehicle Number'] ?? '');
            $rwcNumber     = trim($row['RWC Number'] ?? '');
            $engineNumber  = trim($row['Engine Number'] ?? '');
            $brandName     = trim($row['Brand'] ?? '');
            $modelName     = trim($row['Model'] ?? '');
            $colorName     = trim($row['Color'] ?? '');
            $bodyType      = trim($row['Body Type'] ?? '');
            $condition     = trim($row['Vehicle Condition'] ?? '');
            $transmission  = trim($row['Transmission'] ?? '');
            $fuelType      = trim($row['Fuel Type'] ?? '');
            $leftHandDrive = trim($row['Left Hand Drive (Yes/No)'] ?? '');
            $hybrid        = trim($row['Hybrid (Yes/No)'] ?? '');
            $engineType    = trim($row['Engine Type'] ?? '');
            $vehicleTypeName = trim($row['Vehicle Type'] ?? '');
            $acStatus      = trim($row['AC Status'] ?? '');
            $description   = trim($row['Description'] ?? '');
            $status        = trim($row['Status'] ?? '');

            // Skip completely blank rows
            if ($name === '' && $vehicleNumber === '' && $rwcNumber === '' && $engineNumber === '') {
                continue;
            }

            if ($name === '') {
                $errors[] = "Row {$rowNumber}: Name is required.";
                continue;
            }
            if ($vehicleNumber === '') {
                $errors[] = "Row {$rowNumber}: Vehicle Number is required.";
                continue;
            }
            if ($rwcNumber === '') {
                $errors[] = "Row {$rowNumber}: RWC Number is required.";
                continue;
            }
            if ($engineNumber === '') {
                $errors[] = "Row {$rowNumber}: Engine Number is required.";
                continue;
            }

            $vehicleNumberKey = strtoupper($vehicleNumber);
            $rwcNumberKey = strtoupper($rwcNumber);
            $engineNumberKey = strtoupper($engineNumber);

            // Uniqueness against DB
            if (Vehicle::where('vehicle_number', $vehicleNumber)->exists()) {
                $errors[] = "Row {$rowNumber}: Vehicle Number '{$vehicleNumber}' already exists.";
                continue;
            }
            if (Vehicle::where('rwc_number', $rwcNumber)->exists()) {
                $errors[] = "Row {$rowNumber}: RWC Number '{$rwcNumber}' already exists.";
                continue;
            }
            if (Vehicle::where('engine_number', $engineNumber)->exists()) {
                $errors[] = "Row {$rowNumber}: Engine Number '{$engineNumber}' already exists.";
                continue;
            }

            // Uniqueness within the same file
            if (isset($vehicleNumbersSeen[$vehicleNumberKey])) {
                $errors[] = "Row {$rowNumber}: Vehicle Number '{$vehicleNumber}' is duplicated in the file (row {$vehicleNumbersSeen[$vehicleNumberKey]}).";
                continue;
            }
            if (isset($rwcNumbersSeen[$rwcNumberKey])) {
                $errors[] = "Row {$rowNumber}: RWC Number '{$rwcNumber}' is duplicated in the file (row {$rwcNumbersSeen[$rwcNumberKey]}).";
                continue;
            }
            if (isset($engineNumbersSeen[$engineNumberKey])) {
                $errors[] = "Row {$rowNumber}: Engine Number '{$engineNumber}' is duplicated in the file (row {$engineNumbersSeen[$engineNumberKey]}).";
                continue;
            }

            $brand = null;
            if ($brandName !== '') {
                $brand = Brand::where('name', $brandName)->first();
                if (!$brand) {
                    $errors[] = "Row {$rowNumber}: Brand '{$brandName}' not found.";
                    continue;
                }
            } else {
                $errors[] = "Row {$rowNumber}: Brand is required.";
                continue;
            }

            $model = null;
            if ($modelName !== '') {
                $model = Models::where('name', $modelName)->where('brand_id', $brand->id)->first();
                if (!$model) {
                    $errors[] = "Row {$rowNumber}: Model '{$modelName}' not found for brand '{$brandName}'.";
                    continue;
                }
            } else {
                $errors[] = "Row {$rowNumber}: Model is required.";
                continue;
            }

            $color = null;
            if ($colorName !== '') {
                $color = Color::where('name', $colorName)->first();
                if (!$color) {
                    $errors[] = "Row {$rowNumber}: Color '{$colorName}' not found.";
                    continue;
                }
            } else {
                $errors[] = "Row {$rowNumber}: Color is required.";
                continue;
            }

            $vehicleTypeId = null;
            if ($vehicleTypeName !== '') {
                $vehicleType = VehicleType::where('name', $vehicleTypeName)->first();
                if (!$vehicleType) {
                    $errors[] = "Row {$rowNumber}: Vehicle Type '{$vehicleTypeName}' not found.";
                    continue;
                }
                $vehicleTypeId = $vehicleType->id;
            }

            $normalizedLeftHand = strtolower($leftHandDrive);
            $isLeftHandDrive = in_array($normalizedLeftHand, ['yes', '1', 'true']) ? 1 : 0;

            $normalizedHybrid = strtolower($hybrid);
            $isHybrid = in_array($normalizedHybrid, ['yes', '1', 'true']) ? 1 : 0;

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

            $vehicleNumbersSeen[$vehicleNumberKey] = $rowNumber;
            $rwcNumbersSeen[$rwcNumberKey] = $rowNumber;
            $engineNumbersSeen[$engineNumberKey] = $rowNumber;

            $vehiclesToCreate[] = [
                'name' => $name,
                'vehicle_number' => $vehicleNumber,
                'rwc_number' => $rwcNumber,
                'engine_number' => $engineNumber,
                'brand_id' => $brand->id,
                'model_id' => $model->id,
                'color_id' => $color->id,
                'body_type' => $bodyType !== '' ? $bodyType : null,
                'vehicle_condition' => $condition !== '' ? $condition : null,
                'transmisssion' => $transmission !== '' ? $transmission : null,
                'fuel_type' => $fuelType !== '' ? $fuelType : null,
                'left_hand_drive' => $isLeftHandDrive,
                'hybird' => $isHybrid,
                'engine_type' => $engineType !== '' ? $engineType : null,
                'vehicle_type' => $vehicleTypeId,
                'ac_status' => $acStatus !== '' ? $acStatus : null,
                'description' => $description !== '' ? $description : null,
                'is_visible' => $isVisible,
            ];
        }

        if (!empty($errors)) {
            $errorMessage = 'Bulk upload failed: ' . implode(' | ', $errors);
            return back()->with('error', $errorMessage);
        }

        if (empty($vehiclesToCreate)) {
            return back()->with('error', 'No valid vehicle rows were found in the uploaded file.');
        }

        DB::beginTransaction();
        try {
            $companyId = auth()->user()->companyId();

            foreach ($vehiclesToCreate as $vehicleData) {
                $vehicleData['company_id'] = $companyId;
                Vehicle::create($vehicleData);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Vehicle bulk upload failed: ' . $e->getMessage());
            return back()->with('error', 'Bulk upload failed: ' . $e->getMessage());
        }

        return back()->with('success', count($vehiclesToCreate) . ' vehicle(s) uploaded successfully.');
    }
}
