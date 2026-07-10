<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\Promo;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $airports = Airport::orderBy('city')->get();
        $travelClasses = config('travel_class');

        $myBookings = Booking::where('user_id', Auth::id())->count();
        $myTrips = Booking::where('user_id', Auth::id())->where('status', 'confirmed')->count();
        $mySpent = Booking::where('user_id', Auth::id())
            ->whereHas('payment', fn ($q) => $q->where('payment_status', 'paid'))
            ->sum('total_price');

        $availableFlights = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
            ->where('available_seats', '>', 0)
            ->where('departure_time', '>=', now())
            ->orderBy('departure_time')
            ->limit(6)
            ->get();

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
            'availableFlights',
            'activePromos'
        ));
    }
}
