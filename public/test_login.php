<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Test the login function directly
require_once 'config/database.php';
require_once 'config/helpers.php';
require_once 'config/autoload.php';

echo "Testing database connection and login...<br>";

try {
    $db = new Database();
    echo "✓ Database connected<br>";
    
    // Test user query
    $sql = "SELECT * FROM users WHERE email = 'admin@calamba.gov.ph'";
    $result = $db->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo "✓ User found: " . $user['name'] . "<br>";
        echo "  Email: " . $user['email'] . "<br>";
        echo "  Role: " . $user['role'] . "<br>";
        echo "  Status: " . ($user['status'] ?? 'N/A') . "<br>";
        
        // Test password
        $test_password = "password";
        $password_hash = $user['password_hash'];
        
        if (password_verify($test_password, $password_hash)) {
            echo "✓ Password verification successful<br>";
        } else {
            echo "✗ Password verification failed<br>";
            echo "  Expected hash: " . $password_hash . "<br>";
        }
    } else {
        echo "✗ User not found<br>";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}
?>
