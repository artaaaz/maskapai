<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Flight;
use App\Models\BookingExtra;
use App\Models\ActivityLog;
use App\Services\OperationalService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'all');
        $startDate = null;
        $endDate = null;

        $now = now();
        switch ($period) {
            case 'today':
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case 'week':
                $startDate = $now->copy()->startOfWeek();
                $endDate = $now->copy()->endOfWeek();
                break;
            case 'month':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                break;
            case 'year':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                break;
            case 'custom':
                $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : null;
                $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : null;
                break;
        }

        // === OPERATIONAL STATS (REAL DATA) ===
        // These stats come directly from staff activities - check-in, boarding, check-out
        $totalCheckedIn = Passenger::where('status', 'checked_in')->count();
        $totalBoarded = Passenger::where('status', 'boarded')->count();
        $totalCompleted = Passenger::where('status', 'completed')->count();
        $totalNoShow = Passenger::where('status', 'no_show')->count();
        $totalWaiting = Passenger::where('status', 'waiting')->count();

        // Today's operational stats
        $checkedInToday = Passenger::where('status', 'checked_in')
            ->whereDate('checked_in_at', $now)->count();
        $boardedToday = Passenger::where('status', 'boarded')
            ->whereDate('boarded_at', $now)->count();
        $completedToday = Passenger::where('status', 'completed')
            ->whereDate('checked_out_at', $now)->count();
        $noShowToday = Passenger::where('status', 'no_show')
            ->whereHas('booking.flight', function($q) use ($now) {
                $q->whereDate('departure_time', $now);
            })->count();

        // === FINANCIAL STATS ===
        $paymentQuery = Payment::where('payment_status', 'paid');
        $bookingQuery = Booking::query();
        $passengerQuery = Passenger::query();

        if ($startDate && $endDate) {
            $paymentQuery->whereBetween('paid_at', [$startDate, $endDate]);
            $bookingQuery->whereBetween('created_at', [$startDate, $endDate]);
            $passengerQuery->whereHas('booking', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
        }

        $totalRevenue = $paymentQuery->sum('amount');
        $totalBookings = $bookingQuery->count();
        $totalPassengers = $passengerQuery->count();
        $revenueThisMonth = Payment::where('payment_status', 'paid')
            ->whereYear('paid_at', $now->year)
            ->whereMonth('paid_at', $now->month)
            ->sum('amount');

        // Booking status counts
        $bookingsConfirmed = Booking::where('status', 'confirmed')->count();
        $bookingsPending = Booking::where('status', 'pending')->count();
        $bookingsCancelled = Booking::where('status', 'cancelled')->count();
        $flightsToday = Flight::whereDate('departure_time', $now)->count();

        // === FLIGHT STATS ===
        $totalFlights = Flight::count();
        $activeFlights = Flight::where('departure_time', '>=', $now)->count();
        $completedFlights = Flight::where('arrival_time', '<', $now)->count();

        // === REVENUE 7 DAYS ===
        $revenue7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $dayRevenue = Payment::where('payment_status', 'paid')
                ->whereDate('paid_at', $date)
                ->sum('amount');
            $revenue7Days[] = [
                'date' => $date->format('D'),
                'full_date' => $date->format('d M'),
                'revenue' => (float) $dayRevenue,
            ];
        }

        // === BOOKINGS PER MONTH (last 6 months) ===
        $bookingsPerMonth = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $count = Booking::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $bookingsPerMonth[] = [
                'month' => $date->format('M'),
                'year' => $date->format('Y'),
                'count' => $count,
            ];
        }

        // === PAYMENT METHOD DISTRIBUTION ===
        $paymentMethods = Payment::where('payment_status', 'paid')
            ->selectRaw('payment_method, COUNT(*) as total, SUM(amount) as total_amount')
            ->groupBy('payment_method')
            ->get();

        // === BOOKING STATUS DISTRIBUTION ===
        $bookingStatuses = Booking::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        // === TOP ROUTES ===
        $topRoutes = Booking::where('status', '!=', 'cancelled')
            ->with('flight.departureAirport', 'flight.arrivalAirport')
            ->get()
            ->groupBy(function ($booking) {
                if (!$booking->flight || !$booking->flight->departureAirport || !$booking->flight->arrivalAirport) {
                    return 'Unknown';
                }
                return $booking->flight->departureAirport->iata_code . ' → ' . $booking->flight->arrivalAirport->iata_code;
            })
            ->map(function ($bookings, $route) {
                return [
                    'route' => $route,
                    'total_bookings' => $bookings->count(),
                    'total_revenue' => $bookings->sum(function ($b) {
                        return $b->payment ? $b->payment->amount : 0;
                    }),
                ];
            })
            ->sortByDesc('total_bookings')
            ->take(5)
            ->values();

        // === TOP AIRLINES ===
        $topAirlines = Booking::where('status', '!=', 'cancelled')
            ->with('flight.airline')
            ->get()
            ->groupBy(function ($booking) {
                return $booking->flight?->airline?->name ?? 'Unknown';
            })
            ->map(function ($bookings, $airlineName) {
                $firstBooking = $bookings->first();
                return [
                    'name' => $airlineName,
                    'code' => $firstBooking?->flight?->airline?->code ?? '',
                    'total_bookings' => $bookings->count(),
                    'total_revenue' => $bookings->sum(function ($b) {
                        return $b->payment ? $b->payment->amount : 0;
                    }),
                ];
            })
            ->sortByDesc('total_bookings')
            ->take(5)
            ->values();

        // === RECENT ACTIVITIES ===
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        // === RECENT BOOKINGS ===
        $recentBookings = Booking::with([
            'user',
            'flight.airline',
            'flight.departureAirport',
            'flight.arrivalAirport',
            'payment',
        ])
            ->latest()
            ->limit(10)
            ->get();

        // === FLIGHTS TODAY ===
        $todayFlights = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'airplane'])
            ->whereDate('departure_time', $now)
            ->orderBy('departure_time')
            ->get()
            ->map(function ($flight) {
                $totalSeats = $flight->airplane?->seats()->count() ?? 0;
                $bookedSeats = Passenger::whereHas('booking', function ($q) use ($flight) {
                    $q->where('flight_id', $flight->id)
                      ->where('status', '!=', 'cancelled');
                })->whereNotNull('seat_number')->count();
                $flight->total_seats = $totalSeats;
                $flight->booked_seats = $bookedSeats;
                return $flight;
            });

        // === AVERAGE TICKET PRICE ===
        $avgTicketPrice = Booking::where('status', '!=', 'cancelled')->avg('total_price') ?? 0;

        // === AVERAGE REVENUE PER BOOKING ===
        $paidBookings = Payment::where('payment_status', 'paid');
        $avgRevenuePerBooking = $paidBookings->avg('amount') ?? 0;

        // === SUCCESS RATE ===
        $totalBookingsAll = Booking::count();
        $successRate = $totalBookingsAll > 0
            ? round((Booking::where('status', 'confirmed')->count() / $totalBookingsAll) * 100, 1)
            : 0;

        // === AVERAGE PASSENGERS PER BOOKING ===
        $avgPassengersPerBooking = Booking::avg('total_passengers') ?? 0;

        return view('manager.dashboard', compact(
            'totalRevenue',
            'totalBookings',
            'totalPassengers',
            'revenueThisMonth',
            'bookingsConfirmed',
            'bookingsPending',
            'bookingsCancelled',
            'flightsToday',
            'revenue7Days',
            'bookingsPerMonth',
            'paymentMethods',
            'bookingStatuses',
            'topRoutes',
            'topAirlines',
            'recentBookings',
            'todayFlights',
            'avgTicketPrice',
            'avgRevenuePerBooking',
            'successRate',
            'avgPassengersPerBooking',
            'period',
            'startDate',
            'endDate',
            // Operational stats
            'totalCheckedIn',
            'totalBoarded',
            'totalCompleted',
            'totalNoShow',
            'totalWaiting',
            'checkedInToday',
            'boardedToday',
            'completedToday',
            'noShowToday',
            'totalFlights',
            'activeFlights',
            'completedFlights',
            'recentActivities',
        ));
    }

    /**
     * Export dashboard data as PDF
     */
    public function exportPdf(Request $request)
    {
        $data = $this->getExportData($request);
        return response()->json($data);
    }

    /**
     * Export dashboard data as Excel/CSV
     */
    public function exportExcel(Request $request)
    {
        $data = $this->getExportData($request);
        return response()->json($data);
    }

    private function getExportData(Request $request)
    {
        $period = $request->get('period', 'all');
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : null;
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date) : null;

        $query = Booking::with(['user', 'flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'payment', 'passengers']);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
        }

        $bookings = $query->latest()->get();

        return [
            'period' => $period,
            'start_date' => $startDate?->format('Y-m-d'),
            'end_date' => $endDate?->format('Y-m-d'),
            'total_bookings' => $bookings->count(),
            'total_revenue' => $bookings->sum(fn($b) => $b->payment?->amount ?? 0),
            'bookings' => $bookings->map(fn($b) => [
                'booking_code' => $b->booking_code,
                'customer' => $b->user?->name,
                'flight' => $b->flight?->flight_number,
                'route' => ($b->flight?->departureAirport?->iata_code ?? '??') . ' → ' . ($b->flight?->arrivalAirport?->iata_code ?? '??'),
                'airline' => $b->flight?->airline?->name,
                'total' => $b->total_price,
                'status' => $b->status,
                'payment_status' => $b->payment?->payment_status,
                'created_at' => $b->created_at->format('Y-m-d H:i'),
            ]),
        ];
    }
}