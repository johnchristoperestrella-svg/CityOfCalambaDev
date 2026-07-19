<?php
// Simple script to list users from the local calamba_popdev database
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'calamba_popdev';

$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) {
    echo "DB connection error: " . $mysqli->connect_error . PHP_EOL;
    exit(1);
}

$res = $mysqli->query("SELECT id, email, name, role, status, created_at FROM users ORDER BY id ASC");
if (!$res) {
    echo "Query error: " . $mysqli->error . PHP_EOL;
    exit(1);
}

echo "id | email | name | role | status | created_at" . PHP_EOL;
while ($row = $res->fetch_assoc()) {
    echo $row['id'] . ' | ' . $row['email'] . ' | ' . $row['name'] . ' | ' . $row['role'] . ' | ' . $row['status'] . ' | ' . $row['created_at'] . PHP_EOL;
}

$mysqli->close();
