<?php
// Debug the worksheet structure
$filePath = 'test_template.xlsx';
$zip = new ZipArchive();
if ($zip->open($filePath) === true) {
    $xmlData = $zip->getFromName('xl/worksheets/sheet1.xml');
    $zip->close();
    
    echo "=== First 2000 characters of Sheet1 XML ===\n";
    echo substr($xmlData, 0, 2000) . "\n";
}
