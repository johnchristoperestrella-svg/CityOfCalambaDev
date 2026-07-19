<?php
require 'config/helpers.php';
require 'config/database.php';

$db = new Database();

echo "Adding new test report...\n";

$sql = "INSERT INTO reports (title, description, type, generated_date, published_date, published_by, views, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $db->prepare($sql);

$title = "Test Real-Time Report - " . date('Y-m-d H:i:s');
$description = "This report was added at " . date('Y-m-d H:i:s') . " to test real-time functionality";
$type = "testing";
$generated_date = date('Y-m-d H:i:s');
$published_date = date('Y-m-d H:i:s');
$published_by = 14;
$views = 5;
$status = "published";

$stmt->bind_param('ssssssis', $title, $description, $type, $generated_date, $published_date, $published_by, $views, $status);

if($stmt->execute()) {
    echo "✅ New report added successfully!\n";
    echo "Title: $title\n";
    echo "Views: $views\n";
    echo "\nRefresh the Decision Support page to see it appear immediately!\n";
} else {
    echo "❌ Failed to add report\n";
}

$stmt->close();
?>
