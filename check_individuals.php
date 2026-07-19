<?php
$host = '127.0.0.1';
$username = 'root';
$password = '';
$dbName = 'calamba_popdev';

$conn = new mysqli($host, $username, $password, $dbName);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Check individuals per barangay
$sql = 'SELECT barangay_id, COUNT(*) as count FROM individuals GROUP BY barangay_id ORDER BY barangay_id';
$result = $conn->query($sql);
echo "Individuals per barangay:\n";
while ($row = $result->fetch_assoc()) {
    echo 'Barangay ID ' . $row['barangay_id'] . ': ' . $row['count'] . " individuals\n";
}

// Check total
$result = $conn->query('SELECT COUNT(*) as total FROM individuals');
$row = $result->fetch_assoc();
echo "\nTotal individuals: " . $row['total'] . "\n";

// Check barangays
$result = $conn->query('SELECT id, name FROM barangays ORDER BY id');
echo "\nBarangays:\n";
while ($row = $result->fetch_assoc()) {
    echo 'ID ' . $row['id'] . ': ' . $row['name'] . "\n";
}

$conn->close();
?>
