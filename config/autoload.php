<?php
/**
 * Autoloader Configuration
 * Loads both Composer packages and App namespace
 */

// Load Composer autoload if available
$composerAutoload = BASE_PATH . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
}

// PSR-4 Autoloader for App namespace
spl_autoload_register(function ($class) {
    // Only handle App namespace
    if (strpos($class, 'App\\') !== 0) {
        return;
    }

    // Remove App\ prefix
    $relative = substr($class, 4);
    
    // Convert namespace to file path
    $path = BASE_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relative) . '.php';
    
    if (file_exists($path)) {
        require_once $path;
        return true;
    }
    
    return false;
});
?>
