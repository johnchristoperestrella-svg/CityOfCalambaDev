<?php
// Debug script to test routing
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'helpers.php';
require_once BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.php';
require_once BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'Router.php';

session_start_custom();

echo "Debug Information:\n";
echo "==================\n\n";

// Test 1: Check REQUEST_URI
echo "1. REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "   REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n\n";

// Test 2: Parse URL
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
echo "2. Parsed PATH: $path\n\n";

// Test 3: Check route matching
$basePaths = ['/CityOfCalambaDev', '/CityOfCalambaDev/public'];
foreach ($basePaths as $basePath) {
    if (strpos($path, $basePath) === 0) {
        $newPath = substr($path, strlen($basePath));
        echo "3. Matched base path: $basePath\n";
        echo "   New path: $newPath\n";
        break;
    }
}

// Test 4: Try to instantiate router
try {
    $router = new Router();
    echo "4. Router instantiated: ✅\n\n";
    
    // Test 5: Try to dispatch
    echo "5. Attempting dispatch...\n";
    $result = $router->dispatch();
    
    if ($result) {
        echo "   Result length: " . strlen($result) . " bytes\n";
        echo "   First 200 chars:\n";
        echo substr($result, 0, 200) . "\n";
    } else {
        echo "   Result is NULL or empty\n";
    }
} catch (Exception $e) {
    echo "4. Error: " . $e->getMessage() . "\n";
}
?>
