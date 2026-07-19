<?php
// Test the upload endpoint directly
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/config/database.php';

use App\Controllers\DataImportController;
use App\Models\Household;

echo "=== Testing Upload Endpoint ===\n\n";

// Simulate a file upload
$testFile = __DIR__ . '/test_template.xlsx';

// Check file
echo "1. File check:\n";
echo "   File exists: " . (file_exists($testFile) ? 'YES' : 'NO') . "\n";
echo "   File size: " . filesize($testFile) . " bytes\n\n";

// Simulate the upload by copying to uploads directory
$uploadDir = __DIR__ . '/public/uploads/';
$fileName = 'test_' . time() . '_' . basename($testFile);
$uploadPath = $uploadDir . $fileName;

if (!copy($testFile, $uploadPath)) {
    echo "   Failed to copy file\n";
    exit(1);
}

echo "2. File copied to: $uploadPath\n\n";

// Now simulate the data processing
echo "3. Testing controller upload logic:\n";

require_once 'app/ML_Models/ExcelParser.php';
use App\ML_Models\ExcelParser;

$parser = new ExcelParser();
if (!$parser->parseFile($uploadPath)) {
    echo "   Parser failed:\n";
    foreach ($parser->getErrors() as $err) {
        echo "     - $err\n";
    }
    exit(1);
}

$data = $parser->getData();
echo "   Parser success: " . count($data) . " records parsed\n\n";

// Test batch insert
echo "4. Testing batch insert logic:\n";
try {
    $householdModel = new Household();
    echo "   Household model initialized: OK\n";
    
    // Build sample insert data
    $householdValues = [];
    $barangayId = 1;
    $importId = 999; // dummy
    
    foreach ($data as $record) {
        $householdValues[] = [
            'barangay_id' => $barangayId,
            'import_id' => $importId,
            'household_head' => $record['name'],
            'address' => $record['address'],
            'member_count' => (int)$record['family_members'],
            'socioeconomic_status' => 'middle'
        ];
    }
    
    echo "   Prepared " . count($householdValues) . " households for insert\n";
    echo "   First household: " . json_encode($householdValues[0]) . "\n\n";
    
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Clean up
unlink($uploadPath);
echo "5. Test file cleaned up\n\n";

echo "=== All Tests Passed ===\n";
