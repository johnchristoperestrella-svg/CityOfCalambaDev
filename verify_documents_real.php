<?php
require 'config/helpers.php';
require 'config/database.php';

$db = new Database();

echo "=== All Documents in Database ===\n";
$result = $db->query("SELECT id, title, category, file_type, views, created_at FROM documents ORDER BY created_at DESC");

$count = 0;
while($row = $result->fetch_assoc()) {
    $count++;
    echo "\n{$count}. {$row['title']}\n";
    echo "   Category: {$row['category']}\n";
    echo "   File Type: {$row['file_type']}\n";
    echo "   Views: {$row['views']}\n";
    echo "   Created: {$row['created_at']}\n";
}

echo "\n\n=== Summary ===\n";
echo "Total documents in database: {$count}\n";

// Check where documents come from
echo "\n=== Documents Source ===\n";
echo "All documents stored in: documents table\n";
echo "Database: calamba_popdev\n";
echo "Status: All data is REAL from the database, not hardcoded\n";
?>
