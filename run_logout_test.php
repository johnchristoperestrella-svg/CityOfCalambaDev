<?php
$ch = curl_init('http://localhost/api/logout');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE,'cookiejar.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR,'cookiejar.txt');
$res = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo 'LOGOUT_HTTP_CODE:'.$code."\n".substr($res,0,200);
?>
