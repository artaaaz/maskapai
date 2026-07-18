<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\FlightClass;
use Illuminate\Http\Request;

class FlightClassController extends Controller
{
    public function index(Flight $flight)
    {
        $flight->load('flightClasses');
        return view('admin.flight-classes.index', compact('flight'));
    }

    public function create(Flight $flight)
    {
        // Only show classes that haven't been added yet
        $existingClasses = $flight->flightClasses()->pluck('class_name')->toArray();
        $allClassNames = ['economy', 'premium_economy', 'business', 'first'];
        
        // Economy can only be created automatically when flight is created
        // So we hide economy from manual creation
        $classNames = array_values(array_diff($allClassNames, ['economy'], $existingClasses));
        
        if (empty($classNames)) {
            return redirect()->route('admin.flights.flight-classes.index', $flight)
                ->with('info', 'Semua kelas sudah tersedia untuk flight ini.');
        }
        
        return view('admin.flight-classes.create', compact('flight', 'classNames'));
    }

    public function store(Request $request, Flight $flight)
    {
        $validated = $request->validate([
            'class_name' => 'required|in:economy,premium_economy,business,first',
            'price' => 'required|numeric|min:0',
            'seat_quota' => 'required|integer|min:0',
        ]);

        // Check duplicate class
        if ($flight->flightClasses()->where('class_name', $validated['class_name'])->exists()) {
            return back()->with('error', 'Kelas "' . $validated['class_name'] . '" sudah ada untuk flight ini.')->withInput();
        }

        // Validasi seat_quota tidak melebihi layout pesawat
        $layoutSeatCount = \App\Models\Seat::where('airplane_id', $flight->airplane_id)
            ->whereRaw('LOWER(`class`) = ?', [strtolower($validated['class_name'])])
            ->count();

        if ($layoutSeatCount > 0 && $validated['seat_quota'] > $layoutSeatCount) {
            return back()->with('error', 'Jumlah kursi ' . str_replace('_', ' ', $validated['class_name']) . ' melebihi kapasitas layout pesawat. Maksimal ' . $layoutSeatCount . ' kursi.')->withInput();
        }

        $flight->flightClasses()->create($validated);

        return redirect()->route('admin.flights.flight-classes.index', $flight)
            ->with('success', 'Kelas penerbangan berhasil ditambahkan.');
    }

    public function edit(Flight $flight, FlightClass $flightClass)
    {
        $classNames = ['economy', 'premium_economy', 'business', 'first'];
        return view('admin.flight-classes.edit', compact('flight', 'flightClass', 'classNames'));
    }

    public function update(Request $request, Flight $flight, FlightClass $flightClass)
    {
        $validated = $request->validate([
            'class_name' => 'required|in:economy,premium_economy,business,first',
            'price' => 'required|numeric|min:0',
            'seat_quota' => 'required|integer|min:0',
        ]);

        $flightClass->update($validated);

        return redirect()->route('admin.flights.flight-classes.index', $flight)
            ->with('success', 'Kelas penerbangan berhasil diperbarui.');
    }

    public function destroy(Flight $flight, FlightClass $flightClass)
    {
        $flightClass->delete();
        return redirect()->route('admin.flights.flight-classes.index', $flight)
            ->with('success', 'Kelas penerbangan berhasil dihapus.');
    }
}