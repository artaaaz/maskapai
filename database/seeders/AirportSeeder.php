<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airport;

class AirportSeeder extends Seeder
{
    public function run(): void
    {
        $airports = [
            ['name' => 'Soekarno-Hatta', 'city' => 'Jakarta', 'country' => 'Indonesia', 'iata_code' => 'CGK'],
            ['name' => 'Ngurah Rai', 'city' => 'Bali', 'country' => 'Indonesia', 'iata_code' => 'DPS'],
            ['name' => 'Juanda', 'city' => 'Surabaya', 'country' => 'Indonesia', 'iata_code' => 'SUB'],
            ['name' => 'Kualanamu', 'city' => 'Medan', 'country' => 'Indonesia', 'iata_code' => 'KNO'],
        ];

        foreach ($airports as $airport) {
            Airport::updateOrCreate(
                ['iata_code' => $airport['iata_code']], // Cari berdasarkan kode IATA
                $airport
            );
        }
    }
}
