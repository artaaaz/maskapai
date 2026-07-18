<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airline;

class AirlineSeeder extends Seeder
{
    public function run(): void
    {
        $airlines = [
            ['name' => 'Garuda Indonesia', 'code' => 'GA', 'description' => 'Flag carrier of Indonesia, full-service airline'],
            ['name' => 'Lion Air', 'code' => 'JT', 'description' => 'Largest low-cost airline in Indonesia'],
            ['name' => 'Citilink', 'code' => 'QG', 'description' => 'Low-cost subsidiary of Garuda Indonesia'],
            ['name' => 'Batik Air', 'code' => 'ID', 'description' => 'Full-service subsidiary of Lion Air Group'],
            ['name' => 'Super Air Jet', 'code' => 'IU', 'description' => 'Ultra low-cost carrier'],
            ['name' => 'AirAsia Indonesia', 'code' => 'QZ', 'description' => 'Low-cost carrier, part of AirAsia Group'],
            ['name' => 'Pelita Air', 'code' => 'IP', 'description' => 'Indonesian charter and scheduled airline'],
            ['name' => 'Sriwijaya Air', 'code' => 'SJ', 'description' => 'Indonesian low-cost airline'],
            ['name' => 'NAM Air', 'code' => 'IN', 'description' => 'Regional low-cost airline, subsidiary of Sriwijaya Air'],
            ['name' => 'TransNusa', 'code' => '8B', 'description' => 'Regional airline serving eastern Indonesia'],
        ];

        foreach ($airlines as $airline) {
            Airline::updateOrCreate(
                ['code' => $airline['code']],
                $airline
            );
        }
    }
}