<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Airplane;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function index()
    {
        $flights = Flight::with(['airline', 'airplane', 'departureAirport', 'arrivalAirport', 'flightClasses'])->latest()->get();
        return view('admin.flights.index', compact('flights'));
    }

    public function create()
    {
        $airlines = Airline::all();
        $airports = Airport::all();
        $airplanes = Airplane::all();
        return view('admin.flights.create', compact('airlines', 'airports', 'airplanes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'airline_id' => 'required|exists:airlines,id',
            'airplane_id' => 'required|exists:airplanes,id',
            'departure_airport_id' => 'required|exists:airports,id',
            'arrival_airport_id' => 'required|exists:airports,id|different:departure_airport_id',
            'flight_number' => 'required|string|max:20|unique:flights,flight_number',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date|after:departure_time',
            'price' => 'required|numeric|min:0',
            'available_seats' => 'required|integer|min:0',
        ]);

        $flight = Flight::create($validated);

        // Auto-create Economy class from the flight's price and available_seats
        $flight->flightClasses()->create([
            'class_name' => 'economy',
            'price' => $validated['price'],
            'seat_quota' => $validated['available_seats'],
        ]);

        return redirect()->route('admin.flights.index')->with('success', 'Penerbangan berhasil ditambahkan! Kelas Economy otomatis dibuat.');
    }

    public function edit(Flight $flight)
    {
        $airlines = Airline::all();
        $airports = Airport::all();
        $airplanes = Airplane::all();
        return view('admin.flights.edit', compact('flight', 'airlines', 'airports', 'airplanes'));
    }

    public function update(Request $request, Flight $flight)
    {
        $validated = $request->validate([
            'airline_id' => 'required|exists:airlines,id',
            'airplane_id' => 'required|exists:airplanes,id',
            'departure_airport_id' => 'required|exists:airports,id',
            'arrival_airport_id' => 'required|exists:airports,id|different:departure_airport_id',
            'flight_number' => 'required|string|max:20|unique:flights,flight_number,' . $flight->id,
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date|after:departure_time',
            'price' => 'required|numeric|min:0',
            'available_seats' => 'required|integer|min:0',
        ]);

        $flight->update($validated);

        // Sync economy class when flight is updated
        $economyClass = $flight->flightClasses()->where('class_name', 'economy')->first();
        if ($economyClass) {
            $economyClass->update([
                'price' => $validated['price'],
                'seat_quota' => $validated['available_seats'],
            ]);
        }

        return redirect()->route('admin.flights.index')->with('success', 'Penerbangan berhasil diupdate!');
    }

    public function destroy(Flight $flight)
    {
        $flight->delete();
        return redirect()->route('admin.flights.index')->with('success', 'Penerbangan berhasil dihapus!');
    }
}