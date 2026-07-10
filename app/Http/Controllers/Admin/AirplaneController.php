<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Airplane;
use App\Models\Airline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AirplaneController extends Controller
{
    public function index()
    {
        $airplanes = Airplane::with('airline')->latest()->get();
        return view('admin.airplanes.index', compact('airplanes'));
    }

    public function create()
    {
        $airlines = Airline::all();
        return view('admin.airplanes.create', compact('airlines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'airline_id' => 'required|exists:airlines,id',
            'model' => 'required|string|max:255',
            'registration_number' => 'required|string|max:255|unique:airplanes,registration_number',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'photos' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photos')) {
            $validated['photos'] = $request->file('photos')->store('airplanes');
        }

        Airplane::create($validated);
        return redirect()->route('admin.airplanes.index')->with('success', 'Pesawat berhasil ditambahkan!');
    }

    public function edit(Airplane $airplane)
    {
        $airlines = Airline::all();
        return view('admin.airplanes.edit', compact('airplane', 'airlines'));
    }

    public function update(Request $request, Airplane $airplane)
    {
        $validated = $request->validate([
            'airline_id' => 'required|exists:airlines,id',
            'model' => 'required|string|max:255',
            'registration_number' => 'required|string|max:255|unique:airplanes,registration_number,' . $airplane->id,
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'photos' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photos')) {
            if ($airplane->photos) Storage::delete($airplane->photos);
            $validated['photos'] = $request->file('photos')->store('airplanes');
        }

        $airplane->update($validated);
        return redirect()->route('admin.airplanes.index')->with('success', 'Pesawat berhasil diupdate!');
    }

    public function destroy(Airplane $airplane)
    {
        if ($airplane->photos) Storage::delete($airplane->photos);
        $airplane->delete();
        return redirect()->route('admin.airplanes.index')->with('success', 'Pesawat berhasil dihapus!');
    }
}