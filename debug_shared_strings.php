<?php
// Debug the actual XML structure
$filePath = 'test_template.xlsx';
$zip = new ZipArchive();
if ($zip->open($filePath) === true) {
    $sharedStrings = $zip->getFromName('xl/sharedStrings.xml');
    $zip->close();
    
    echo "=== Shared Strings XML ===\n";
    echo $sharedStrings . "\n\n";
    
    echo "=== Parsed ===\n";
    $sharedStringsXml = simplexml_load_string($sharedStrings);
    foreach ($sharedStringsXml->si as $index => $si) {
        echo "Index $index:\n";
        echo "  t content: " . (string)$si->t . "\n";
        echo "  All children:\n";
        foreach ($si->children() as $child) {
            echo "    " . $child->getName() . ": " . (string)$child . "\n";
        }
    }
}
