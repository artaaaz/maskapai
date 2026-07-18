<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('bookings:cancel-expired')
    ->everyFiveMinutes()
    ->withoutOverlapping();

// Sinkronisasi status operasional (no-show otomatis + sync booking.status
// mengikuti status penumpang). SEBELUMNYA dipanggil setiap kali staff membuka
// halaman dashboard (GET request) — dipindahkan ke scheduler agar status
// booking/passenger tidak berubah sendiri hanya karena ada yang membuka halaman.
Schedule::call(function () {
    app(\App\Services\OperationalService::class)->run();
})->everyFiveMinutes()
    ->name('operational-sync')
    ->withoutOverlapping();