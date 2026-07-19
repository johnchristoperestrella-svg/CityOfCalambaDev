<?php
// Test admin login with correct credentials
echo "=== Login Test ===\n\n";

$url = 'http://localhost:8080/api/login';
echo "URL: $url\n";
echo "Email: admin@calamba.gov.ph\n";
echo "Password: DefaultPass@123\n\n";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'email' => 'admin@calamba.gov.ph',
    'password' => 'DefaultPass@123'
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookiejar.txt');

echo "Sending request...\n";
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status: $http_code\n";
echo "Response:\n";
echo $response . "\n";

if ($http_code === 200) {
    echo "\n✓ Login successful!\n";
} else {
    echo "\n✗ Login failed!\n";
}
?>
