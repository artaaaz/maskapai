<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Flight;
use App\Models\ActivityLog;
use App\Services\OperationalService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private OperationalService $operational;

    public function __construct(OperationalService $operational)
    {
        $this->operational = $operational;
    }

    public function index()
    {
        $today = Carbon::today();

        // Stats
        $bookingToday = Booking::whereDate('created_at', $today)->count();
        $waitingConfirmation = Booking::where('status', 'pending')->count();
        $passengersToday = Passenger::whereHas('booking.flight', function($q) use ($today) {
            $q->whereDate('departure_time', $today);
        })->count();
        $paymentPending = Payment::where('payment_status', 'pending')->count();

        // Booking status stats
        $bookingsPending = Booking::where('status', 'pending')->count();
        $bookingsConfirmed = Booking::where('status', 'confirmed')->count();
        $bookingsInProgress = Booking::where('status', 'in_progress')->count();
        $bookingsCompleted = Booking::where('status', 'completed')->count();
        $bookingsCancelled = Booking::where('status', 'cancelled')->count();

        // Operational stats
        $totalCheckedIn = Passenger::where('status', 'checked_in')->count();
        $totalBoarded = Passenger::where('status', 'boarded')->count();
        $totalCompleted = Passenger::where('status', 'completed')->count();
        $totalNoShow = Passenger::where('status', 'no_show')->count();
        $totalWaiting = Passenger::where('status', 'waiting')->count();

        // Today's stats
        $checkedInToday = Passenger::where('status', 'checked_in')
            ->whereDate('checked_in_at', $today)->count();
        $boardedToday = Passenger::where('status', 'boarded')
            ->whereDate('boarded_at', $today)->count();
        $completedToday = Passenger::where('status', 'completed')
            ->whereDate('checked_out_at', $today)->count();
        $noShowToday = Passenger::where('status', 'no_show')
            ->whereHas('booking.flight', function($q) use ($today) {
                $q->whereDate('departure_time', $today);
            })->count();

        $todayDepartures = Flight::whereDate('departure_time', $today)->count();
        $todayArrivals = Flight::whereDate('arrival_time', $today)->count();

        // Today's flights with seat availability
        $todayFlights = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'airplane'])
            ->whereDate('departure_time', $today)
            ->orderBy('departure_time')
            ->get();

        // Latest bookings
        $latestBookings = Booking::with(['user', 'flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'payment'])
            ->latest()
            ->limit(10)
            ->get();

        // Recent activities
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
            'totalCompleted',
            'totalNoShow',
            'totalWaiting',
            'todayDepartures',
            'todayArrivals',
            'recentActivities',
            'bookingsPending',
            'bookingsConfirmed',
            'bookingsInProgress',
            'bookingsCompleted',
            'bookingsCancelled',
            'checkedInToday',
            'boardedToday',
            'completedToday',
            'noShowToday'
        ));
    }

    public function checkIn(Passenger $passenger)
    {
        $this->operational->checkIn($passenger);

        return back()->with('success', 'Penumpang ' . $passenger->full_name . ' berhasil check-in.');
    }

    public function boardPassenger(Passenger $passenger)
    {
        $this->operational->board($passenger);

        return back()->with('success', 'Penumpang ' . $passenger->full_name . ' sudah boarding.');
    }

    public function checkOut(Passenger $passenger)
    {
        $this->operational->checkOut($passenger);

        return back()->with('success', 'Penumpang ' . $passenger->full_name . ' selesai check-out.');
    }

    public function passengers()
    {
        $passengers = Passenger::with([
            'booking.flight.departureAirport',
            'booking.flight.arrivalAirport',
            'booking.flight.airline',
            'booking.flight.airplane'
        ])
            ->latest()
            ->paginate(20);

        return view('staff.passengers', compact('passengers'));
    }

    public function passengerDetail(Passenger $passenger)
    {
        $passenger->load([
            'booking.flight.airline',
            'booking.flight.departureAirport',
            'booking.flight.arrivalAirport',
            'booking.flight.airplane',
            'booking.user'
        ]);

        return view('staff.passenger-detail', compact('passenger'));
    }

    /**
     * Passenger monitoring with filters
     */
    public function monitoring(Request $request)
    {
        $query = Passenger::with([
            'booking.flight.airline',
            'booking.flight.departureAirport',
            'booking.flight.arrivalAirport',
        ]);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by flight
        if ($request->filled('flight_id')) {
            $query->whereHas('booking', function ($q) use ($request) {
                $q->where('flight_id', $request->flight_id);
            });
        }

        // Filter by airline
        if ($request->filled('airline_id')) {
            $query->whereHas('booking.flight', function ($q) use ($request) {
                $q->where('airline_id', $request->airline_id);
            });
        }

        // Filter today only
        if ($request->boolean('today')) {
            $today = Carbon::today();
            $query->whereHas('booking.flight', function ($q) use ($today) {
                $q->whereDate('departure_time', $today);
            });
        }

        $passengers = $query->latest()->paginate(30);

        $flights = Flight::with('airline')->where('departure_time', '>=', now()->subDay())->orderBy('departure_time')->get();
        $statuses = ['waiting', 'checked_in', 'boarded', 'completed', 'no_show'];

        return view('staff.monitoring', compact('passengers', 'flights', 'statuses'));
    }

    /**
     * Flight passenger manifest
     */
    public function manifest(Flight $flight)
    {
        $flight->load(['airline', 'departureAirport', 'arrivalAirport', 'airplane']);

        $passengers = Passenger::with('booking')
            ->whereHas('booking', function ($q) use ($flight) {
                $q->where('flight_id', $flight->id)
                  ->where('status', '!=', 'cancelled');
            })
            ->orderBy('seat_number')
            ->get();

        return view('staff.manifest', compact('flight', 'passengers'));
    }
}