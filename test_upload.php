<?php
// Test uploading CSV directly using PHP with session

session_start();

// First, check if we have a valid session
if (!isset($_SESSION['user_id'])) {
    echo "No valid session. Testing as unauthenticated.\n";
}

$uploadUrl = 'http://localhost:8080/api/data-import/upload';
$csvFile = __DIR__ . '/test_data.csv';

echo "Test CSV file: $csvFile\n";
echo "File exists: " . (file_exists($csvFile) ? 'YES' : 'NO') . "\n";
echo "File size: " . filesize($csvFile) . " bytes\n";
echo "File contents:\n";
echo file_get_contents($csvFile);
echo "\n\n";

// Create a new cURL resource
$ch = curl_init();

// Set up the multipart form data
$postData = array(
    'barangay_id' => '1',
    'excel_file' => new CURLFile($csvFile, 'text/csv', 'test_data.csv')
);

curl_setopt_array($ch, array(
    CURLOPT_URL => $uploadUrl,
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => $postData,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
        'User-Agent: PHP-Curl-Test'
    ),
    CURLOPT_FOLLOWLOCATION => true
));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

curl_close($ch);

echo "HTTP Code: $httpCode\n";
if ($curlError) {
    echo "Curl Error: $curlError\n";
}
echo "Response:\n";
echo $response . "\n";

// Also try to parse the JSON response
$json = json_decode($response, true);
if ($json) {
    echo "\nJSON Response:\n";
    if (isset($json['success'])) {
        echo "Success: " . ($json['success'] ? 'YES' : 'NO') . "\n";
    }
    if (isset($json['message'])) {
        echo "Message: " . $json['message'] . "\n";
    }
    if (isset($json['error'])) {
        echo "Error: " . $json['error'] . "\n";
    }
    if (isset($json['errors'])) {
        echo "Detailed Errors:\n";
        foreach (array_slice($json['errors'], 0, 5) as $error) {
            echo "  - $error\n";
        }
        if (count($json['errors']) > 5) {
            echo "  ... and " . (count($json['errors']) - 5) . " more\n";
        }
    }
}
