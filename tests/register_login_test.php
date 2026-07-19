<?php
// Automated registration + login test for local dev server
$base = 'http://localhost:8000';
$registerUrl = $base . '/api/register';
$loginUrl = $base . '/api/login';
$dashboardUrl = $base . '/dashboard';

// Unique test user
$uniq = time();
$email = "test_user_{$uniq}@example.com";
$password = 'TestPass123!';
$name = 'Test User ' . $uniq;

function curl_post($url, $data, $cookieFile) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    $resp = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    return ['info'=>$info, 'raw'=>$resp];
}

function curl_get($url, $cookieFile) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    $resp = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    return ['info'=>$info, 'raw'=>$resp];
}

echo "Registering user: $email\n";
$cookieFile = __DIR__ . '/cookie_{$uniq}.txt';
$regData = [
    'name'=>$name,
    'email'=>$email,
    'password'=>$password,
    'confirm_password'=>$password
];

$reg = curl_post($registerUrl, $regData, $cookieFile);
$status = $reg['info']['http_code'] ?? 0;
$body = $reg['raw'];
$body_only = preg_replace("/^.*?\r\n\r\n/s", '', $body);

echo "POST $registerUrl -> HTTP $status\n";
echo "Response body:\n" . $body_only . "\n\n";

if ($status >= 200 && $status < 300) {
    echo "Registration appears successful. Proceeding to login...\n";
} else {
    echo "Registration failed (HTTP $status). Aborting test.\n";
    exit(1);
}

// Login
echo "Logging in user: $email\n";
$loginData = ['email'=>$email, 'password'=>$password];
$log = curl_post($loginUrl, $loginData, $cookieFile);
$logStatus = $log['info']['http_code'] ?? 0;
$logBody = preg_replace("/^.*?\r\n\r\n/s", '', $log['raw']);

echo "POST $loginUrl -> HTTP $logStatus\n";
echo "Response body:\n" . $logBody . "\n\n";

if (!($logStatus >= 200 && $logStatus < 300)) {
    echo "Login failed (HTTP $logStatus). Aborting test.\n";
    exit(1);
}

// Try to GET dashboard using same cookie
echo "Requesting dashboard page...\n";
$dash = curl_get($dashboardUrl, $cookieFile);
$dashStatus = $dash['info']['http_code'] ?? 0;
$dashBody = preg_replace("/^.*?\r\n\r\n/s", '', $dash['raw']);

echo "GET $dashboardUrl -> HTTP $dashStatus\n";
// Print a small excerpt
$excerpt = substr(strip_tags($dashBody), 0, 400);
echo "Dashboard excerpt:\n" . $excerpt . "\n\n";

if ($dashStatus == 200) {
    echo "Dashboard loaded successfully; login session persisted. TEST PASSED.\n";
    exit(0);
} else {
    echo "Dashboard did not load (HTTP $dashStatus). TEST FAILED.\n";
    exit(1);
}
