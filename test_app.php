<?php
/**
 * Test Application Connectivity
 */

echo "Testing application connectivity...\n\n";

// Try to fetch the login page
$url = "http://localhost:8000/";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200 && !empty($response)) {
    echo "✅ Application is responding\n";
    echo "   HTTP Status: $http_code\n";
    echo "   Response Length: " . strlen($response) . " bytes\n";
    
    if (strpos($response, 'login') !== false || strpos($response, 'email') !== false) {
        echo "   ✅ Login page detected\n";
    }
} else {
    echo "❌ Application error\n";
    echo "   HTTP Status: $http_code\n";
    if (!empty($response)) {
        echo "\nResponse (first 500 chars):\n";
        echo substr($response, 0, 500) . "\n";
    }
}
?>
