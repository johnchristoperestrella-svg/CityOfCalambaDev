<?php
require 'config/helpers.php';
require 'config/database.php';

$db = new Database();
$r = $db->query('DESC reports');

echo "Reports Table Schema:\n";
while($row = $r->fetch_assoc()) {
    echo $row['Field'] . ': ' . $row['Type'] . "\n";
}

echo "\n\nSample Report Query:\n";
$report = $db->query('SELECT id, title, status, views FROM reports WHERE id = 1')->fetch_assoc();
print_r($report);
?>
