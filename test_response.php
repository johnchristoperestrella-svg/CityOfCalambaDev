<?php
/**
 * Application HTTP Response Test
 */

echo "Testing HTTP response from http://localhost:8000/\n\n";

$url = "http://localhost:8000/";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "Status: HTTP $http_code\n";

if (!empty($error)) {
    echo "cURL Error: $error\n";
    exit(1);
}

if ($http_code === 200 || $http_code === '200') {
    echo "✅ Application is responding!\n";
    echo "Response size: " . strlen($response) . " bytes\n";
    
    if (!empty($response)) {
        echo "\n📄 Response preview (first 300 chars):\n";
        echo substr($response, 0, 300) . "\n";
        
        // Check for common error indicators
        if (strpos($response, 'Failed to open stream') !== false) {
            echo "\n❌ File loading error detected in response!\n";
        } elseif (strpos($response, 'Fatal error') !== false) {
            echo "\n❌ Fatal PHP error detected!\n";
        } elseif (strpos($response, 'Parse error') !== false) {
            echo "\n❌ Parse error detected!\n";
        } else {
            echo "\n✅ No error messages detected!\n";
        }
    }
} else {
    echo "❌ HTTP Error: $http_code\n";
    echo "Response:\n$response\n";
}
?>
