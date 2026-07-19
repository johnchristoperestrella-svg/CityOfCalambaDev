<?php
define('BASE_PATH', __DIR__);
require_once 'config/autoload.php';
require_once 'config/database.php';

$db = new Database();

echo "=== POST-DELETE DATABASE VERIFICATION ===\n\n";

// Check imports
echo "1. DATA IMPORTS:\n";
$imports = $db->query('SELECT id, file_name, processed_records FROM data_imports ORDER BY id DESC');
if ($imports && $imports->num_rows > 0) {
    while ($row = $imports->fetch_assoc()) {
        echo "   ID: {$row['id']} | File: {$row['file_name']} | Records: {$row['processed_records']}\n";
    }
} else {
    echo "   No imports found\n";
}

// Check households count
echo "\n2. TOTAL HOUSEHOLDS:\n";
$hh = $db->query('SELECT COUNT(*) as cnt FROM households');
$hhRow = $hh->fetch_assoc();
echo "   Total: {$hhRow['cnt']}\n";

// Check households per import
echo "\n3. HOUSEHOLDS PER IMPORT:\n";
$imports2 = $db->query('SELECT id, file_name FROM data_imports ORDER BY id DESC');
if ($imports2 && $imports2->num_rows > 0) {
    while ($imp = $imports2->fetch_assoc()) {
        $count = $db->query("SELECT COUNT(*) as cnt FROM households WHERE import_id = {$imp['id']}");
        $countRow = $count->fetch_assoc();
        echo "   Import {$imp['id']} ({$imp['file_name']}): {$countRow['cnt']} households\n";
    }
}

// Check total individuals
echo "\n4. TOTAL INDIVIDUALS:\n";
$ind = $db->query('SELECT COUNT(*) as cnt FROM individuals');
$indRow = $ind->fetch_assoc();
echo "   Total: {$indRow['cnt']}\n";

echo "\n=== VERIFICATION COMPLETE ===\n";
?>
