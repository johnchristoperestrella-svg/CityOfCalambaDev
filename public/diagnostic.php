<?php
echo "╔════════════════════════════════════════════════════════════╗" . PHP_EOL;
echo "║     CALAMBA POPDEV RESOURCE NETWORK - DIAGNOSTICS         ║" . PHP_EOL;
echo "╚════════════════════════════════════════════════════════════╝" . PHP_EOL . PHP_EOL;

// Database Connection Test
echo "[1] DATABASE CONNECTION TEST" . PHP_EOL;
if ($conn = mysqli_connect('127.0.0.1', 'root', '', 'calamba_popdev')) {
    echo "✅ MySQL Connection: SUCCESS" . PHP_EOL;
    
    // Check Tables
    $result = $conn->query('SHOW TABLES');
    $tableCount = $result->num_rows;
    echo "✅ Database Tables: " . $tableCount . PHP_EOL;
    
    // Check Users
    $users = $conn->query('SELECT COUNT(*) as count FROM users');
    $row = $users->fetch_assoc();
    echo "✅ Total Users: " . $row['count'] . PHP_EOL;
    
    // Check Imports
    $imports = $conn->query('SELECT COUNT(*) as count FROM data_imports');
    $row = $imports->fetch_assoc();
    echo "✅ Data Imports: " . $row['count'] . PHP_EOL;
    
    // Check Households
    $households = $conn->query('SELECT COUNT(*) as count FROM households');
    $row = $households->fetch_assoc();
    echo "✅ Households: " . $row['count'] . PHP_EOL;
    
    $conn->close();
} else {
    echo "❌ Database Connection Failed" . PHP_EOL;
}

echo PHP_EOL . "[2] FILE STRUCTURE TEST" . PHP_EOL;
$requiredDirs = ['app/Controllers', 'app/Models', 'config', 'public', 'resources/views', 'routes'];
foreach ($requiredDirs as $dir) {
    echo (is_dir($dir) ? "✅" : "❌") . " " . $dir . PHP_EOL;
}

echo PHP_EOL . "[3] CONFIGURATION TEST" . PHP_EOL;
echo (file_exists('config/database.php') ? "✅" : "❌") . " Database Config" . PHP_EOL;
echo (file_exists('config/helpers.php') ? "✅" : "❌") . " Helpers" . PHP_EOL;
echo (file_exists('config/autoload.php') ? "✅" : "❌") . " Autoloader" . PHP_EOL;
echo (file_exists('public/router.php') ? "✅" : "❌") . " Router" . PHP_EOL;

echo PHP_EOL . "[4] PHP INFORMATION" . PHP_EOL;
echo "PHP Version: " . phpversion() . PHP_EOL;
echo "Loaded Extensions: " . (extension_loaded('mysqli') ? "✅ mysqli" : "❌ mysqli") . ", " . (extension_loaded('pdo') ? "✅ pdo" : "❌ pdo") . PHP_EOL;
