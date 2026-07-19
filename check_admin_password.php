<?php
// Check database user details
$host = '127.0.0.1';
$username = 'root';
$password = '';
$dbName = 'calamba_popdev';

$conn = new mysqli($host, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== User Database Check ===\n\n";

// Check admin user
$email = 'admin@calamba.gov.ph';
$testPassword = 'DefaultPass@123';

$query = "SELECT id, email, password, name, role, status FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    echo "✓ User found in database:\n";
    echo "  ID: " . $user['id'] . "\n";
    echo "  Email: " . $user['email'] . "\n";
    echo "  Name: " . $user['name'] . "\n";
    echo "  Role: " . $user['role'] . "\n";
    echo "  Status: " . $user['status'] . "\n";
    echo "  Password Hash: " . substr($user['password'], 0, 50) . "...\n\n";
    
    // Test password verification
    echo "Testing password verification:\n";
    echo "  Test Password: $testPassword\n";
    $verify = password_verify($testPassword, $user['password']);
    echo "  password_verify result: " . ($verify ? "TRUE ✓" : "FALSE ✗") . "\n\n";
    
    if (!$verify) {
        echo "Password verification FAILED!\n";
        echo "\nTrying to re-hash the password...\n";
        
        // Generate new hash
        $newHash = password_hash($testPassword, PASSWORD_BCRYPT);
        echo "New hash: " . $newHash . "\n\n";
        
        // Update the database with the new hash
        $updateQuery = "UPDATE users SET password = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("si", $newHash, $user['id']);
        
        if ($updateStmt->execute()) {
            echo "✓ Password updated in database!\n";
            echo "Password is now: $testPassword\n";
            
            // Verify the update
            $verifyQuery = "SELECT password FROM users WHERE id = ?";
            $verifyStmt = $conn->prepare($verifyQuery);
            $verifyStmt->bind_param("i", $user['id']);
            $verifyStmt->execute();
            $verifyResult = $verifyStmt->get_result();
            $verifyUser = $verifyResult->fetch_assoc();
            
            $newVerify = password_verify($testPassword, $verifyUser['password']);
            echo "Verification after update: " . ($newVerify ? "TRUE ✓" : "FALSE ✗") . "\n";
        } else {
            echo "✗ Failed to update password: " . $conn->error . "\n";
        }
    }
} else {
    echo "✗ User not found in database!\n";
}

$conn->close();
?>
