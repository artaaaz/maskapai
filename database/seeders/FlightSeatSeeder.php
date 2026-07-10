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
        // Get airports
        $cgk = Airport::where('iata_code', 'CGK')->first();
        $dps = Airport::where('iata_code', 'DPS')->first();
        $sub = Airport::where('iata_code', 'SUB')->first();
        
        if (!$cgk || !$dps || !$sub) {
            $this->command->error('Airports not found! Please seed airports first.');
            return;
        }
        
        // Get or create airlines
        $garuda = Airline::firstOrCreate(
            ['code' => 'GA'],
            ['name' => 'Garuda Indonesia', 'logo' => 'garuda.png']
        );
        
        $lion = Airline::firstOrCreate(
            ['code' => 'JT'],
            ['name' => 'Lion Air', 'logo' => 'lion.png']
        );
        
        $citilink = Airline::firstOrCreate(
            ['code' => 'QG'],
            ['name' => 'Citilink', 'logo' => 'citilink.png']
        );

        // Create airplanes
        $airplane1 = Airplane::firstOrCreate(
            ['registration_number' => 'PK-GIA'],
            ['airline_id' => $garuda->id, 'model' => 'Boeing 737-800', 'total_seats' => 160]
        );
        
        $airplane2 = Airplane::firstOrCreate(
            ['registration_number' => 'PK-LAI'],
            ['airline_id' => $lion->id, 'model' => 'Boeing 737-900ER', 'total_seats' => 180]
        );

        // Create flights for next 30 days
        $airlines = [
            ['airline' => $garuda, 'code' => 'GA', 'price' => 1200000, 'airplane' => $airplane1],
            ['airline' => $lion, 'code' => 'JT', 'price' => 900000, 'airplane' => $airplane2],
            ['airline' => $citilink, 'code' => 'QG', 'price' => 850000, 'airplane' => $airplane1],
        ];

        $routes = [
            ['from' => $cgk, 'to' => $dps, 'departure_times' => ['06:00', '09:00', '13:00', '16:00']],
            ['from' => $dps, 'to' => $cgk, 'departure_times' => ['07:00', '10:00', '14:00', '17:00']],
            ['from' => $cgk, 'to' => $sub, 'departure_times' => ['05:00', '08:00', '12:00', '15:00']],
            ['from' => $sub, 'to' => $cgk, 'departure_times' => ['06:30', '09:30', '13:30', '16:30']],
        ];

        $startDate = Carbon::today();
        $flightCount = 0;

        foreach ($airlines as $airlineData) {
            foreach ($routes as $route) {
                foreach ($route['departure_times'] as $index => $time) {
                    for ($day = 0; $day < 30; $day++) {
                        $departureDateTime = $startDate->copy()->addDays($day)->setTimeFromTimeString($time);
                        $arrivalDateTime = $departureDateTime->copy()->addHours(2);

                        $flight = Flight::create([
                            'flight_number' => $airlineData['code'] . (100 + $index),
                            'airline_id' => $airlineData['airline']->id,
                            'airplane_id' => $airlineData['airplane']->id,
                            'departure_airport_id' => $route['from']->id,
                            'arrival_airport_id' => $route['to']->id,
                            'departure_time' => $departureDateTime,
                            'arrival_time' => $arrivalDateTime,
                            'price' => $airlineData['price'],
                            'available_seats' => 160,
                            'duration' => 120,
                        ]);

                        // Create seats for this flight
                        $this->createSeats($flight);
                        $flightCount++;
                    }
                }
            }
        }

        $this->command->info("✅ Created {$flightCount} flights with seats!");
    }

    private function createSeats($flight)
    {
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
                    'flight_id' => $flight->id,
                    'seat_number' => $seat['number'],
                    'class' => 'economy',
                    'position' => $seat['position'],
                    'status' => 'available',
                ]);
            }
        }

        // Premium Economy: Rows 6-9
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
                    'flight_id' => $flight->id,
                    'seat_number' => $seat['number'],
                    'class' => 'premium_economy',
                    'position' => $seat['position'],
                    'status' => 'available',
                ]);
            }
        }

        // Business Class: Rows 3-5
        for ($row = 3; $row <= 5; $row++) {
            $seats = [
                ['number' => $row . 'A', 'position' => 'window'],
                ['number' => $row . 'C', 'position' => 'aisle'],
                ['number' => $row . 'D', 'position' => 'aisle'],
                ['number' => $row . 'F', 'position' => 'window'],
            ];

            foreach ($seats as $seat) {
                Seat::create([
                    'flight_id' => $flight->id,
                    'seat_number' => $seat['number'],
                    'class' => 'business',
                    'position' => $seat['position'],
                    'status' => 'available',
                ]);
            }
        }

        // First Class: Rows 1-2
        for ($row = 1; $row <= 2; $row++) {
            $seats = [
                ['number' => $row . 'A', 'position' => 'window'],
                ['number' => $row . 'F', 'position' => 'window'],
            ];

            foreach ($seats as $seat) {
                Seat::create([
                    'flight_id' => $flight->id,
                    'seat_number' => $seat['number'],
                    'class' => 'first',
                    'position' => $seat['position'],
                    'status' => 'available',
                ]);
            }
        }
    }
}