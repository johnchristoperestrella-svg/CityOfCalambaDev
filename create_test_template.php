<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/helpers.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Generate a test Excel template similar to what downloadTemplate() creates
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$headers = ['Household Head Name', 'Weight (kg)', 'Address', 'Salary (PHP)', 'Family Members'];
$sheet->fromArray($headers, NULL, 'A1');

$sampleData = [
    ['Juan Dela Cruz', 65, '123 Main Street', 25000, 4],
    ['Maria Santos', 58, '456 Oak Avenue', 30000, 5],
    ['Pedro Garcia', 72, '789 Maple Lane', 18000, 3],
];

$row = 2;
foreach ($sampleData as $data) {
    $sheet->fromArray($data, NULL, 'A' . $row);
    $row++;
}

// Add 5 empty rows
for ($i = 0; $i < 5; $i++) {
    $row++;
}

$writer = new Xlsx($spreadsheet);
$writer->save('test_template.xlsx');

echo "Template created: test_template.xlsx\n";
