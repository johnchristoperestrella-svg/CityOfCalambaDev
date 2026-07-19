<?php
/**
 * Path Resolution Test
 */

echo "════════════════════════════════════════════════════════\n";
echo "   PATH RESOLUTION & FILE LOADING TEST\n";
echo "════════════════════════════════════════════════════════\n\n";

// Test 1: Check if we're in the public directory
echo "1️⃣  DIRECTORY CHECK\n";
$currentDir = __DIR__;
echo "   Current Dir: $currentDir\n";
echo "   Parent Dir: " . dirname(__DIR__) . "\n\n";

// Test 2: Test path separators
echo "2️⃣  PATH SEPARATOR TEST\n";
$testPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'helpers.php';
echo "   Path: $testPath\n";
echo "   Exists: " . (file_exists($testPath) ? '✅ YES' : '❌ NO') . "\n\n";

// Test 3: Test helpers loading
echo "3️⃣  HELPERS.PHP LOAD TEST\n";
try {
    $helpersPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'helpers.php';
    if (file_exists($helpersPath)) {
        require_once $helpersPath;
        echo "   ✅ Helpers loaded successfully\n";
        
        // Test helper functions
        echo "\n4️⃣  HELPER FUNCTIONS TEST\n";
        
        // Test base_path
        if (function_exists('base_path')) {
            $basePath = base_path();
            echo "   ✅ base_path() works: $basePath\n";
        } else {
            echo "   ❌ base_path() not defined\n";
        }
        
        // Test env
        if (function_exists('env')) {
            $dbHost = env('DB_HOST', 'not-found');
            echo "   ✅ env() works: DB_HOST=$dbHost\n";
        } else {
            echo "   ❌ env() not defined\n";
        }
    } else {
        echo "   ❌ Helpers file not found at: $helpersPath\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error loading helpers: " . $e->getMessage() . "\n";
}

// Test 4: Test database connection
echo "\n5️⃣  DATABASE CONNECTION TEST\n";
try {
    $db = new mysqli('127.0.0.1', 'root', '', 'calamba_popdev');
    if ($db->connect_error) {
        echo "   ❌ Connection error: " . $db->connect_error . "\n";
    } else {
        echo "   ✅ Database connected successfully\n";
        $result = $db->query("SHOW TABLES");
        $tableCount = $result->num_rows;
        echo "   ✅ Tables found: $tableCount\n";
        $db->close();
    }
} catch (Exception $e) {
    echo "   ❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n════════════════════════════════════════════════════════\n";
echo "✅ PATH RESOLUTION TEST COMPLETE\n";
echo "════════════════════════════════════════════════════════\n";
?>
