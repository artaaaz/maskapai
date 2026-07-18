<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airplane;
use App\Models\Seat;

class AirplaneSeeder extends Seeder
{
    public function run(): void
    {
        $airplanes = [
            // Garuda Indonesia (airline_id = 1)
            ['airline_id' => 1, 'model' => 'Boeing 777-300ER', 'registration_number' => 'PK-GIA', 'capacity' => 314],
            ['airline_id' => 1, 'model' => 'Airbus A330-900neo', 'registration_number' => 'PK-GII', 'capacity' => 286],
            ['airline_id' => 1, 'model' => 'Boeing 737-800NG', 'registration_number' => 'PK-GMH', 'capacity' => 162],
            
            // Lion Air (airline_id = 2)
            ['airline_id' => 2, 'model' => 'Boeing 737-900ER', 'registration_number' => 'PK-LHQ', 'capacity' => 215],
            ['airline_id' => 2, 'model' => 'Boeing 737-800', 'registration_number' => 'PK-LIJ', 'capacity' => 189],
            ['airline_id' => 2, 'model' => 'Airbus A330-300', 'registration_number' => 'PK-LEO', 'capacity' => 440],
            
            // Citilink (airline_id = 3)
            ['airline_id' => 3, 'model' => 'Airbus A320-200', 'registration_number' => 'PK-GTL', 'capacity' => 180],
            ['airline_id' => 3, 'model' => 'Airbus A320neo', 'registration_number' => 'PK-GTA', 'capacity' => 186],
            
            // Batik Air (airline_id = 4)
            ['airline_id' => 4, 'model' => 'Boeing 737-800', 'registration_number' => 'PK-BUK', 'capacity' => 162],
            ['airline_id' => 4, 'model' => 'Airbus A320-200', 'registration_number' => 'PK-BUD', 'capacity' => 180],
            
            // Super Air Jet (airline_id = 5)
            ['airline_id' => 5, 'model' => 'Airbus A320-200', 'registration_number' => 'PK-SAJ', 'capacity' => 180],
            ['airline_id' => 5, 'model' => 'Boeing 737-800', 'registration_number' => 'PK-SAI', 'capacity' => 189],
            
            // AirAsia Indonesia (airline_id = 6)
            ['airline_id' => 6, 'model' => 'Airbus A320-200', 'registration_number' => 'PQ-AXV', 'capacity' => 180],
            ['airline_id' => 6, 'model' => 'Airbus A320neo', 'registration_number' => 'PQ-AXB', 'capacity' => 186],
            
            // Pelita Air (airline_id = 7)
            ['airline_id' => 7, 'model' => 'Airbus A320-200', 'registration_number' => 'PK-PAW', 'capacity' => 180],
            
            // Sriwijaya Air (airline_id = 8)
            ['airline_id' => 8, 'model' => 'Boeing 737-500', 'registration_number' => 'PK-CMH', 'capacity' => 149],
            ['airline_id' => 8, 'model' => 'Boeing 737-300', 'registration_number' => 'PK-CKN', 'capacity' => 149],
            
            // NAM Air (airline_id = 9)
            ['airline_id' => 9, 'model' => 'Boeing 737-500', 'registration_number' => 'PK-NAQ', 'capacity' => 149],
            ['airline_id' => 9, 'model' => 'ATR 72-600', 'registration_number' => 'PK-NAN', 'capacity' => 78],
            
            // TransNusa (airline_id = 10)
            ['airline_id' => 10, 'model' => 'ATR 72-600', 'registration_number' => 'PK-TNB', 'capacity' => 78],
            ['airline_id' => 10, 'model' => 'Airbus A320-200', 'registration_number' => 'PK-TNA', 'capacity' => 180],
        ];

        foreach ($airplanes as $data) {
            $airplane = Airplane::create($data);
            $this->generateSeats($airplane);
        }
    }

    private function generateSeats(Airplane $airplane): void
    {
        $capacity = $airplane->capacity;
        $letters = ['A', 'B', 'C', 'D', 'E', 'F'];
        $seatRows = [];

        // Determine column count and class layout
        if ($capacity >= 400) {
            // Wide body (9-abreast: ABC-DEF-GHI)
            $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
            $perRow = 9;
            $numRows = (int)ceil($capacity / $perRow);
            $class = 'economy';
        } elseif ($capacity >= 280) {
            // Wide body (8-abreast: ABC-DEF-GH)
            $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
            $perRow = 8;
            $numRows = (int)ceil($capacity / $perRow);
            $class = 'economy';
        } elseif ($capacity >= 210) {
            // 6-abreast
            $perRow = 6;
            $numRows = (int)ceil($capacity / $perRow);
            $class = 'economy';
        } elseif ($capacity >= 180) {
            // 6-abreast
            $perRow = 6;
            $numRows = (int)ceil($capacity / $perRow);
            $class = 'economy';
        } elseif ($capacity >= 160) {
            $perRow = 6;
            $numRows = (int)ceil($capacity / $perRow);
            $class = 'economy';
        } elseif ($capacity >= 140) {
            $perRow = 6;
            $numRows = (int)ceil($capacity / $perRow);
            $class = 'economy';
        } else {
            // ATR 72: 4-abreast
            $letters = ['A', 'B', 'C', 'D'];
            $perRow = 4;
            $numRows = (int)ceil($capacity / $perRow);
            $class = 'economy';
        }

        // First class rows for wide body
        $firstRows = ($capacity >= 400) ? 8 : 0;
        $businessRows = ($capacity >= 280) ? 8 : 0;

        for ($row = 1; $row <= $numRows; $row++) {
            foreach ($letters as $letter) {
                $totalSeats = $row * count($letters);
                if ($totalSeats > $capacity) break;

                $seatClass = 'economy';
                if ($row <= $firstRows) {
                    $seatClass = 'first';
                } elseif ($row <= $firstRows + $businessRows) {
                    $seatClass = 'business';
                } elseif ($row <= $firstRows + $businessRows + 4 && $capacity >= 280) {
                    $seatClass = 'premium_economy';
                }

                $position = '';
                if (count($letters) <= 4) {
                    // 4-abreast: window, aisle, aisle, window
                    $position = ($letter === $letters[0] || $letter === $letters[count($letters)-1]) ? 'window' : 'aisle';
                } elseif (count($letters) <= 6) {
                    // 6-abreast: window, middle, aisle, aisle, middle, window
                    $idx = array_search($letter, $letters);
                    if ($idx === 0 || $idx === count($letters)-1) $position = 'window';
                    elseif ($idx === 1 || $idx === count($letters)-2) $position = 'middle';
                    else $position = 'aisle';
                } else {
                    // 8/9-abreast
                    $idx = array_search($letter, $letters);
                    if ($idx === 0 || $idx === count($letters)-1) $position = 'window';
                    elseif ($idx === 1 || $idx === count($letters)-2 || $idx === count($letters)-3) $position = 'middle';
                    else $position = 'aisle';
                }

                Seat::create([
                    'airplane_id' => $airplane->id,
                    'seat_number' => $row . $letter,
                    'class' => $seatClass,
                    'position' => $position,
                    'status' => 'available',
                ]);
            }
        }
    }
}