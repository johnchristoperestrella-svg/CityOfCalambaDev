<?php
// Test main index.php  
$ch = curl_init('http://localhost:8000/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "╔════════════════════════════════════════════════════╗\n";
echo "║   MAIN INDEX.PHP TEST\n";
echo "╚════════════════════════════════════════════════════╝\n\n";

echo "HTTP Status: $httpCode\n";
echo "Response Length: " . strlen($response) . " bytes\n";

if ($error) {
    echo "cURL Error: $error\n";
}

echo "\nResponse:\n";

if ($response) {
    // Show first 1000 chars
    echo substr($response, 0, 1000) . "\n";
    
    // Check for errors
    if (strpos($response, 'Failed to open stream') !== false) {
        echo "\n❌ ERROR: File loading failure detected!\n";
    } elseif (strpos($response, 'Fatal error') !== false) {
        echo "\n❌ ERROR: Fatal PHP error detected!\n";
    } elseif (strpos($response, 'Warning') !== false) {
        echo "\n⚠️  WARNING: Warnings detected in output\n";
    } else {
        echo "\n✅ No errors detected!\n";
    }
} else {
    echo "Empty response\n";
}
?>
