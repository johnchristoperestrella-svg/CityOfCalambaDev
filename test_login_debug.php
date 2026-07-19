<?php
// Test login mechanism
echo "1. Checking admin user:\n";
require 'config/database.php';
require 'config/helpers.php';

echo "=== Testing Login Mechanism ===\n\n";

// Instantiate Database wrapper
$db = new Database();

// Check admin user
echo "1. Checking admin user:\n";
$result = $db->query("SELECT id, email, password, role, status FROM users WHERE email = 'admin@calamba.gov.ph'");
$admin = $result->fetch_assoc();
if ($admin) {
    echo "   Found admin user:\n";
    echo "   ID: " . $admin['id'] . "\n";
    echo "   Email: " . $admin['email'] . "\n";
    echo "   Password Hash: " . substr($admin['password'], 0, 20) . "...\n";
    echo "   Role: " . $admin['role'] . "\n";
    echo "   Status: " . $admin['status'] . "\n";
    
    // Test password verification
    echo "\n2. Testing password verification:\n";
    $testPassword = "DefaultPass@123";
    $verify = password_verify($testPassword, $admin['password']);
    echo "   password_verify('DefaultPass@123', hash) = " . ($verify ? "TRUE" : "FALSE") . "\n";
    
    // Test what password_hash creates
    echo "\n3. Testing password_hash creation:\n";
    $newHash = password_hash($testPassword, PASSWORD_BCRYPT, ['cost' => 10]);
    echo "   New hash: " . substr($newHash, 0, 20) . "...\n";
    echo "   Verify with new hash: " . (password_verify($testPassword, $newHash) ? "TRUE" : "FALSE") . "\n";
} else {
    echo "   Admin user NOT found!\n";
}

// Check all users
echo "\n4. All users in database:\n";
$allResult = $db->query("SELECT id, email, role, status FROM users");
while ($user = $allResult->fetch_assoc()) {
    echo "   - {$user['id']}: {$user['email']} ({$user['role']}, {$user['status']})\n";
}

// Check finaltest user
echo "\n5. Checking finaltest user:\n";
 $result = $db->query("SELECT id, email, password, role, status FROM users WHERE email = 'finaltest@calamba.gov.ph'");
 $finaltest = $result->fetch_assoc();
if ($finaltest) {
    echo "   Found finaltest user:\n";
    echo "   ID: " . $finaltest['id'] . "\n";
    echo "   Email: " . $finaltest['email'] . "\n";
    echo "   Password Hash: " . substr($finaltest['password'], 0, 20) . "...\n";
    echo "   Role: " . $finaltest['role'] . "\n";
    echo "   Status: " . $finaltest['status'] . "\n";
    
    echo "\n6. Testing finaltest password:\n";
    $testPassword = "FinalTest@2026";
    $verify = password_verify($testPassword, $finaltest['password']);
    echo "   password_verify('FinalTest@2026', hash) = " . ($verify ? "TRUE" : "FALSE") . "\n";
} else {
    echo "   Finaltest user NOT found!\n";
}
?>
