<?php
require 'config/database.php';

$db = new Database();
$result = $db->query('SELECT COUNT(*) as count FROM individuals');
$row = $result->fetch_assoc();
echo "Total individuals: " . $row['count'] . "\n";

// Check by barangay
for($i = 1; $i <= 5; $i++) {
    $result2 = $db->query("SELECT COUNT(*) as count FROM individuals WHERE barangay_id = $i");
    $row2 = $result2->fetch_assoc();
    echo "Barangay $i: " . $row2['count'] . " members\n";
}

// Show first 10 individuals
echo "\nFirst 10 individuals:\n";
$result3 = $db->query('SELECT id, first_name, last_name, barangay_id FROM individuals LIMIT 10');
while($r = $result3->fetch_assoc()) {
    echo "#" . $r['id'] . " - " . $r['first_name'] . " " . $r['last_name'] . " (Barangay: " . $r['barangay_id'] . ")\n";
}
