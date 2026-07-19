<?php
require 'config/helpers.php';
require 'config/database.php';

$db = new Database();
$r = $db->query('SELECT * FROM reports');

echo "Total reports: " . $r->num_rows . "\n";

while($row = $r->fetch_assoc()) {
    echo $row['title'] . ' - Views: ' . $row['views'] . " - Status: " . $row['status'] . "\n";
}
?>
