<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DriverBulkController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Driver Bulk Upload Template', only: ['downloadTemplate']),
            new Middleware('permission:Driver Bulk Upload', only: ['bulkUpload']),
        ];
    }

    /**
     * Download the Excel Template for Bulk Driver Upload
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Driver Template');

        $headers = [
            'First Name',
            'Last Name',
            'Email',
            'Phone (10-digit)',
            'Password (optional)',
            'Gender',
            'Date of Birth (YYYY-MM-DD)',
            'Address',
            'Aadhaar Number',
            'PAN Card Number',
            'Driving License Number',
            'Vehicle Type',
            'Vehicle Number',
            'Driving Experience (years)',
            'Status',
        ];

        foreach ($headers as $index => $text) {
            $col = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue("{$col}1", $text);
        }

        $genders = ['Male', 'Female', 'Others'];
        $vehicleTypes = VehicleType::pluck('name')->filter()->values()->toArray();
        $vehicleNumbers = Vehicle::pluck('vehicle_number')->filter()->values()->toArray();
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
            $createDropdown("F$i", $genders);          // Gender
            $createDropdown("L$i", $vehicleTypes);      // Vehicle Type
            $createDropdown("M$i", $vehicleNumbers);    // Vehicle Number
            $createDropdown("O$i", $statusOptions);     // Status

            // Phone must be a 10-digit number
            $validation = $sheet->getCell("D$i")->getDataValidation();
            $validation->setType(DataValidation::TYPE_WHOLE);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowErrorMessage(true);
            $validation->setErrorTitle('Invalid Phone');
            $validation->setError('Please enter a valid 10-digit number.');
            $validation->setOperator(DataValidation::OPERATOR_BETWEEN);
            $validation->setFormula1(1000000000);
            $validation->setFormula2(9999999999);
            $sheet->setDataValidation("D$i", $validation);
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

        $fileName = 'DriverBulkUploadTemplate.xlsx';
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
     * Handle Bulk Driver Upload
     */
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'bulk_file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('bulk_file');
        $authUser = auth()->user();

        $rows = \Maatwebsite\Excel\Facades\Excel::toCollection(null, $file)->first();

        if (!$rows || $rows->isEmpty()) {
            return back()->with('error', 'The uploaded file is empty or invalid.');
        }

        $headers = $rows->shift()->toArray();
        $headers = array_map('trim', $headers);

        $errors = [];
        $driversToCreate = [];
        $emailsSeen = [];
        $phonesSeen = [];
        $licensesSeen = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $row = array_combine($headers, $row->toArray());

            $firstName    = trim($row['First Name'] ?? '');
            $lastName     = trim($row['Last Name'] ?? '');
            $email        = trim($row['Email'] ?? '');
            $phone        = trim($row['Phone (10-digit)'] ?? '');
            $password     = trim($row['Password (optional)'] ?? '');
            $gender       = trim($row['Gender'] ?? '');
            $dob          = $row['Date of Birth (YYYY-MM-DD)'] ?? null;
            $address      = trim($row['Address'] ?? '');
            $aadhaar      = trim($row['Aadhaar Number'] ?? '');
            $pan          = trim($row['PAN Card Number'] ?? '');
            $license      = trim($row['Driving License Number'] ?? '');
            $vehicleTypeName = trim($row['Vehicle Type'] ?? '');
            $vehicleNumber   = trim($row['Vehicle Number'] ?? '');
            $experience   = trim($row['Driving Experience (years)'] ?? '');
            $status       = trim($row['Status'] ?? '');

            // Skip completely blank rows
            if ($firstName === '' && $lastName === '' && $email === '' && $phone === '') {
                continue;
            }

            if ($firstName === '' || !preg_match('/^[a-zA-Z\s]+$/', $firstName)) {
                $errors[] = "Row {$rowNumber}: First Name is required and must contain letters only.";
                continue;
            }
            if ($lastName === '' || !preg_match('/^[a-zA-Z\s]+$/', $lastName)) {
                $errors[] = "Row {$rowNumber}: Last Name is required and must contain letters only.";
                continue;
            }
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Row {$rowNumber}: A valid Email is required.";
                continue;
            }
            if (!preg_match('/^[6789]\d{9}$/', $phone)) {
                $errors[] = "Row {$rowNumber}: Phone must be a valid 10-digit number starting with 6-9.";
                continue;
            }
            if ($license === '') {
                $errors[] = "Row {$rowNumber}: Driving License Number is required.";
                continue;
            }
            if ($vehicleTypeName === '') {
                $errors[] = "Row {$rowNumber}: Vehicle Type is required.";
                continue;
            }

            // Uniqueness against DB
            if (User::where('email', $email)->exists()) {
                $errors[] = "Row {$rowNumber}: Email '{$email}' already exists.";
                continue;
            }
            if (User::where('phone', $phone)->exists()) {
                $errors[] = "Row {$rowNumber}: Phone '{$phone}' already exists.";
                continue;
            }
            if (Driver::where('driving_license_number', $license)->exists()) {
                $errors[] = "Row {$rowNumber}: Driving License Number '{$license}' already exists.";
                continue;
            }

            // Uniqueness within the same file
            $emailKey = strtolower($email);
            if (isset($emailsSeen[$emailKey])) {
                $errors[] = "Row {$rowNumber}: Email '{$email}' is duplicated in the file (row {$emailsSeen[$emailKey]}).";
                continue;
            }
            if (isset($phonesSeen[$phone])) {
                $errors[] = "Row {$rowNumber}: Phone '{$phone}' is duplicated in the file (row {$phonesSeen[$phone]}).";
                continue;
            }
            if (isset($licensesSeen[$license])) {
                $errors[] = "Row {$rowNumber}: Driving License Number '{$license}' is duplicated in the file (row {$licensesSeen[$license]}).";
                continue;
            }

            $vehicleType = VehicleType::where('name', $vehicleTypeName)->first();
            if (!$vehicleType) {
                $errors[] = "Row {$rowNumber}: Vehicle Type '{$vehicleTypeName}' not found.";
                continue;
            }

            $vehicleId = null;
            if ($vehicleNumber !== '') {
                $vehicle = Vehicle::where('vehicle_number', $vehicleNumber)->first();
                if (!$vehicle) {
                    $errors[] = "Row {$rowNumber}: Vehicle Number '{$vehicleNumber}' not found.";
                    continue;
                }
                $vehicleId = $vehicle->id;
            }

            $normalizedGender = strtolower($gender);
            $genderValue = 'male';
            if (in_array($normalizedGender, ['female'])) {
                $genderValue = 'female';
            } elseif (in_array($normalizedGender, ['others', 'other'])) {
                $genderValue = 'others';
            } elseif ($normalizedGender !== '' && $normalizedGender !== 'male') {
                $errors[] = "Row {$rowNumber}: Gender must be 'Male', 'Female' or 'Others'.";
                continue;
            }

            $dobFormatted = null;
            if ($dob !== null && $dob !== '') {
                try {
                    if (is_numeric($dob)) {
                        $dobFormatted = ExcelDate::excelToDateTimeObject($dob)->format('Y-m-d');
                    } else {
                        $dobFormatted = \Carbon\Carbon::parse($dob)->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    $errors[] = "Row {$rowNumber}: Date of Birth is not a valid date.";
                    continue;
                }
            }

            if ($aadhaar !== '' && !preg_match('/^\d{12}$/', $aadhaar)) {
                $errors[] = "Row {$rowNumber}: Aadhaar Number must be a 12-digit number.";
                continue;
            }
            if ($aadhaar !== '' && User::where('aadhar_card_number', $aadhaar)->exists()) {
                $errors[] = "Row {$rowNumber}: Aadhaar Number '{$aadhaar}' already exists.";
                continue;
            }

            if ($pan !== '' && !preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', strtoupper($pan))) {
                $errors[] = "Row {$rowNumber}: PAN Card Number format is invalid.";
                continue;
            }
            if ($pan !== '' && User::where('pan_card_number', $pan)->exists()) {
                $errors[] = "Row {$rowNumber}: PAN Card Number '{$pan}' already exists.";
                continue;
            }

            $isActive = 1;
            if ($status !== '') {
                $normalizedStatus = strtolower($status);
                if (in_array($normalizedStatus, ['active', 'visible', '1', 'yes', 'pending'])) {
                    $isActive = 1;
                } elseif (in_array($normalizedStatus, ['inactive', 'hidden', '0', 'no'])) {
                    $isActive = 0;
                } else {
                    $errors[] = "Row {$rowNumber}: Status must be either 'Active' or 'Inactive'.";
                    continue;
                }
            }

            $emailsSeen[$emailKey] = $rowNumber;
            $phonesSeen[$phone] = $rowNumber;
            $licensesSeen[$license] = $rowNumber;

            $driversToCreate[] = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'password' => $password !== '' ? $password : '12345678',
                'gender' => $genderValue,
                'date_of_birth' => $dobFormatted,
                'address' => $address !== '' ? $address : null,
                'aadhar_card_number' => $aadhaar !== '' ? $aadhaar : null,
                'pan_card_number' => $pan !== '' ? strtoupper($pan) : null,
                'status' => $isActive,
                'driving_license_number' => $license,
                'vehicle_type' => $vehicleType->id,
                'vehicle_id' => $vehicleId,
                'driving_exprience' => $experience !== '' ? $experience : null,
            ];
        }

        if (!empty($errors)) {
            $errorMessage = 'Bulk upload failed: ' . implode(' | ', $errors);
            return back()->with('error', $errorMessage);
        }

        if (empty($driversToCreate)) {
            return back()->with('error', 'No valid driver rows were found in the uploaded file.');
        }

        DB::beginTransaction();
        try {
            foreach ($driversToCreate as $driverData) {
                $user = User::create([
                    'status' => $driverData['status'],
                    'first_name' => $driverData['first_name'],
                    'last_name' => $driverData['last_name'],
                    'name' => $driverData['first_name'] . ' ' . $driverData['last_name'],
                    'date_of_birth' => $driverData['date_of_birth'],
                    'gender' => $driverData['gender'],
                    'email' => $driverData['email'],
                    'phone' => $driverData['phone'],
                    'address' => $driverData['address'],
                    'password' => bcrypt($driverData['password']),
                    'aadhar_card_number' => $driverData['aadhar_card_number'],
                    'pan_card_number' => $driverData['pan_card_number'],
                ]);

                $user->syncRoles('Driver');

                Driver::create([
                    'user_id' => $user->id,
                    'driving_license_number' => $driverData['driving_license_number'],
                    'vehicle_type' => $driverData['vehicle_type'],
                    'driving_exprience' => $driverData['driving_exprience'],
                    'vehicle_id' => $driverData['vehicle_id'],
                    'company_id' => $authUser->companyId(),
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Driver bulk upload failed: ' . $e->getMessage());
            return back()->with('error', 'Bulk upload failed: ' . $e->getMessage());
        }

        return back()->with('success', count($driversToCreate) . ' driver(s) uploaded successfully.');
    }
}
