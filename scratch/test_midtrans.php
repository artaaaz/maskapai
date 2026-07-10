<?php
require __DIR__ . '/../vendor/autoload.php';

use Midtrans\Config;
use Midtrans\Snap;

echo "--- TESTING MIDTRANS SANDBOX KEY --- \n";

Config::$serverKey = 'SB-Mid-server-TCF0ReKjoweuDQeaNliTXs0M';
Config::$isProduction = false;
Config::$isSanitized = true;
Config::$is3ds = true;

try {
    echo "Trying Sandbox API with SB- key...\n";
    $token = Snap::getSnapToken([
        'transaction_details' => [
            'order_id' => 'TEST-SB-' . time(),
            'gross_amount' => 10000,
        ]
    ]);
    echo "SUCCESS! Token: $token\n\n";
} catch (\Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n\n";
}
