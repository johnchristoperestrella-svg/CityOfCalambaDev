<?php
require_once 'config/helpers.php';
require_once 'config/database.php';

$db = new Database();
$result = $db->query('SELECT id, email, profile_photo FROM users WHERE email = "admin@calamba.gov.ph"');
$user = $result->fetch_assoc();

if ($user) {
    echo 'User ID: ' . $user['id'] . PHP_EOL;
    echo 'Email: ' . $user['email'] . PHP_EOL;
    echo 'Profile Photo: ' . ($user['profile_photo'] ?? 'NULL') . PHP_EOL;
    
    // Also check if the file exists
    if ($user['profile_photo']) {
        $filePath = base_path('public' . $user['profile_photo']);
        echo 'File Path: ' . $filePath . PHP_EOL;
        echo 'File Exists: ' . (file_exists($filePath) ? 'YES' : 'NO') . PHP_EOL;
    }
} else {
    echo 'User not found' . PHP_EOL;
}
?>
