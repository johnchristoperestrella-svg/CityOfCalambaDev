<?php
// Create admin user
$host = '127.0.0.1';
$username = 'root';
$password = '';
$dbName = 'calamba_popdev';

$conn = new mysqli($host, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$adminEmail = 'admin@calamba.gov.ph';
$adminPassword = password_hash('DefaultPass@123', PASSWORD_BCRYPT);
$adminName = 'System Administrator';
$adminRole = 'City Administrator';

// Check if user already exists
$checkQuery = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("s", $adminEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Admin user already exists.\n";
} else {
    // Insert new admin user
    $insertQuery = "INSERT INTO users (email, password, name, role, status) VALUES (?, ?, ?, ?, 'active')";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssss", $adminEmail, $adminPassword, $adminName, $adminRole);
    
    if ($stmt->execute()) {
        echo "✓ Admin user created successfully!\n";
        echo "Email: $adminEmail\n";
        echo "Password: DefaultPass@123\n";
    } else {
        echo "Error creating user: " . $conn->error . "\n";
    }
}

$conn->close();
?>
