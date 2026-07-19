<?php
// Test direct execution from public directory
echo "✅ PHP is executing correctly\n";
echo "Working directory: " . getcwd() . "\n";
echo "Script directory: " . __DIR__ . "\n";

// Test path calculation
define('BASE_PATH', dirname(__DIR__));
echo "BASE_PATH: " . BASE_PATH . "\n";

// Test config files exist
$configPath = BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'helpers.php';
echo "Config path: $configPath\n";
echo "Config exists: " . (file_exists($configPath) ? 'YES' : 'NO') . "\n";

// Try to load helpers
require_once $configPath;

echo "\n✅ Helpers loaded\n";

// Test a helper function
echo "Testing base_path(): " . base_path() . "\n";
echo "Testing env(): " . env('DB_DATABASE', 'not-found') . "\n";
?>
