<?php
require 'config/helpers.php';
require 'config/database.php';

$db = new Database();

echo "=== Adding Test Document ===\n";

$sql = "INSERT INTO documents (title, category, file_path, file_type, uploaded_by, views, created_at, updated_at) 
        VALUES ('Community Development Training Manual', 'Training', 'documents/training_manual.pdf', 'application/pdf', 2, 0, NOW(), NOW())";

if ($db->query($sql)) {
    echo "✓ Added: Community Development Training Manual\n";
    
    // Check total
    $result = $db->query("SELECT COUNT(*) as cnt FROM documents");
    $row = $result->fetch_assoc();
    echo "Total documents now: " . $row['cnt'] . "\n";
} else {
    echo "Error: Failed to add document\n";
}
?>
