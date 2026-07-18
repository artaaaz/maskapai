<?php

namespace App\Services;

use App\Models\Airplane;
use App\Models\Seat;

/**
 * SeatLayoutService
 *
 * Root cause (BUG 2): kursi (tabel `seats`) hanya pernah dibuat lewat method privat
 * AirplaneSeeder::generateSeats(). Alur produksi (Admin > Tambah Pesawat, via
 * AirplaneController@store) TIDAK PERNAH memanggil logic ini, jadi setiap pesawat
 * yang dibuat lewat aplikasi (bukan seeder) punya 0 baris di tabel `seats`.
 * Akibatnya SeatSelectionController selalu mendapat collection kosong dan
 * menampilkan "Tidak ada kursi tersedia" walau seat_quota di flight_classes > 0.
 *
 * Service ini dipakai di dua tempat:
 *  1. Saat Admin membuat/mengubah Airplane -> generate layout awal.
 *  2. Sebagai self-healing lazy-fix saat Customer membuka halaman pilih kursi -> jika
 *     kursi untuk airplane/class tsb belum cukup (termasuk utk data pesawat LAMA yang
 *     sudah ada sebelum perbaikan ini), kursi baru otomatis dibuat sampai jumlahnya
 *     mencukupi seat_quota. Tidak ada perubahan skema tabel.
 */
class SeatLayoutService
{
    /**
     * Pastikan sebuah Airplane memiliki minimal $minCount kursi untuk $class tertentu.
     * Aman dipanggil berkali-kali (idempotent) - tidak akan membuat duplikat seat_number.
     */
    public static function ensureSeatsForClass(Airplane $airplane, string $class, int $minCount): void
    {
        if ($minCount <= 0) {
            return;
        }

        $existingForClass = Seat::where('airplane_id', $airplane->id)
            ->whereRaw('LOWER(`class`) = ?', [strtolower($class)])
            ->count();

        if ($existingForClass >= $minCount) {
            return;
        }

        // Kalau pesawat ini belum punya kursi sama sekali, generate layout dasar
        // dulu berdasarkan capacity (meniru logic AirplaneSeeder yang sudah ada).
        $hasAnySeat = Seat::where('airplane_id', $airplane->id)->exists();
        if (!$hasAnySeat) {
            self::generateBaseLayout($airplane);
            $existingForClass = Seat::where('airplane_id', $airplane->id)
                ->whereRaw('LOWER(`class`) = ?', [strtolower($class)])
                ->count();
            if ($existingForClass >= $minCount) {
                return;
            }
        }

        // Top-up: layout dasar berbasis capacity bisa saja mengalokasikan kursi
        // economy lebih sedikit dari seat_quota yang diisi Admin (mis. capacity kecil
        // tapi quota economy 100). Supaya "quota 100 -> minimal 100 seat dipilih"
        // selalu terpenuhi, tambahkan baris kursi baru khusus class ini.
        $needed = $minCount - $existingForClass;
        self::appendSeats($airplane, $class, $needed);
    }

    /**
     * Generate layout dasar kursi berdasarkan capacity pesawat.
     * Logic ini diadaptasi dari AirplaneSeeder::generateSeats() supaya konsisten
     * dengan data yang sudah pernah di-seed, hanya dipindah ke tempat yang bisa
     * dipakai ulang oleh controller (Admin & Customer), bukan cuma seeder.
     */
    public static function generateBaseLayout(Airplane $airplane): void
    {
        // Idempotent: kalau sudah ada kursi, jangan generate ulang (hindari duplikat).
        if (Seat::where('airplane_id', $airplane->id)->exists()) {
            return;
        }

        $capacity = max(1, (int) $airplane->capacity);

        if ($capacity >= 400) {
            $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
        } elseif ($capacity >= 280) {
            $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        } elseif ($capacity >= 140) {
            $letters = ['A', 'B', 'C', 'D', 'E', 'F'];
        } else {
            $letters = ['A', 'B', 'C', 'D'];
        }

        $perRow = count($letters);
        $numRows = (int) ceil($capacity / $perRow);

        $firstRows = ($capacity >= 400) ? 8 : 0;
        $businessRows = ($capacity >= 280) ? 8 : 0;
        $premiumRows = ($capacity >= 280) ? 4 : 0;

        $seatCount = 0;
        for ($row = 1; $row <= $numRows; $row++) {
            foreach ($letters as $letter) {
                $seatCount++;
                if ($seatCount > $capacity) {
                    break;
                }

                $seatClass = 'economy';
                if ($row <= $firstRows) {
                    $seatClass = 'first';
                } elseif ($row <= $firstRows + $businessRows) {
                    $seatClass = 'business';
                } elseif ($row <= $firstRows + $businessRows + $premiumRows) {
                    $seatClass = 'premium_economy';
                }

                self::createSeat($airplane->id, $row . $letter, $seatClass);
            }
        }
    }

    /**
     * Tambahkan $count kursi baru untuk $class pada airplane, melanjutkan nomor
     * baris dari kursi tertinggi yang sudah ada supaya tidak bentrok dengan
     * seat_number yang sudah dipakai (kolom seat_number unik per airplane_id).
     */
    protected static function appendSeats(Airplane $airplane, string $class, int $count): void
    {
        $letters = ['A', 'B', 'C', 'D', 'E', 'F'];

        // Cari nomor baris tertinggi yang sudah dipakai. Diambil dengan PHP (bukan
        // fungsi SQL vendor-specific seperti REGEXP_REPLACE) supaya tetap jalan di
        // MySQL (production) maupun SQLite (testing, sesuai phpunit.xml).
        $lastRow = 0;
        $existingSeatNumbers = Seat::where('airplane_id', $airplane->id)->pluck('seat_number');
        foreach ($existingSeatNumbers as $seatNumber) {
            preg_match('/^(\d+)/', $seatNumber, $matches);
            $rowNum = isset($matches[1]) ? (int) $matches[1] : 0;
            if ($rowNum > $lastRow) {
                $lastRow = $rowNum;
            }
        }

        $row = $lastRow + 1;
        $created = 0;

        while ($created < $count) {
            foreach ($letters as $letter) {
                if ($created >= $count) {
                    break;
                }

                $seatNumber = $row . $letter;

                $exists = Seat::where('airplane_id', $airplane->id)
                    ->where('seat_number', $seatNumber)
                    ->exists();

                if (!$exists) {
                    self::createSeat($airplane->id, $seatNumber, $class);
                    $created++;
                }
            }
            $row++;
        }
    }

    /**
     * Insert satu baris Seat. Defensif terhadap kolom opsional (position,
     * booking_id) yang mungkin ada/tidak ada di skema aktual - tidak mengubah
     * struktur tabel, hanya menghindari error "Unknown column" di server yang
     * skemanya berbeda dari migration bawaan repo ini.
     */
    protected static function createSeat(int $airplaneId, string $seatNumber, string $class): void
    {
        $data = [
            'airplane_id' => $airplaneId,
            'seat_number' => $seatNumber,
            'class' => $class,
            'status' => 'available',
        ];

        if (\Illuminate\Support\Facades\Schema::hasColumn('seats', 'position')) {
            $idx = array_search($seatNumber[strlen($seatNumber) - 1], ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I']);
            $data['position'] = ($idx === 0) ? 'window' : 'aisle';
        }

        Seat::firstOrCreate(
            ['airplane_id' => $airplaneId, 'seat_number' => $seatNumber],
            $data
        );
    }
}
