<?php
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'helpers.php';

echo "Testing base_path function:\n";
$basePath = base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'login.php');
echo "Path: $basePath\n";
echo "File exists: " . (file_exists($basePath) ? 'YES' : 'NO') . "\n\n";

if (file_exists($basePath)) {
    echo "Loading view...\n";
    ob_start();
    require $basePath;
    $content = ob_get_clean();
    echo "Content length: " . strlen($content) . " bytes\n";
    echo "First 500 chars:\n";
    echo substr($content, 0, 500) . "\n";
}
?>
