<?php

namespace Database\Seeders;

use App\Models\Flight;
use App\Models\Seat;
use App\Models\Airport;
use App\Models\Airline;
use App\Models\Airplane;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FlightSeatSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🪑 Membuat kursi untuk setiap pesawat...');
        
        $airplanes = Airplane::all();

        if ($airplanes->isEmpty()) {
            $this->command->error('Tidak ada pesawat! Jalankan CompleteDataSeeder dulu.');
            return;
        }

        $seatCount = 0;

        foreach ($airplanes as $airplane) {
            // Cek apakah kursi sudah ada untuk pesawat ini
            if ($airplane->seats()->count() > 0) {
                $this->command->info("   ✈️ Pesawat {$airplane->registration_number} sudah memiliki kursi, skip.");
                continue;
            }

            $this->command->info("   ✈️ Membuat kursi untuk {$airplane->registration_number}...");
            $seatCount += $this->createSeatsForAirplane($airplane);
        }

        $this->command->info("✅ {$seatCount} kursi berhasil dibuat untuk " . $airplanes->count() . " pesawat!");
    }

    private function createSeatsForAirplane($airplane): int
    {
        $count = 0;

        // Economy Class: Rows 10-40 (A, B, C, D, E, F)
        for ($row = 10; $row <= 40; $row++) {
            $seats = [
                ['number' => $row . 'A', 'position' => 'window'],
                ['number' => $row . 'B', 'position' => 'middle'],
                ['number' => $row . 'C', 'position' => 'aisle'],
                ['number' => $row . 'D', 'position' => 'aisle'],
                ['number' => $row . 'E', 'position' => 'middle'],
                ['number' => $row . 'F', 'position' => 'window'],
            ];

            foreach ($seats as $seat) {
                Seat::create([
                    'airplane_id' => $airplane->id,
                    'seat_number' => $seat['number'],
                    'class' => 'economy',
                    'status' => 'available',
                ]);
                $count++;
            }
        }

        // Premium Economy: Rows 6-9 (A, B, C, D, E, F)
        for ($row = 6; $row <= 9; $row++) {
            $seats = [
                ['number' => $row . 'A', 'position' => 'window'],
                ['number' => $row . 'B', 'position' => 'middle'],
                ['number' => $row . 'C', 'position' => 'aisle'],
                ['number' => $row . 'D', 'position' => 'aisle'],
                ['number' => $row . 'E', 'position' => 'middle'],
                ['number' => $row . 'F', 'position' => 'window'],
            ];

            foreach ($seats as $seat) {
                Seat::create([
                    'airplane_id' => $airplane->id,
                    'seat_number' => $seat['number'],
                    'class' => 'premium_economy',
                    'status' => 'available',
                ]);
                $count++;
            }
        }

        // Business Class: Rows 3-5 (A, C, D, F)
        for ($row = 3; $row <= 5; $row++) {
            $seats = [
                ['number' => $row . 'A', 'position' => 'window'],
                ['number' => $row . 'C', 'position' => 'aisle'],
                ['number' => $row . 'D', 'position' => 'aisle'],
                ['number' => $row . 'F', 'position' => 'window'],
            ];

            foreach ($seats as $seat) {
                Seat::create([
                    'airplane_id' => $airplane->id,
                    'seat_number' => $seat['number'],
                    'class' => 'business',
                    'status' => 'available',
                ]);
                $count++;
            }
        }

        // First Class: Rows 1-2 (A, F)
        for ($row = 1; $row <= 2; $row++) {
            $seats = [
                ['number' => $row . 'A', 'position' => 'window'],
                ['number' => $row . 'F', 'position' => 'window'],
            ];

            foreach ($seats as $seat) {
                Seat::create([
                    'airplane_id' => $airplane->id,
                    'seat_number' => $seat['number'],
                    'class' => 'first',
                    'status' => 'available',
                ]);
                $count++;
            }
        }

        return $count;
    }
}
