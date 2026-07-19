<?php
require 'config/helpers.php';
require 'config/database.php';

$db = new Database();

echo "=== Checking Documents Table ===\n";
$result = $db->query("SELECT COUNT(*) as cnt FROM documents");
$row = $result->fetch_assoc();
echo "Total documents in database: " . $row['cnt'] . "\n";

echo "\n=== Checking Categories ===\n";
$categories = $db->query("SELECT DISTINCT category FROM documents");
echo "Total categories: " . $categories->num_rows . "\n";
while($cat = $categories->fetch_assoc()) {
    echo " - " . $cat['category'] . "\n";
}

echo "\n=== Sample Documents ===\n";
$docs = $db->query("SELECT id, title, category FROM documents LIMIT 5");
while($doc = $docs->fetch_assoc()) {
    echo "ID {$doc['id']}: {$doc['title']} ({$doc['category']})\n";
}
?>
