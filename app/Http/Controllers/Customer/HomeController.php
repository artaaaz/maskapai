<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\Passenger;
use App\Models\Promo;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $airports = Airport::orderBy('city')->get();
        $travelClasses = config('travel_class');

        // Guest-friendly: only query bookings if user is logged in
        $myBookings = 0;
        $myTrips = 0;
        $mySpent = 0;

        if (Auth::check()) {
            $myBookings = Booking::where('user_id', Auth::id())->count();
            $myTrips = Booking::where('user_id', Auth::id())->where('status', 'confirmed')->count();
            $mySpent = Booking::where('user_id', Auth::id())
                ->whereHas('payment', fn ($q) => $q->where('payment_status', 'paid'))
                ->sum('total_price');
        }

        // Ambil ALL penerbangan masa depan dengan flightClasses untuk harga
        // BUG 1 FIX: whereHas('flightClasses') dihapus dari query utama karena filter
        // ini membuang Flight lama yang belum punya baris flight_classes (lihat
        // Flight::ensureHasFlightClass() untuk penjelasan lengkap). Sekarang SEMUA
        // flight yang valid & belum berangkat diambil dulu, baru "disembuhkan" di
        // bawah kalau ada yang belum punya flightClasses.
        $allFlights = Flight::with([
            'airline',
            'airplane.seats',
            'departureAirport',
            'arrivalAirport',
            'flightClasses' => function ($q) {
                $q->orderBy('price', 'asc');
            }
        ])
        ->where('departure_time', '>=', now())
        ->orderBy('departure_time')
        ->paginate(6);

        // Filter menggunakan computed attribute agar available_seats_count dihitung REAL dari database
        // We need to re-paginate after filtering, so we use a different approach:
        // Get all flights, filter, then create a custom paginator
        $allFlightsCollection = $allFlights->getCollection()->filter(function ($flight) {
            return $flight->available_seats_count > 0;
        })->map(function ($flight) {
            // Self-healing: Flight lama tanpa flight_classes otomatis dibuatkan
            // kelas economy dari data flight itu sendiri (price & available_seats).
            $flight->ensureHasFlightClass();

            // Set display_price from cheapest flight class
            $cheapestClass = $flight->flightClasses->sortBy('price')->first();
            $flight->display_price = $cheapestClass ? $cheapestClass->price : $flight->price;
            return $flight;
        })->values();

        // Re-set the collection on the paginator
        $allFlights->setCollection($allFlightsCollection);

        $activePromos = Promo::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->limit(3)
            ->get();

        return view('customer.home', compact(
            'airports',
            'travelClasses',
            'myBookings',
            'myTrips',
            'mySpent',
            'allFlights',
            'activePromos'
        ));
    }
}