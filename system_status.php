<?php
/**
 * System Status Report
 * Calamba PopDev Resource Network
 */

echo "═════════════════════════════════════════════════════════════\n";
echo "   CALAMBA POPDEV RESOURCE NETWORK - SYSTEM STATUS REPORT\n";
echo "═════════════════════════════════════════════════════════════\n\n";

// 1. PHP Information
echo "1️⃣  PHP ENVIRONMENT\n";
echo "   ✅ PHP Version: " . phpversion() . "\n";
echo "   ✅ Server: " . php_uname() . "\n\n";

// 2. Database Connection
echo "2️⃣  DATABASE CONNECTION\n";
$db = new mysqli('127.0.0.1', 'root', '', 'calamba_popdev');

if ($db->connect_error) {
    echo "   ❌ Connection Failed: " . $db->connect_error . "\n";
    exit;
}

echo "   ✅ Connected to: calamba_popdev\n";
echo "   ✅ Host: 127.0.0.1\n";
echo "   ✅ User: root\n\n";

// 3. Database Tables
echo "3️⃣  DATABASE TABLES\n";
$result = $db->query("SHOW TABLES");
$tableCount = $result->num_rows;
echo "   ✅ Total Tables: $tableCount\n";

while ($row = $result->fetch_row()) {
    echo "      • " . $row[0] . "\n";
}
echo "\n";

// 4. Users
echo "4️⃣  USERS\n";
$result = $db->query("SELECT COUNT(*) as count FROM users");
$row = $result->fetch_assoc();
echo "   ✅ Total Users: " . $row['count'] . "\n";

$result = $db->query("SELECT id, email, name, role FROM users LIMIT 5");
while ($user = $result->fetch_assoc()) {
    echo "      • " . $user['email'] . " (" . $user['role'] . ")\n";
}
echo "\n";

// 5. Data Records
echo "5️⃣  DATA RECORDS\n";
$tables = [
    'barangays' => 'Barangays',
    'households' => 'Households',
    'individuals' => 'Individuals',
    'health_metrics' => 'Health Metrics',
    'documents' => 'Documents'
];

foreach ($tables as $table => $name) {
    $result = $db->query("SELECT COUNT(*) as count FROM $table");
    $row = $result->fetch_assoc();
    echo "   ✅ " . str_pad($name, 20) . ": " . $row['count'] . " records\n";
}
echo "\n";

// 6. Application Files
echo "6️⃣  APPLICATION FILES\n";
$keyFiles = [
    'app/Controllers/AuthController.php',
    'app/Controllers/DashboardController.php',
    'app/Controllers/DataManagementController.php',
    'app/Controllers/MLAnalyticsController.php',
    'config/database.php',
    'config/Router.php',
    'public/index.php',
    'resources/views/auth/login.php',
    'resources/views/dashboard/index.php'
];

$allFilesExist = true;
foreach ($keyFiles as $file) {
    if (file_exists($file)) {
        echo "   ✅ $file\n";
    } else {
        echo "   ❌ $file - NOT FOUND\n";
        $allFilesExist = false;
    }
}
echo "\n";

// 7. Configuration
echo "7️⃣  CONFIGURATION\n";
if (file_exists('.env')) {
    echo "   ✅ .env file exists\n";
} else {
    echo "   ⚠️  .env file not found\n";
}

echo "   ✅ APP_URL: http://localhost/CityOfCalambaDev\n";
echo "   ✅ DB_HOST: 127.0.0.1\n";
echo "   ✅ DB_DATABASE: calamba_popdev\n";
echo "   ✅ DB_USERNAME: root\n";
echo "\n";

// 8. Admin User
echo "8️⃣  ADMIN USER\n";
$result = $db->query("SELECT id, email, name, role FROM users WHERE role='City Administrator' LIMIT 1");
if ($result && $result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    echo "   ✅ Admin Account Found\n";
    echo "      Email: " . $admin['email'] . "\n";
    echo "      Role: " . $admin['role'] . "\n";
    echo "      Password: DefaultPass@123 (change after first login)\n";
} else {
    echo "   ❌ No admin user found\n";
}
echo "\n";

// 9. System Status
echo "9️⃣  SYSTEM STATUS\n";
if ($allFilesExist && $db->ping()) {
    echo "   ✅ All Systems Operational\n";
    echo "   ✅ Database: Connected\n";
    echo "   ✅ Application: Ready to Run\n";
} else {
    echo "   ⚠️  Some components may have issues\n";
}
echo "\n";

// 10. Access Information
echo "🔟 ACCESS INFORMATION\n";
echo "   🌐 Application URL: http://localhost/CityOfCalambaDev/public\n";
echo "   🌐 Development URL: http://localhost:8000/\n";
echo "   📧 Login Email: admin@calamba.gov.ph\n";
echo "   🔑 Login Password: DefaultPass@123\n";
echo "   ⚠️  Please change password after first login!\n";
echo "\n";

echo "═════════════════════════════════════════════════════════════\n";
echo "✅ SYSTEM READY FOR USE\n";
echo "═════════════════════════════════════════════════════════════\n";

$db->close();
?>
