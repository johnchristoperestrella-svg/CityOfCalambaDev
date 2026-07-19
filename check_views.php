<?php
require 'config/helpers.php';
require 'config/database.php';

$db = new Database();

echo "=== Checking Document Views in Database ===\n";
$result = $db->query("SELECT id, title, views FROM documents ORDER BY id");
while($row = $result->fetch_assoc()) {
    echo "{$row['id']}: {$row['title']} - {$row['views']} views\n";
}
?>
