<?php

use App\Models\Airplane;
use App\Models\Flight;
use App\Services\SeatLayoutService;
use Illuminate\Database\Migrations\Migration;

/**
 * DATA REPAIR MIGRATION - bukan perubahan struktur tabel.
 *
 * Ini adalah jaring pengaman satu-kali untuk data yang SUDAH TERLANJUR ada di
 * server (Antigravity) sebelum kode aplikasi diperbaiki:
 *
 *  - BUG 1: Flight lama yang tidak punya baris flight_classes (root cause Flight
 *    tidak tampil di Customer) langsung dibackfill di sini, jadi Customer tidak
 *    perlu menunggu flight tsb diakses dulu baru "sembuh" lewat
 *    Flight::ensureHasFlightClass().
 *  - BUG 2: Airplane lama yang belum punya kursi sama sekali (root cause pesan
 *    "Tidak ada kursi tersedia") langsung digenerate layout dasarnya di sini.
 *
 * Aman dijalankan berkali-kali (idempotent) - hanya menyentuh flight/airplane
 * yang datanya memang masih kosong.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1) Backfill flight_classes untuk Flight lama yang belum punya kelas.
        Flight::whereDoesntHave('flightClasses')->get()->each(function (Flight $flight) {
            $flight->flightClasses()->create([
                'class_name' => 'economy',
                'price' => $flight->price,
                'seat_quota' => $flight->available_seats,
            ]);
        });

        // 2) Generate layout kursi dasar untuk Airplane yang belum punya kursi.
        Airplane::doesntHave('seats')->get()->each(function (Airplane $airplane) {
            SeatLayoutService::generateBaseLayout($airplane);
        });

        // 3) Pastikan tiap flight_class quota-nya benar-benar tercukupi jumlah
        //    kursi fisiknya (mis. quota economy 100 tapi layout dasar hanya
        //    menghasilkan 90 kursi economy karena capacity pesawat kecil).
        Flight::with('flightClasses', 'airplane')->get()->each(function (Flight $flight) {
            if (!$flight->airplane) {
                return;
            }
            foreach ($flight->flightClasses as $flightClass) {
                SeatLayoutService::ensureSeatsForClass(
                    $flight->airplane,
                    $flightClass->class_name,
                    $flightClass->seat_quota
                );
            }
        });
    }

    public function down(): void
    {
        // Data repair tidak di-rollback - menghapusnya akan menghilangkan data
        // yang justru dibutuhkan Customer untuk melihat/booking Flight lama.
    }
};
