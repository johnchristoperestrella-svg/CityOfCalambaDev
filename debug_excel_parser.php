<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/config/database.php';

use App\ML_Models\ExcelParser;

echo "=== Debugging Excel Parser ===\n\n";

// Create a reflection to access private methods
$parser = new ExcelParser();
$reflection = new ReflectionClass($parser);

// Create a simple test - read the Excel file directly
$filePath = 'test_template.xlsx';

// Try to open and read Excel
$zip = new ZipArchive();
if ($zip->open($filePath) === true) {
    $xmlData = $zip->getFromName('xl/worksheets/sheet1.xml');
    $sharedStrings = $zip->getFromName('xl/sharedStrings.xml');
    $zip->close();
    
    if ($xmlData && $sharedStrings) {
        echo "Excel file structure found\n";
        echo "Sheet1 XML size: " . strlen($xmlData) . " bytes\n";
        echo "SharedStrings XML size: " . strlen($sharedStrings) . " bytes\n\n";
        
        // Parse shared strings
        $sharedStringsXml = simplexml_load_string($sharedStrings);
        $strings = [];
        if ($sharedStringsXml) {
            foreach ($sharedStringsXml->si as $index => $si) {
                $strings[$index] = (string)$si->t;
            }
            echo "Shared strings count: " . count($strings) . "\n";
            echo "Strings: " . json_encode($strings) . "\n\n";
        }
        
        // Parse worksheet
        $xml = simplexml_load_string($xmlData);
        if ($xml && $xml->sheetData) {
            $rowCount = 0;
            foreach ($xml->sheetData->row as $row) {
                $rowCount++;
                $rowData = [];
                foreach ($row->c as $cell) {
                    $value = '';
                    if ((string)$cell['t'] === 's' && isset($strings[(int)$cell->v])) {
                        $value = $strings[(int)$cell->v];
                    } else {
                        $value = (string)$cell->v;
                    }
                    $rowData[] = $value;
                }
                echo "Row $rowCount: " . json_encode($rowData) . "\n";
                if ($rowCount >= 10) {
                    echo "... (showing first 10 rows)\n";
                    break;
                }
            }
        }
    }
} else {
    echo "Failed to open Excel file\n";
}
