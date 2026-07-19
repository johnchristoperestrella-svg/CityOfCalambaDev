<?php
/**
 * Router for PHP Built-in Development Server
 * This file handles URL rewriting for the development server
 * It must route all non-static requests to index.php
 */

// Get the requested URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove leading slash for easier processing
$path = ltrim($uri, '/');

// List of static file directories that should be served directly
$staticDirs = ['css', 'js', 'images', 'documents', 'uploads'];

// Check if it's a real file that exists and should be served
$filePath = __DIR__ . DIRECTORY_SEPARATOR . $path;

// For static assets, serve them if they exist
$isStatic = false;
foreach ($staticDirs as $dir) {
    if (strpos($path, $dir . '/') === 0 || $path === $dir) {
        $isStatic = true;
        break;
    }
}

if ($isStatic && file_exists($filePath) && is_file($filePath)) {
    return false; // Let the server serve the static file
}

// For everything else (including /), route through index.php
// We need to set up the environment for index.php
$_SERVER['REQUEST_URI'] = $uri;

// If the request is directly to index.php, let it be served
if (basename($filePath) === 'index.php' && file_exists($filePath)) {
    return false;
}

// Otherwise, include index.php to handle the routing
require_once __DIR__ . '/index.php';

// Return true to indicate we've handled the request (prevent the server from serving 404)
return true;




