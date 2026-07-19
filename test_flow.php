<?php
// Comprehensive test of the upload flow

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/config/database.php';

use App\Controllers\DataImportController;
use App\Models\DataImport;
use App\Models\Household;
use App\Models\Individual;
use App\Models\Barangay;
use App\Models\Analytics;
use App\ML_Models\ExcelParser;

echo "=== Testing Full Upload Flow ===\n\n";

// Step 1: Test file existence
$testFile = __DIR__ . '/test_data.csv';
echo "1. File Check:\n";
echo "   File exists: " . (file_exists($testFile) ? 'YES' : 'NO') . "\n";
echo "   File size: " . filesize($testFile) . " bytes\n\n";

// Step 2: Test parser
echo "2. Parser Test:\n";
$parser = new ExcelParser();
$parseResult = $parser->parseFile($testFile);
echo "   Parse result: " . ($parseResult ? 'SUCCESS' : 'FAILED') . "\n";

if (!$parseResult) {
    echo "   Errors:\n";
    foreach ($parser->getErrors() as $error) {
        echo "     - $error\n";
    }
    exit(1);
}

$data = $parser->getData();
echo "   Records parsed: " . count($data) . "\n";
if (count($data) > 0) {
    echo "   First record: " . json_encode($data[0]) . "\n";
}
echo "\n";

// Step 3: Test models
echo "3. Model Test:\n";
try {
    $barangayModel = new Barangay();
    $barangays = $barangayModel->getAll();
    echo "   Barangays found: " . count($barangays) . "\n";
    
    $householdModel = new Household();
    echo "   Household model initialized: OK\n";
    
    $individualModel = new Individual();
    echo "   Individual model initialized: OK\n";
    
    $analyticsModel = new Analytics();
    echo "   Analytics model initialized: OK\n";
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
echo "\n";

// Step 4: Test batch insert simulation
echo "4. Batch Insert Simulation:\n";
try {
    $householdValues = [];
    $barangayId = 1;
    
    foreach ($data as $record) {
        echo "   Processing: " . $record['name'] . "\n";
        $householdValues[] = [
            'barangay_id' => $barangayId,
            'import_id' => 0, // dummy
            'household_head' => $record['name'],
            'address' => $record['address'],
            'member_count' => (int)$record['family_members'],
            'socioeconomic_status' => 'middle' // dummy
        ];
    }
    echo "   Prepared " . count($householdValues) . " households for insert\n";
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
echo "\n";

echo "=== All Tests Passed ===\n";
