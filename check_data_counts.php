<?php
require 'config/database.php';
require 'config/helpers.php';

$db = new Database();

echo "=== Actual Database Counts ===\n\n";

// Count barangays
$barangayResult = $db->query("SELECT COUNT(*) as count FROM barangays");
$barangayCount = $barangayResult->fetch_assoc()['count'];
echo "Barangays: $barangayCount\n";

// Count households
$householdResult = $db->query("SELECT COUNT(*) as count FROM households");
$householdCount = $householdResult->fetch_assoc()['count'];
echo "Households: $householdCount\n";

// Count individuals
$individualResult = $db->query("SELECT COUNT(*) as count FROM individuals");
$individualCount = $individualResult->fetch_assoc()['count'];
echo "Individuals: $individualCount\n";

echo "\n=== Barangay Details ===\n";
$barangays = $db->query("SELECT id, name, population FROM barangays ORDER BY id");
while($b = $barangays->fetch_assoc()) {
    echo "ID {$b['id']}: {$b['name']} - Population: {$b['population']}\n";
}

echo "\n=== Top 5 Individuals ===\n";
$individuals = $db->query("SELECT id, first_name, last_name, barangay_id FROM individuals LIMIT 5");
while($i = $individuals->fetch_assoc()) {
    echo "ID {$i['id']}: {$i['first_name']} {$i['last_name']} (Barangay: {$i['barangay_id']})\n";
}
?>
