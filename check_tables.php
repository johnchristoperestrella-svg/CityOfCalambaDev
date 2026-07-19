<?php
require 'config/helpers.php';
require 'config/database.php';

$db = new Database();

echo "=== Database Tables ===\n";
$result = $db->query("SHOW TABLES");
while($row = $result->fetch_row()) {
    echo $row[0] . "\n";
}

echo "\n=== Check for reports table ===\n";
$check = $db->query("SHOW TABLES LIKE '%report%'");
if($check->num_rows > 0) {
    echo "Reports table exists\n";
    while($row = $check->fetch_row()) {
        echo $row[0] . "\n";
    }
} else {
    echo "No reports table found\n";
}
?>
