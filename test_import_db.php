<?php
define('BASE_PATH', __DIR__);
require_once 'config/autoload.php';
require_once 'config/database.php';
$db = new Database();

echo '=== DATA IMPORTS ==='.PHP_EOL;
$imports = $db->fetch_all('SELECT id, file_name, barangay, total_records, processed_records, status FROM data_imports ORDER BY id DESC');
foreach ($imports as $imp) {
    echo 'ID: ' . $imp['id'] . ' | File: ' . $imp['file_name'] . ' | Records: ' . $imp['processed_records'] . PHP_EOL;
}

echo PHP_EOL . '=== HOUSEHOLDS PER IMPORT ===' . PHP_EOL;
foreach ($imports as $imp) {
    $count = $db->fetch_one('SELECT COUNT(*) as cnt FROM households WHERE import_id = ?', [$imp['id']]);
    echo 'Import ' . $imp['id'] . ' (' . $imp['file_name'] . '): ' . $count['cnt'] . ' households' . PHP_EOL;
}

echo PHP_EOL . '=== TOTAL INDIVIDUALS ===' . PHP_EOL;
$total = $db->fetch_one('SELECT COUNT(*) as cnt FROM individuals');
echo 'Total individuals in DB: ' . $total['cnt'] . PHP_EOL;
?>
