<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/config/database.php';

$db = new \Database();

// Check users
echo "=== Checking Users ===\n\n";

$result = $db->query("SELECT id, email, role FROM users LIMIT 10");

if ($result && $result->num_rows > 0) {
    echo "Users found:\n";
    while ($row = $result->fetch_assoc()) {
        echo "  ID: " . $row['id'] . ", Email: " . $row['email'] . ", Role: " . $row['role'] . "\n";
    }
} else {
    echo "No users found\n";
}

// Check data_imports table structure
echo "\n=== Data Imports Table Structure ===\n\n";

$result = $db->query("DESCRIBE data_imports");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . " - " . ($row['Null'] === 'YES' ? 'NULL' : 'NOT NULL') . "\n";
    }
}

// Check foreign key constraint
echo "\n=== Foreign Key Constraints ===\n\n";

$result = $db->query("SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME 
                       FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                       WHERE TABLE_NAME = 'data_imports' AND REFERENCED_TABLE_NAME IS NOT NULL");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Constraint: " . $row['CONSTRAINT_NAME'] . "\n";
        echo "  " . $row['TABLE_NAME'] . "." . $row['COLUMN_NAME'] . " -> " . $row['REFERENCED_TABLE_NAME'] . "." . $row['REFERENCED_COLUMN_NAME'] . "\n";
    }
}
