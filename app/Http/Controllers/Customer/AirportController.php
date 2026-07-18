<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $query = trim($request->query('q'));
        $like = '%' . str_replace(' ', '%', strtolower($query)) . '%';

        $airports = Airport::select(['id', 'city', 'name', 'iata_code'])
            ->where(function ($q) use ($like) {
                $q->whereRaw('LOWER(city) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(name) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(iata_code) LIKE ?', [$like]);
            })
            ->orderBy('city')
            ->orderBy('iata_code')
            ->limit(10)
            ->get();

        return response()->json($airports);
    }
}
