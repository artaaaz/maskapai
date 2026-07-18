<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Flight;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Airplane;
use Carbon\Carbon;

class FlightSeeder extends Seeder
{
    public function run(): void
    {
        $airlines = Airline::all();
        $airports = Airport::all();
        $airplanes = Airplane::all();

        if ($airlines->isEmpty() || $airports->isEmpty() || $airplanes->isEmpty()) {
            $this->command->warn('Skipping FlightSeeder: missing airlines, airports, or airplanes.');
            return;
        }

        // Define popular routes with realistic prices and durations
        $routes = [
            ['from' => 'CGK', 'to' => 'SUB', 'min_price' => 350000, 'max_price' => 850000, 'duration' => 90],
            ['from' => 'CGK', 'to' => 'DPS', 'min_price' => 500000, 'max_price' => 1200000, 'duration' => 120],
            ['from' => 'CGK', 'to' => 'UPG', 'min_price' => 600000, 'max_price' => 1500000, 'duration' => 150],
            ['from' => 'CGK', 'to' => 'KNO', 'min_price' => 450000, 'max_price' => 1100000, 'duration' => 135],
            ['from' => 'CGK', 'to' => 'BPN', 'min_price' => 550000, 'max_price' => 1300000, 'duration' => 140],
            ['from' => 'CGK', 'to' => 'BDO', 'min_price' => 250000, 'max_price' => 500000, 'duration' => 45],
            ['from' => 'CGK', 'to' => 'YIA', 'min_price' => 300000, 'max_price' => 600000, 'duration' => 60],
            ['from' => 'CGK', 'to' => 'PLM', 'min_price' => 350000, 'max_price' => 700000, 'duration' => 60],
            ['from' => 'CGK', 'to' => 'PKU', 'min_price' => 400000, 'max_price' => 800000, 'duration' => 90],
            ['from' => 'CGK', 'to' => 'LOP', 'min_price' => 500000, 'max_price' => 1100000, 'duration' => 120],
            ['from' => 'CGK', 'to' => 'MDC', 'min_price' => 700000, 'max_price' => 1600000, 'duration' => 180],
            ['from' => 'CGK', 'to' => 'BTH', 'min_price' => 350000, 'max_price' => 700000, 'duration' => 75],
            ['from' => 'CGK', 'to' => 'PDG', 'min_price' => 400000, 'max_price' => 800000, 'duration' => 90],
            ['from' => 'CGK', 'to' => 'BDJ', 'min_price' => 500000, 'max_price' => 1100000, 'duration' => 120],
            ['from' => 'SUB', 'to' => 'DPS', 'min_price' => 250000, 'max_price' => 500000, 'duration' => 45],
            ['from' => 'SUB', 'to' => 'UPG', 'min_price' => 400000, 'max_price' => 900000, 'duration' => 100],
            ['from' => 'SUB', 'to' => 'BPN', 'min_price' => 400000, 'max_price' => 900000, 'duration' => 100],
            ['from' => 'DPS', 'to' => 'UPG', 'min_price' => 350000, 'max_price' => 800000, 'duration' => 80],
            ['from' => 'DPS', 'to' => 'LOP', 'min_price' => 200000, 'max_price' => 400000, 'duration' => 35],
            ['from' => 'KNO', 'to' => 'BTH', 'min_price' => 300000, 'max_price' => 600000, 'duration' => 60],
            ['from' => 'KNO', 'to' => 'PKU', 'min_price' => 250000, 'max_price' => 500000, 'duration' => 50],
            ['from' => 'UPG', 'to' => 'MDC', 'min_price' => 300000, 'max_price' => 700000, 'duration' => 70],
            ['from' => 'UPG', 'to' => 'BPN', 'min_price' => 300000, 'max_price' => 700000, 'duration' => 70],
            ['from' => 'YIA', 'to' => 'DPS', 'min_price' => 400000, 'max_price' => 900000, 'duration' => 100],
            ['from' => 'YIA', 'to' => 'SUB', 'min_price' => 300000, 'max_price' => 600000, 'duration' => 60],
            ['from' => 'BDO', 'to' => 'DPS', 'min_price' => 450000, 'max_price' => 1000000, 'duration' => 110],
        ];

        $flightNumber = 1;
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->addDays(30);

        // Only use 5 major airlines for flights
        $activeAirlines = $airlines->take(5);

        foreach ($routes as $route) {
            $departureAirport = $airports->firstWhere('iata_code', $route['from']);
            $arrivalAirport = $airports->firstWhere('iata_code', $route['to']);
            
            if (!$departureAirport || !$arrivalAirport) continue;

            // Pick 2-3 random airlines per route
            $routeAirlineCount = rand(2, 3);
            $routeAirlines = $activeAirlines->random($routeAirlineCount);

            foreach ($routeAirlines as $airline) {
                $airlineAirplanes = $airplanes->where('airline_id', $airline->id);
                if ($airlineAirplanes->isEmpty()) continue;

                // Generate 1-3 flights daily for this route/airline
                $flightsPerDay = rand(1, 3);
                $currentDate = clone $startDate;

                while ($currentDate <= $endDate) {
                    for ($i = 0; $i < $flightsPerDay; $i++) {
                        $airplane = $airlineAirplanes->random();
                        
                        $hour = rand(5, 20);
                        $minute = rand(0, 3) * 15;
                        
                        $departureTime = (clone $currentDate)->setHour($hour)->setMinute($minute)->setSecond(0);
                        $arrivalTime = (clone $departureTime)->addMinutes($route['duration']);
                        
                        $price = rand($route['min_price'], $route['max_price']);
                        $price = round($price / 50000) * 50000;

                        $flight = Flight::create([
                            'airline_id' => $airline->id,
                            'airplane_id' => $airplane->id,
                            'departure_airport_id' => $departureAirport->id,
                            'arrival_airport_id' => $arrivalAirport->id,
                            'flight_number' => $airline->code . rand(100, 999),
                            'departure_time' => $departureTime,
                            'arrival_time' => $arrivalTime,
                            'price' => $price,
                            'available_seats' => $airplane->seats()->count(),
                            'duration_minutes' => $route['duration'],
                            'baggage_allowance_kg' => rand(20, 30),
                            'refund_policy' => 'Dapat di-refund dengan biaya administrasi 10%',
                        ]);

                        // Auto-create Economy class for this flight
                        $flight->flightClasses()->create([
                            'class_name' => 'economy',
                            'price' => $price,
                            'seat_quota' => $airplane->seats()->count(),
                        ]);

                        $flightNumber++;
                    }
                    
                    $currentDate->addDay();
                }
            }
        }

        $this->command->info("Generated {$flightNumber} flights with Economy classes.");
    }
}
