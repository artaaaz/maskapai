<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Airport;
use Illuminate\Http\Request;

class FlightSearchApiController extends Controller
{
    /**
     * GET /api/flights/search
     * 
     * Search flights by origin, destination, date and return JSON.
     */
    public function search(Request $request)
    {
        $request->validate([
            'departure_airport_id' => 'required|exists:airports,id',
            'arrival_airport_id' => 'required|exists:airports,id|different:departure_airport_id',
            'departure_date' => 'required|date',
            'trip_type' => 'nullable|in:one_way,round_trip',
            'return_date' => 'nullable|date|after:departure_date',
            'passenger_count' => 'nullable|integer|min:1|max:9',
            'travel_class' => 'nullable|string',
        ]);

        $passengerCount = max(1, (int) $request->passenger_count);
        $travelClass = $request->travel_class ?? 'economy';
        $multiplier = config('travel_class.' . $travelClass . '.multiplier', 1);

        $flights = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'airplane'])
            ->where('departure_airport_id', $request->departure_airport_id)
            ->where('arrival_airport_id', $request->arrival_airport_id)
            ->whereDate('departure_time', $request->departure_date)
            ->where('available_seats', '>=', $passengerCount)
            ->orderBy('departure_time')
            ->get()
            ->map(function ($flight) use ($multiplier, $passengerCount) {
                $dep = \Carbon\Carbon::parse($flight->departure_time);
                $arr = \Carbon\Carbon::parse($flight->arrival_time);
                $durationMinutes = $dep->diffInMinutes($arr);
                $hours = intdiv($durationMinutes, 60);
                $mins = $durationMinutes % 60;

                return [
                    'id' => $flight->id,
                    'flight_number' => $flight->flight_number,
                    'airline_name' => $flight->airline?->name ?? 'DRG Maskapai',
                    'airline_initial' => substr($flight->airline?->name ?? 'DRG', 0, 2),
                    'departure_iata' => $flight->departureAirport?->iata_code ?? '',
                    'departure_city' => $flight->departureAirport?->city ?? '',
                    'departure_time' => $dep->format('H:i'),
                    'departure_date' => $dep->format('d M Y'),
                    'arrival_iata' => $flight->arrivalAirport?->iata_code ?? '',
                    'arrival_city' => $flight->arrivalAirport?->city ?? '',
                    'arrival_time' => $arr->format('H:i'),
                    'arrival_date' => $arr->format('d M Y'),
                    'duration_minutes' => $durationMinutes,
                    'duration_formatted' => $hours . 'j ' . $mins . 'm',
                    'display_price' => (int) round($flight->price * $multiplier),
                    'original_price' => (int) $flight->price,
                    'available_seats' => $flight->available_seats,
                    'transit_count' => 0, // Direct flights
                    'is_direct' => true,
                ];
            });

        $returnFlights = collect();
        if ($request->trip_type === 'round_trip' && $request->return_date) {
            $returnFlights = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'airplane'])
                ->where('departure_airport_id', $request->arrival_airport_id)
                ->where('arrival_airport_id', $request->departure_airport_id)
                ->whereDate('departure_time', $request->return_date)
                ->where('available_seats', '>=', $passengerCount)
                ->orderBy('departure_time')
                ->get()
                ->map(function ($flight) use ($multiplier, $passengerCount) {
                    $dep = \Carbon\Carbon::parse($flight->departure_time);
                    $arr = \Carbon\Carbon::parse($flight->arrival_time);
                    $durationMinutes = $dep->diffInMinutes($arr);
                    $hours = intdiv($durationMinutes, 60);
                    $mins = $durationMinutes % 60;

                    return [
                        'id' => $flight->id,
                        'flight_number' => $flight->flight_number,
                        'airline_name' => $flight->airline?->name ?? 'DRG Maskapai',
                        'airline_initial' => substr($flight->airline?->name ?? 'DRG', 0, 2),
                        'departure_iata' => $flight->departureAirport?->iata_code ?? '',
                        'departure_city' => $flight->departureAirport?->city ?? '',
                        'departure_time' => $dep->format('H:i'),
                        'departure_date' => $dep->format('d M Y'),
                        'arrival_iata' => $flight->arrivalAirport?->iata_code ?? '',
                        'arrival_city' => $flight->arrivalAirport?->city ?? '',
                        'arrival_time' => $arr->format('H:i'),
                        'arrival_date' => $arr->format('d M Y'),
                        'duration_minutes' => $durationMinutes,
                        'duration_formatted' => $hours . 'j ' . $mins . 'm',
                        'display_price' => (int) round($flight->price * $multiplier),
                        'original_price' => (int) $flight->price,
                        'available_seats' => $flight->available_seats,
                        'transit_count' => 0,
                        'is_direct' => true,
                    ];
                });
        }

        $fromAirport = Airport::find($request->departure_airport_id);
        $toAirport = Airport::find($request->arrival_airport_id);

        return response()->json([
            'success' => true,
            'data' => [
                'flights' => $flights,
                'return_flights' => $returnFlights,
                'from' => $fromAirport ? $fromAirport->city . ' (' . $fromAirport->iata_code . ')' : '',
                'to' => $toAirport ? $toAirport->city . ' (' . $toAirport->iata_code . ')' : '',
                'departure_date' => $request->departure_date,
                'return_date' => $request->return_date,
                'passenger_count' => $passengerCount,
                'travel_class' => $travelClass,
                'trip_type' => $request->trip_type ?? 'one_way',
            ]
        ]);
    }
}