<?php
require 'config/helpers.php';
require 'config/database.php';

$db = new Database();

echo "=== Resetting Document Views to 0 ===\n";

$sql = "UPDATE documents SET views = 0";
if ($db->query($sql)) {
    echo "✓ All document views reset to 0\n";
    
    // Show current state
    $result = $db->query("SELECT id, title, views FROM documents ORDER BY id");
    while($row = $result->fetch_assoc()) {
        echo "  - {$row['title']}: {$row['views']} views\n";
    }
} else {
    echo "Error: Failed to reset views\n";
}
?>
