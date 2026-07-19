<?php
$db = new mysqli('127.0.0.1', 'root', '', 'calamba_popdev');

echo "=== TESTING PASSWORD VERIFICATION ===\n";
$result = $db->query("SELECT password FROM users WHERE email = 'admin@calamba.gov.ph'");
if($row = $result->fetch_assoc()) {
    $passwordHash = $row['password'];
    echo "Password hash: " . substr($passwordHash, 0, 50) . "...\n\n";
    
    // Test password_verify with different passwords
    $testPasswords = ['password', 'DefaultPass@123', 'admin', 'Password@123', 'Admin@123', '12345678'];
    foreach($testPasswords as $testPass) {
        $verified = password_verify($testPass, $passwordHash);
        echo "Testing '$testPass': " . ($verified ? '✓ MATCH' : '✗ NO MATCH') . "\n";
    }
} else {
    echo "No admin user found\n";
}
?>
