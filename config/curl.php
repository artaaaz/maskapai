<?php

return [
    /*
    |--------------------------------------------------------------------------
    | cURL SSL Configuration
    |--------------------------------------------------------------------------
    |
    | Path ke file cacert.pem untuk verifikasi sertifikat SSL.
    | Digunakan oleh Midtrans, Guzzle, dan semua koneksi HTTPS.
    |
    | Sistem otomatis mendeteksi dari berbagai lokasi umum di Windows:
    | - storage/app/cacert.pem (prioritas utama)
    | - config/cacert.pem
    | - Laragon / XAMPP / WAMP bundled PHP
    |
    */
    'cacert_path' => null, // di-resolve otomatis oleh MidtransSslServiceProvider
    'use_system_ca' => true,
];