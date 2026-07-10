<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airline; 

class AirlineSeeder extends Seeder
{
    public function run(): void
    {
        $airlines = [
            ['name' => 'Garuda Indonesia', 'code' => 'GA', 'description' => 'Flag carrier of Indonesia'],
            ['name' => 'Lion Air', 'code' => 'JT', 'description' => 'Low-cost carrier group'],
        ];

        foreach ($airlines as $airline) {
            Airline::updateOrCreate(
                ['code' => $airline['code']],
                $airline
            );
        }
    }
}