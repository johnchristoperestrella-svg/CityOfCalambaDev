<?php
require_once 'config/helpers.php';
require_once 'config/database.php';

$db = new Database();

// Check if column exists
$result = $db->query('DESCRIBE users');
$columns = [];
while ($row = $result->fetch_assoc()) {
    $columns[] = $row['Field'];
}

if (!in_array('profile_photo', $columns)) {
    echo 'Adding profile_photo column...';
    try {
        $db->query('ALTER TABLE users ADD COLUMN profile_photo VARCHAR(500) NULL DEFAULT NULL AFTER status');
        echo " ✓ Success\n";
    } catch (Exception $e) {
        echo " Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "profile_photo column already exists\n";
}
?>
