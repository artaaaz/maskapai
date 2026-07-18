<?php
$cacert = __DIR__ . '/../storage/app/cacert.pem';
if (!file_exists($cacert)) {
    $cacert = __DIR__ . '/../config/cacert.pem';
}

echo "PHP Version: " . phpversion() . "\n";
echo "cacert.pem: " . ($cacert && file_exists($cacert) ? filesize($cacert) . ' bytes' : 'NOT FOUND') . "\n";
echo "curl.cainfo ini: " . (ini_get('curl.cainfo') ?: 'NOT SET') . "\n";
echo "openssl.cafile ini: " . (ini_get('openssl.cafile') ?: 'NOT SET') . "\n";
echo "SSL_CERT_FILE env: " . (getenv('SSL_CERT_FILE') ?: 'NOT SET') . "\n";

if (file_exists($cacert)) {
    echo "\n--- Test 1: cURL to Midtrans API ---\n";
    $ch = curl_init('https://api.midtrans.com/v2/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CAINFO, $cacert);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    echo "HTTP Code: " . $httpCode . "\n";
    echo "SSL Error: " . ($error ?: 'NONE') . "\n";
    echo "Status: " . ($httpCode > 0 ? 'SUCCESS' : 'FAILED') . "\n";

    echo "\n--- Test 2: cURL to Google (common SSL test) ---\n";
    $ch2 = curl_init('https://www.google.com');
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_CAINFO, $cacert);
    curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch2, CURLOPT_TIMEOUT, 10);
    $result2 = curl_exec($ch2);
    $httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
    $error2 = curl_error($ch2);
    curl_close($ch2);

    echo "HTTP Code: " . $httpCode2 . "\n";
    echo "SSL Error: " . ($error2 ?: 'NONE') . "\n";
    echo "Status: " . ($httpCode2 > 0 ? 'SUCCESS' : 'FAILED') . "\n";

    echo "\n--- Test 3: file_get_contents HTTPS ---\n";
    $ctx = stream_context_create(['ssl' => ['cafile' => $cacert]]);
    $result3 = @file_get_contents('https://www.google.com', false, $ctx);
    echo "Status: " . ($result3 !== false ? 'SUCCESS (' . strlen($result3) . ' bytes)' : 'FAILED') . "\n";
}

echo "\n--- Test Complete ---\n";