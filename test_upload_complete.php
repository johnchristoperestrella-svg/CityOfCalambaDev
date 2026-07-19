<?php
// Comprehensive test of entire upload flow with database
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/config/database.php';

use App\ML_Models\ExcelParser;
use App\Models\DataImport;
use App\Models\Household;
use App\Models\Individual;
use App\Models\Analytics;

echo "=== Comprehensive Upload Flow Test ===\n\n";

// Step 1: Parse file
echo "1. Parsing file...\n";
$parser = new ExcelParser();
if (!$parser->parseFile('test_template.xlsx')) {
    echo "   ERROR: Parser failed\n";
    foreach ($parser->getErrors() as $err) {
        echo "     - $err\n";
    }
    exit(1);
}

$data = $parser->getData();
echo "   SUCCESS: " . count($data) . " records parsed\n\n";

// Step 2: Create import record
echo "2. Creating import record...\n";
try {
    $importModel = new DataImport();
    $importData = [
        'user_id' => 2, // Use valid user ID
        'file_name' => 'test_template.xlsx',
        'file_path' => 'uploads/test_template.xlsx',
        'barangay_id' => 1,
        'total_records' => count($data),
        'processed_records' => 0
    ];
    
    $importId = $importModel->create($importData);
    if (!$importId) {
        throw new Exception("Failed to create import record");
    }
    
    echo "   SUCCESS: Import ID " . $importId . " created\n\n";
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 3: Prepare household data
echo "3. Preparing household data...\n";
try {
    $householdValues = [];
    $barangayId = 1;
    
    foreach ($data as $record) {
        // Determine socioeconomic status
        $salary = (float)$record['salary'];
        $familyMembers = (int)$record['family_members'];
        
        if ($salary > 50000) {
            $status = 'high';
        } elseif ($salary > 20000) {
            $status = 'middle';
        } else {
            $status = 'low';
        }
        
        $householdValues[] = [
            'barangay_id' => $barangayId,
            'import_id' => $importId,
            'household_head' => $record['name'],
            'address' => $record['address'],
            'member_count' => $familyMembers,
            'socioeconomic_status' => $status
        ];
    }
    
    echo "   SUCCESS: Prepared " . count($householdValues) . " households\n";
    echo "   First: " . json_encode($householdValues[0]) . "\n\n";
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 4: Batch insert households
echo "4. Batch inserting households...\n";
try {
    $db = new \Database();
    
    $columns = ['barangay_id', 'import_id', 'household_head', 'address', 'member_count', 'socioeconomic_status'];
    $placeholders = [];
    $values = [];
    $types = '';
    
    foreach ($householdValues as $household) {
        $placeholders[] = '(?, ?, ?, ?, ?, ?)';
        
        $values[] = $household['barangay_id'];
        $types .= 'i';
        $values[] = $household['import_id'];
        $types .= 'i';
        $values[] = $household['household_head'];
        $types .= 's';
        $values[] = $household['address'];
        $types .= 's';
        $values[] = $household['member_count'];
        $types .= 'i';
        $values[] = $household['socioeconomic_status'];
        $types .= 's';
    }
    
    $sql = "INSERT INTO households (" . implode(',', $columns) . ") 
            VALUES " . implode(',', $placeholders);
    
    echo "   SQL: " . substr($sql, 0, 100) . "...\n";
    
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $db->connection->error);
    }
    
    // Bind parameters
    $refs = array_merge(array($types), $values);
    $refRefs = [];
    foreach ($refs as $key => &$val) {
        $refRefs[$key] = &$val;
    }
    
    call_user_func_array([$stmt, 'bind_param'], $refRefs);
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $affectedRows = $stmt->affected_rows;
    echo "   SUCCESS: " . $affectedRows . " households inserted\n\n";
    
    $stmt->close();
    
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
    echo "   Trace:\n";
    echo "   " . $e->getTraceAsString() . "\n";
    exit(1);
}

echo "=== All Tests Passed ===\n";
echo "\nImport ID: $importId\n";
echo "Records processed: " . count($data) . "\n";
