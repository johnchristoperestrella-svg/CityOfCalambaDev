<?php
// Use saved cookie to request dashboard
$ch = curl_init('http://localhost/dashboard');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookiejar.txt');
$res = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "HTTP_CODE: $http_code\n";
echo substr($res,0,500);
?>
