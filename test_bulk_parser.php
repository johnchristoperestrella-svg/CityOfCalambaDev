<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/config/database.php';

use App\ML_Models\ExcelParser;

echo "=== Testing Parser with Bulk Data ===\n\n";

$parser = new ExcelParser();
$result = $parser->parseFile('public/uploads/6a20e6d3a87ab_bulk_data_500.csv');
echo "Parse result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
echo "Records parsed: " . count($parser->getData()) . "\n";

if (!empty($parser->getErrors())) {
    echo "\nFirst 10 errors:\n";
    foreach (array_slice($parser->getErrors(), 0, 10) as $err) {
        echo "  - $err\n";
    }
    echo "Total errors: " . count($parser->getErrors()) . "\n";
}

if ($result && count($parser->getData()) > 0) {
    echo "\nFirst 3 records:\n";
    foreach (array_slice($parser->getData(), 0, 3) as $record) {
        echo json_encode($record) . "\n";
    }
}
