<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Airline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AirlineController extends Controller
{
    public function index()
    {
        $airlines = Airline::latest()->get();
        return view('admin.airlines.index', compact('airlines'));
    }

    public function create()
    {
        return view('admin.airlines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:airlines,code',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos');
        }

        Airline::create($validated);
        return redirect()->route('admin.airlines.index')->with('success', 'Maskapai berhasil ditambahkan!');
    }

    public function edit(Airline $airline)
    {
        return view('admin.airlines.edit', compact('airline'));
    }

    public function update(Request $request, Airline $airline)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:airlines,code,' . $airline->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($airline->logo) Storage::delete($airline->logo);
            $validated['logo'] = $request->file('logo')->store('logos');
        }

        $airline->update($validated);
        return redirect()->route('admin.airlines.index')->with('success', 'Maskapai berhasil diupdate!');
    }

    public function destroy(Airline $airline)
    {
        if ($airline->logo) Storage::delete($airline->logo);
        $airline->delete();
        return redirect()->route('admin.airlines.index')->with('success', 'Maskapai berhasil dihapus!');
    }
}