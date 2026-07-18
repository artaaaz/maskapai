<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class InstallSslCertificate extends Command
{
    protected $signature = 'ssl:install';
    protected $description = 'Download CA certificate bundle (cacert.pem) untuk koneksi HTTPS';

    public function handle(): int
    {
        $destPath = storage_path('app/cacert.pem');

        // Check if already exists
        if (file_exists($destPath) && filesize($destPath) > 1000) {
            $this->info('✓ cacert.pem sudah ada: ' . $destPath . ' (' . filesize($destPath) . ' bytes)');
            return Command::SUCCESS;
        }

        $this->info('Mengunduh cacert.pem dari https://curl.se/ca/cacert.pem ...');

        $ctx = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        $content = @file_get_contents('https://curl.se/ca/cacert.pem', false, $ctx);

        if ($content === false || strlen($content) < 1000) {
            $this->error('Gagal mengunduh cacert.pem. Periksa koneksi internet.');
            return Command::FAILURE;
        }

        file_put_contents($destPath, $content);
        $this->info('✓ Berhasil diunduh: ' . $destPath . ' (' . strlen($content) . ' bytes)');
        $this->warn('Jalankan: php artisan ssl:verify untuk verifikasi.');

        return Command::SUCCESS;
    }
}