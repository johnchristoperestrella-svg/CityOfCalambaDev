<?php
// Check admin user
$db = new mysqli('127.0.0.1', 'root', '', 'calamba_popdev');

if ($db->connect_error) {
    die("Connection error: " . $db->connect_error);
}

$email = 'admin@calamba.gov.ph';
$result = $db->query("SELECT id, name, email, role FROM users WHERE email = '$email'");

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "✅ Admin user exists:\n";
    echo "  ID: " . $user['id'] . "\n";
    echo "  Name: " . $user['name'] . "\n";
    echo "  Email: " . $user['email'] . "\n";
    echo "  Role: " . $user['role'] . "\n";
} else {
    echo "❌ Admin user NOT found. Creating one...\n";
    
    // Create admin user with password hash
    $password = 'DefaultPass@123';
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    $stmt = $db->prepare("INSERT INTO users (email, password, name, role, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $email, $hashed_password, $name, $role, $status);
    
    $name = "City Administrator";
    $role = "City Administrator";
    $status = "active";
    
    if ($stmt->execute()) {
        echo "✅ Admin user created successfully!\n";
        echo "  Email: $email\n";
        echo "  Password: $password\n";
        echo "  Role: City Administrator\n";
    } else {
        echo "❌ Failed to create admin user: " . $stmt->error . "\n";
    }
}

$db->close();
?>
