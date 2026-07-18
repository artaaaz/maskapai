<?php

use App\Models\Flight;
use App\Models\FlightClass;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Auto-create Economy FlightClass for all flights that don't have any flight classes
        $flights = Flight::whereDoesntHave('flightClasses')->get();
        
        foreach ($flights as $flight) {
            $flight->flightClasses()->create([
                'class_name' => 'economy',
                'price' => $flight->price,
                'seat_quota' => $flight->available_seats,
            ]);
        }
    }

    public function down(): void
    {
        // Remove auto-created economy classes that were created by this migration
        // We only remove classes where the flight has exactly one class (economy)
        $flights = Flight::whereHas('flightClasses', function ($q) {
            $q->where('class_name', 'economy');
        })->get();

        foreach ($flights as $flight) {
            if ($flight->flightClasses()->count() === 1) {
                $flight->flightClasses()->where('class_name', 'economy')->delete();
            }
        }
    }
};