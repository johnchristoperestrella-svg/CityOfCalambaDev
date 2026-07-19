<?php
/**
 * API Endpoint Testing Script
 * Tests all major application endpoints
 */

echo "╔════════════════════════════════════════════════════════════╗" . PHP_EOL;
echo "║     API ENDPOINT TESTS - CALAMBA POPDEV                   ║" . PHP_EOL;
echo "╚════════════════════════════════════════════════════════════╝" . PHP_EOL . PHP_EOL;

$baseUrl = "http://localhost:8080";
$endpoints = [
    ['GET', '/'],
    ['GET', '/login'],
    ['GET', '/register'],
    ['GET', '/dashboard'],
    ['GET', '/api/barangays'],
];

foreach ($endpoints as $test) {
    [$method, $path] = $test;
    $url = $baseUrl . $path;
    
    try {
        $context = stream_context_create([
            'http' => [
                'method' => $method,
                'timeout' => 5,
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        $headers = $http_response_header ?? [];
        $statusCode = isset($headers[0]) ? intval(substr($headers[0], 9, 3)) : 0;
        
        $status = ($statusCode >= 200 && $statusCode < 400) ? "✅" : "❌";
        echo "$status [$method] $path - Status: $statusCode" . PHP_EOL;
        
    } catch (Exception $e) {
        echo "❌ [$method] $path - Error: " . $e->getMessage() . PHP_EOL;
    }
}

echo PHP_EOL . "[AUTHENTICATION STATUS]" . PHP_EOL;

// Check if session functions work
session_start();
echo "✅ Session Support: " . (isset($_SESSION) ? "Enabled" : "Disabled") . PHP_EOL;

// Test password hashing
$testPassword = "test123";
$hashed = password_hash($testPassword, PASSWORD_BCRYPT);
$verified = password_verify($testPassword, $hashed);
echo ($verified ? "✅" : "❌") . " Password Hashing: " . ($verified ? "Working" : "Failed") . PHP_EOL;

echo PHP_EOL . "[ERROR LOGS]" . PHP_EOL;
echo "Last errors in PHP error log:" . PHP_EOL;

// Check recent errors
$errorLog = ini_get('error_log');
if ($errorLog && file_exists($errorLog)) {
    $lines = file($errorLog);
    $recent = array_slice($lines, -5);
    foreach ($recent as $line) {
        echo "  " . trim($line) . PHP_EOL;
    }
} else {
    echo "  (No error log configured or found)" . PHP_EOL;
}
