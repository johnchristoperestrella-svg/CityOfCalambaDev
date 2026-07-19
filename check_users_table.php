<?php
require 'config/helpers.php';
require 'config/database.php';

$db = new Database();

echo "=== Users Table Structure ===\n";
$result = $db->query("DESCRIBE users");
while($col = $result->fetch_assoc()) {
    echo "{$col['Field']}: {$col['Type']}\n";
}

echo "\n=== Users in Database ===\n";
$users = $db->query("SELECT * FROM users LIMIT 5");
$num = $users->num_rows;
echo "Total users: {$num}\n";
if($num > 0) {
    $user = $users->fetch_assoc();
    echo "First user ID: " . $user['id'] . "\n";
}
?>
