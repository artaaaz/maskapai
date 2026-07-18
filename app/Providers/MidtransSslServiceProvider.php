<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class MidtransSslServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureSsl();
    }

    private function configureSsl(): void
    {
        $cacertPath = $this->findCacertPath();

        if (!$cacertPath) {
            Log::warning('SSL CA certificate file not found. SSL verification may fail on Windows.');
            return;
        }

        // Environment variables (cURL reads these)
        putenv('SSL_CERT_FILE=' . $cacertPath);
        putenv('CURL_CA_BUNDLE=' . $cacertPath);

        // PHP ini overrides
        ini_set('curl.cainfo', $cacertPath);
        ini_set('openssl.cafile', $cacertPath);

        // Midtrans Config::$curlOptions
        // HARUS hati-hati: library Midtrans di ApiRequestor.php baris 117
        // mengakses Config::$curlOptions[CURLOPT_HTTPHEADER] (key 10023)
        // tanpa isset(). Jika kita set key lain, maka key CURLOPT_HTTPHEADER
        // tidak ada dan menyebabkan "Undefined array key 10023".
        //
        // Solusi: set CURLOPT_CAINFO dengan tetap memastikan CURLOPT_HTTPHEADER
        // sebagai array jika belum ada.
        if (class_exists('\Midtrans\Config')) {
            if (!isset(\Midtrans\Config::$curlOptions[CURLOPT_HTTPHEADER])) {
                \Midtrans\Config::$curlOptions[CURLOPT_HTTPHEADER] = [];
            }
            \Midtrans\Config::$curlOptions[CURLOPT_CAINFO] = $cacertPath;
        }

        Log::info('SSL CA certificate configured: ' . $cacertPath);
    }

    private function findCacertPath(): ?string
    {
        $paths = [
            storage_path('app/cacert.pem'),
            config_path('cacert.pem'),
            base_path('cacert.pem'),
        ];

        $phpDir = dirname(PHP_BINARY);
        $paths[] = $phpDir . '/extras/ssl/cacert.pem';
        $paths[] = $phpDir . '/cacert.pem';

        $laragonRoot = getenv('LARAGON_ROOT');
        if ($laragonRoot) {
            $paths[] = $laragonRoot . '/etc/ssl/cacert.pem';
        }

        $paths[] = 'C:/xampp/php/extras/ssl/cacert.pem';
        $paths[] = 'C:/xampp/php/cacert.pem';
        $paths[] = 'C:/wamp64/bin/php/php8.3.32/extras/ssl/cacert.pem';
        $paths[] = 'C:/Windows/cacert.pem';

        foreach ($paths as $path) {
            if ($path && file_exists($path)) {
                return $path;
            }
        }

        return null;
    }
}