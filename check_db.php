<?php
// Check database and user
$host = '127.0.0.1';
$username = 'root';
$password = '';
$dbName = 'calamba_popdev';

$conn = new mysqli($host, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if admin user exists
$query = "SELECT id, email, name, role, status FROM users WHERE email = 'admin@calamba.gov.ph'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "✓ Admin user found:\n";
    echo "ID: " . $user['id'] . "\n";
    echo "Email: " . $user['email'] . "\n";
    echo "Name: " . $user['name'] . "\n";
    echo "Role: " . $user['role'] . "\n";
    echo "Status: " . $user['status'] . "\n";
} else {
    echo "✗ Admin user NOT found in database\n";
}

// Check table count
$tablesQuery = "SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema='$dbName'";
$tablesResult = $conn->query($tablesQuery);
$tablesRow = $tablesResult->fetch_assoc();
echo "\nDatabase tables: " . $tablesRow['count'] . "\n";

// List all tables
$listQuery = "SHOW TABLES";
$listResult = $conn->query($listQuery);
echo "\nTables:\n";
while ($row = $listResult->fetch_row()) {
    echo "- " . $row[0] . "\n";
}

$conn->close();
?>
