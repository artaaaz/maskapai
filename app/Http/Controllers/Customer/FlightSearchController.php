<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Airport;
use Illuminate\Http\Request;

class FlightSearchController extends Controller
{
    public function index(Request $request)
    {
        $airports = Airport::orderBy('city')->get();
        $travelClasses = config('travel_class');

        if ($request->has('departure_airport_id')) {
            $request->validate([
                'departure_airport_id' => 'required|exists:airports,id',
                'arrival_airport_id' => 'required|exists:airports,id|different:departure_airport_id',
                'departure_date' => 'required|date|after_or_equal:today',
                'trip_type' => 'required|in:one_way,round_trip',
                'return_date' => 'nullable|required_if:trip_type,round_trip|date|after:departure_date',
                'passenger_count' => 'required|integer|min:1|max:9',
                'travel_class' => 'required|in:' . implode(',', array_keys(config('travel_class'))),
            ]);

            $flights = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'airplane'])
                ->where('departure_airport_id', $request->departure_airport_id)
                ->where('arrival_airport_id', $request->arrival_airport_id)
                ->whereDate('departure_time', $request->departure_date)
                ->where('available_seats', '>=', $request->passenger_count)
                ->orderBy('departure_time')
                ->get()
                ->map(function ($flight) use ($request) {
                    $multiplier = config('travel_class.' . $request->travel_class . '.multiplier', 1);
                    $flight->display_price = $flight->price * $multiplier;
                    return $flight;
                });

            $returnFlights = collect();
            if ($request->trip_type === 'round_trip' && $request->return_date) {
                $returnFlights = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'airplane'])
                    ->where('departure_airport_id', $request->arrival_airport_id)
                    ->where('arrival_airport_id', $request->departure_airport_id)
                    ->whereDate('departure_time', $request->return_date)
                    ->where('available_seats', '>=', $request->passenger_count)
                    ->orderBy('departure_time')
                    ->get()
                    ->map(function ($flight) use ($request) {
                        $multiplier = config('travel_class.' . $request->travel_class . '.multiplier', 1);
                        $flight->display_price = $flight->price * $multiplier;
                        return $flight;
                    });
            }

            $searchParams = $request->only([
                'departure_airport_id',
                'arrival_airport_id',
                'departure_date',
                'return_date',
                'trip_type',
                'passenger_count',
                'travel_class',
            ]);

            return view('customer.search', compact(
                'flights',
                'returnFlights',
                'airports',
                'travelClasses',
                'searchParams'
            ));
        }

        return view('customer.search', compact('airports', 'travelClasses'));
    }

    public function search(Request $request)
    {
        return redirect()->route('customer.search', $request->all());
    }

    public function show(Flight $flight, Request $request)
    {
        $flight->load(['airline', 'departureAirport', 'arrivalAirport', 'airplane']);

        $travelClass = $request->get('travel_class', 'economy');
        $passengerCount = max(1, (int) $request->get('passenger_count', 1));
        $multiplier = config('travel_class.' . $travelClass . '.multiplier', 1);
        $flight->display_price = $flight->price * $multiplier;

        return view('customer.flight-detail', compact('flight', 'travelClass', 'passengerCount'));
    }
}
