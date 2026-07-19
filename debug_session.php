<?php
require_once 'config/helpers.php';

// Start session with custom function
session_start_custom();

$user = $_SESSION['user'] ?? null;

echo "Session User Data:" . PHP_EOL;
if ($user) {
    echo "ID: " . ($user['id'] ?? 'NULL') . PHP_EOL;
    echo "Email: " . ($user['email'] ?? 'NULL') . PHP_EOL;
    echo "Profile Photo: " . ($user['profile_photo'] ?? 'NULL') . PHP_EOL;
    echo "Name: " . ($user['name'] ?? 'NULL') . PHP_EOL;
} else {
    echo "No user in session" . PHP_EOL;
}

echo PHP_EOL . "Session Save Path: " . session_save_path() . PHP_EOL;
echo "Session ID: " . session_id() . PHP_EOL;
?>
