<?php
$ch = curl_init('http://localhost:8000/test.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "✅ SERVER RUNNING\n";
echo "HTTP Status: $code\n";
echo "Response:\n$response\n";
?>
