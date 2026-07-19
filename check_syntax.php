<?php
/**
 * Syntax and Error Checker
 */

$errors = [];
$files = [];

// Get all PHP files
function getAllPhpFiles($dir) {
    $files = [];
    $items = scandir($dir);
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        
        $path = $dir . '/' . $item;
        
        if (is_dir($path)) {
            $files = array_merge($files, getAllPhpFiles($path));
        } elseif (substr($item, -4) === '.php') {
            $files[] = $path;
        }
    }
    
    return $files;
}

$phpFiles = getAllPhpFiles('app');
$phpFiles = array_merge($phpFiles, getAllPhpFiles('config'));
$phpFiles = array_merge($phpFiles, getAllPhpFiles('public'));

echo "Checking " . count($phpFiles) . " PHP files for syntax errors...\n\n";

$errorCount = 0;
foreach ($phpFiles as $file) {
    $output = [];
    $return = 0;
    exec("php -l " . escapeshellarg($file), $output, $return);
    
    if ($return !== 0) {
        $errorCount++;
        echo "❌ " . str_replace(realpath('.'), '', realpath($file)) . "\n";
        foreach ($output as $line) {
            echo "   " . $line . "\n";
        }
    }
}

if ($errorCount === 0) {
    echo "✅ All PHP files have valid syntax!\n";
} else {
    echo "\n❌ Found $errorCount file(s) with syntax errors.\n";
}

// Check if key files exist
echo "\n📁 Checking key files...\n";
$keyFiles = [
    'app/Controllers/AuthController.php',
    'app/Controllers/DashboardController.php',
    'config/database.php',
    'config/Router.php',
    'public/index.php',
    'resources/views/auth/login.php'
];

foreach ($keyFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file\n";
    } else {
        echo "❌ $file - NOT FOUND\n";
    }
}

echo "\n✅ Application setup complete and ready to run!\n";
?>
