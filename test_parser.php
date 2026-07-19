<?php
// Test the ExcelParser directly

// Load autoloader and config
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/config/database.php';

use App\ML_Models\ExcelParser;

$testFile = __DIR__ . '/test_data.csv';

echo "Testing ExcelParser with: $testFile\n";
echo "File exists: " . (file_exists($testFile) ? 'YES' : 'NO') . "\n\n";

$parser = new ExcelParser();
$result = $parser->parseFile($testFile);

echo "Parse result: " . ($result ? 'SUCCESS' : 'FAILURE') . "\n";

if ($result) {
    $data = $parser->getData();
    echo "Data count: " . count($data) . "\n";
    echo "First record: " . json_encode($data[0] ?? null) . "\n";
} else {
    echo "Errors:\n";
    foreach ($parser->getErrors() as $error) {
        echo "  - $error\n";
    }
}
