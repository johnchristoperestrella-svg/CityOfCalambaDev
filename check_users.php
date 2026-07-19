<?php
require 'config/helpers.php';
require 'config/database.php';

$db = new Database();

echo "=== Checking Users ===\n";
$users = $db->query("SELECT id, username, email FROM users LIMIT 10");
echo "Found " . $users->num_rows . " users\n";
while($user = $users->fetch_assoc()) {
    echo " - ID {$user['id']}: {$user['username']} ({$user['email']})\n";
}
?>
