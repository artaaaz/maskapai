<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Flight;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        $bookingToday = Booking::whereDate('created_at', $today)->count();
        $waitingConfirmation = Booking::where('status', 'pending')->count();
        $passengersToday = Passenger::whereHas('booking', function($q) use ($today) {
            $q->whereDate('created_at', $today);
        })->count();
        $paymentPending = Payment::where('payment_status', 'pending')->count();
        $totalCheckedIn = Passenger::where('has_checked_in', true)->count();
        $totalBoarded = Passenger::where('has_boarded', true)->count();
        $todayDepartures = Flight::whereDate('departure_time', $today)->count();
        
        $latestBookings = Booking::with(['user', 'flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'payment'])
            ->latest()
            ->limit(10)
            ->get();
        
        $todayFlights = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'airplane'])
            ->whereDate('departure_time', $today)
            ->orderBy('departure_time')
            ->get();
        
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();
        
        return view('staff.dashboard', compact(
            'bookingToday',
            'waitingConfirmation',
            'passengersToday',
            'paymentPending',
            'latestBookings',
            'todayFlights',
            'totalCheckedIn',
            'totalBoarded',
            'todayDepartures',
            'recentActivities'
        ));
    }

    public function checkIn(Passenger $passenger)
    {
        $passenger->update([
            'has_checked_in' => true,
            'checked_in_at' => now(),
        ]);

        ActivityLog::log('check_in', 'Check-in penumpang: ' . $passenger->full_name);

        return back()->with('success', 'Penumpang ' . $passenger->full_name . ' berhasil check-in.');
    }

    public function boardPassenger(Passenger $passenger)
    {
        $passenger->update([
            'has_boarded' => true,
            'boarded_at' => now(),
        ]);

        ActivityLog::log('board', 'Penumpang boarding: ' . $passenger->full_name);

        return back()->with('success', 'Penumpang ' . $passenger->full_name . ' sudah boarding.');
    }

    public function checkOut(Passenger $passenger)
    {
        $passenger->update([
            'checked_out_at' => now(),
        ]);

        ActivityLog::log('check_out', 'Check-out penumpang: ' . $passenger->full_name);

        return back()->with('success', 'Penumpang ' . $passenger->full_name . ' selesai check-out.');
    }

    public function passengers()
    {
        $passengers = Passenger::with(['booking.flight.departureAirport', 'booking.flight.arrivalAirport', 'booking.flight.airline', 'booking.flight.airplane'])
            ->latest()
            ->paginate(20);

        return view('staff.passengers', compact('passengers'));
    }

    public function passengerDetail(Passenger $passenger)
    {
        $passenger->load(['booking.flight.airline', 'booking.flight.departureAirport', 'booking.flight.arrivalAirport', 'booking.flight.airplane', 'booking.user']);
        
        return view('staff.passenger-detail', compact('passenger'));
    }
}