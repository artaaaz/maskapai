<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Flight;
use App\Models\Airline;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class ManagerReportController extends Controller
{
    /**
     * Parse date range based on request period.
     */
    private function getDateRange(Request $request)
    {
        $period = $request->get('period', 'month');
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

        return [$period, $startDate, $endDate];
    }

    /**
     * Display executive reports.
     */
    public function index(Request $request)
    {
        list($period, $startDate, $endDate) = $this->getDateRange($request);
        $now = now();

        // 1. STAT CARDS
        $revenueQuery = Payment::where('payment_status', 'paid');
        $bookingQuery = Booking::query();
        $passengerQuery = Passenger::query();
        $flightQuery = Flight::query();

        if ($startDate && $endDate) {
            $revenueQuery->whereBetween('paid_at', [$startDate, $endDate]);
            $bookingQuery->whereBetween('created_at', [$startDate, $endDate]);
            $passengerQuery->whereHas('booking', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
            $flightQuery->whereBetween('departure_time', [$startDate, $endDate]);
        }

        $totalRevenue = $revenueQuery->sum('amount');
        $totalBookings = $bookingQuery->count();
        $totalPassengers = $passengerQuery->count();
        $totalFlights = $flightQuery->count();
        $totalAirlines = Airline::count();

        // Revenue current calendar month
        $revenueThisMonth = Payment::where('payment_status', 'paid')
            ->whereYear('paid_at', $now->year)
            ->whereMonth('paid_at', $now->month)
            ->sum('amount');

        // Status counts
        $pendingQuery = Booking::where('status', 'pending');
        $confirmedQuery = Booking::where('status', 'confirmed');
        $cancelledQuery = Booking::where('status', 'cancelled');

        if ($startDate && $endDate) {
            $pendingQuery->whereBetween('created_at', [$startDate, $endDate]);
            $confirmedQuery->whereBetween('created_at', [$startDate, $endDate]);
            $cancelledQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $bookingsPending = $pendingQuery->count();
        $bookingsConfirmed = $confirmedQuery->count();
        $bookingsCancelled = $cancelledQuery->count();

        // Daily / Today counts (dynamically matches date filter if set, otherwise today's count)
        $boardingCountQuery = Passenger::where('has_boarded', true);
        $checkInCountQuery = Passenger::where('has_checked_in', true);
        $flightsTodayQuery = Flight::query();

        if ($startDate && $endDate) {
            $boardingCountQuery->whereBetween('boarded_at', [$startDate, $endDate]);
            $checkInCountQuery->whereBetween('checked_in_at', [$startDate, $endDate]);
            $flightsTodayQuery->whereBetween('departure_time', [$startDate, $endDate]);
        } else {
            $boardingCountQuery->whereDate('boarded_at', $now->today());
            $checkInCountQuery->whereDate('checked_in_at', $now->today());
            $flightsTodayQuery->whereDate('departure_time', $now->today());
        }

        $boardingToday = $boardingCountQuery->count();
        $checkInToday = $checkInCountQuery->count();
        $flightsTodayCount = $flightsTodayQuery->count();

        // 2. CHART DATA
        // Revenue 7 Days
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

        // Bookings per Month (last 6 months)
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

        // Booking status distribution
        $bookingStatusesQuery = Booking::selectRaw('status, COUNT(*) as total');
        if ($startDate && $endDate) {
            $bookingStatusesQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $bookingStatuses = $bookingStatusesQuery->groupBy('status')->get();

        // Payment method distribution
        $paymentMethodsQuery = Payment::where('payment_status', 'paid')
            ->selectRaw('payment_method, COUNT(*) as total, SUM(amount) as total_amount');
        if ($startDate && $endDate) {
            $paymentMethodsQuery->whereBetween('paid_at', [$startDate, $endDate]);
        }
        $paymentMethods = $paymentMethodsQuery->groupBy('payment_method')->get();

        // Booking by Airline
        $bookingsByAirlineQuery = Booking::join('flights', 'bookings.flight_id', '=', 'flights.id')
            ->join('airlines', 'flights.airline_id', '=', 'airlines.id')
            ->selectRaw('airlines.name as name, COUNT(bookings.id) as total');
        if ($startDate && $endDate) {
            $bookingsByAirlineQuery->whereBetween('bookings.created_at', [$startDate, $endDate]);
        }
        $bookingsByAirline = $bookingsByAirlineQuery->groupBy('airlines.name')->get();

        // Booking by Route
        $bookingsByRouteQuery = Booking::join('flights', 'bookings.flight_id', '=', 'flights.id')
            ->join('airports as dep', 'flights.departure_airport_id', '=', 'dep.id')
            ->join('airports as arr', 'flights.arrival_airport_id', '=', 'arr.id')
            ->selectRaw('CONCAT(dep.iata_code, " → ", arr.iata_code) as route, COUNT(bookings.id) as total');
        if ($startDate && $endDate) {
            $bookingsByRouteQuery->whereBetween('bookings.created_at', [$startDate, $endDate]);
        }
        $bookingsByRoute = $bookingsByRouteQuery->groupBy('route')->orderByDesc('total')->take(10)->get();

        // 3. TABLES DATA
        // Top Airlines
        $topAirlines = Booking::where('bookings.status', '!=', 'cancelled')
            ->join('flights', 'bookings.flight_id', '=', 'flights.id')
            ->join('airlines', 'flights.airline_id', '=', 'airlines.id')
            ->leftJoin('payments', function($join) {
                $join->on('bookings.id', '=', 'payments.booking_id')
                     ->where('payments.payment_status', '=', 'paid');
            })
            ->selectRaw('airlines.name, airlines.code, COUNT(bookings.id) as total_bookings, SUM(payments.amount) as total_revenue')
            ->when($startDate && $endDate, function($q) use ($startDate, $endDate) {
                $q->whereBetween('bookings.created_at', [$startDate, $endDate]);
            })
            ->groupBy('airlines.name', 'airlines.code')
            ->orderByDesc('total_bookings')
            ->take(5)
            ->get();

        // Top Routes
        $topRoutes = Booking::where('bookings.status', '!=', 'cancelled')
            ->join('flights', 'bookings.flight_id', '=', 'flights.id')
            ->join('airports as dep', 'flights.departure_airport_id', '=', 'dep.id')
            ->join('airports as arr', 'flights.arrival_airport_id', '=', 'arr.id')
            ->leftJoin('payments', function($join) {
                $join->on('bookings.id', '=', 'payments.booking_id')
                     ->where('payments.payment_status', '=', 'paid');
            })
            ->selectRaw('CONCAT(dep.iata_code, " → ", arr.iata_code) as route, COUNT(bookings.id) as total_bookings, SUM(payments.amount) as total_revenue')
            ->when($startDate && $endDate, function($q) use ($startDate, $endDate) {
                $q->whereBetween('bookings.created_at', [$startDate, $endDate]);
            })
            ->groupBy('route')
            ->orderByDesc('total_bookings')
            ->take(5)
            ->get();

        // Recent Bookings
        $recentBookings = Booking::with(['user', 'flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'payment'])
            ->when($startDate && $endDate, function($q) use ($startDate, $endDate) {
                $q->whereBetween('bookings.created_at', [$startDate, $endDate]);
            })
            ->latest()
            ->limit(10)
            ->get();

        // Recent Payments
        $recentPayments = Payment::with(['booking.user', 'booking.flight'])
            ->when($startDate && $endDate, function($q) use ($startDate, $endDate) {
                $q->whereBetween('payments.created_at', [$startDate, $endDate]);
            })
            ->latest()
            ->limit(10)
            ->get();

        // Flights Today (real flights list with occupied & total seats details)
        $todayFlightsList = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'airplane'])
            ->whereDate('departure_time', $now->today())
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

        return view('manager.reports.index', compact(
            'period',
            'startDate',
            'endDate',
            'totalRevenue',
            'revenueThisMonth',
            'totalBookings',
            'totalPassengers',
            'totalFlights',
            'totalAirlines',
            'bookingsPending',
            'bookingsConfirmed',
            'bookingsCancelled',
            'boardingToday',
            'checkInToday',
            'flightsTodayCount',
            'revenue7Days',
            'bookingsPerMonth',
            'bookingStatuses',
            'paymentMethods',
            'bookingsByAirline',
            'bookingsByRoute',
            'topAirlines',
            'topRoutes',
            'recentBookings',
            'recentPayments',
            'todayFlightsList'
        ));
    }

    /**
     * Export report data as Excel (CSV compatible).
     */
    public function exportExcel(Request $request)
    {
        list($period, $startDate, $endDate) = $this->getDateRange($request);

        $bookingsQuery = Booking::with([
            'user',
            'flight.airline',
            'flight.departureAirport',
            'flight.arrivalAirport',
            'payment',
            'passengers'
        ]);

        if ($startDate && $endDate) {
            $bookingsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $bookings = $bookingsQuery->latest()->get();
        $filename = 'Laporan-Eksekutif-Manager-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($bookings, $period, $startDate, $endDate) {
            $handle = fopen('php://output', 'w');

            // BOM for UTF-8 Excel compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // TITLE AND FILTER INFO
            fputcsv($handle, ['LAPORAN EKSEKUTIF MANAJER - drg.Maskapai']);
            fputcsv($handle, ['Tanggal Cetak', now()->format('d/m/Y H:i')]);
            fputcsv($handle, ['Periode Filter', strtoupper($period)]);
            if ($startDate && $endDate) {
                fputcsv($handle, ['Rentang Tanggal', $startDate->format('d/m/Y') . ' s.d. ' . $endDate->format('d/m/Y')]);
            }
            fputcsv($handle, ['']);

            // STATS SUMMARY
            $totalRevenue = $bookings->sum(fn($b) => $b->payment && $b->payment->payment_status === 'paid' ? $b->payment->amount : 0);
            $totalBookings = $bookings->count();
            $totalPassengers = $bookings->sum('total_passengers');
            $confirmedBookings = $bookings->where('status', 'confirmed')->count();
            $pendingBookings = $bookings->where('status', 'pending')->count();
            $cancelledBookings = $bookings->where('status', 'cancelled')->count();

            fputcsv($handle, ['RINGKASAN METRIK']);
            fputcsv($handle, ['Total Revenue', 'Rp ' . number_format($totalRevenue, 0, ',', '.')]);
            fputcsv($handle, ['Total Booking', $totalBookings]);
            fputcsv($handle, ['Total Penumpang', $totalPassengers]);
            fputcsv($handle, ['Confirmed Bookings', $confirmedBookings]);
            fputcsv($handle, ['Pending Payments', $pendingBookings]);
            fputcsv($handle, ['Cancelled Bookings', $cancelledBookings]);
            fputcsv($handle, ['']);
            fputcsv($handle, ['']);

            // DETAILS SECTION
            fputcsv($handle, ['DETAIL DATA TRANSAKSI']);
            fputcsv($handle, [
                'Kode Booking',
                'Customer',
                'Email',
                'Flight Number',
                'Airline',
                'Departure',
                'Arrival',
                'Total Passengers',
                'Total Price',
                'Payment Status',
                'Booking Status',
                'Tanggal Booking'
            ]);

            foreach ($bookings as $b) {
                fputcsv($handle, [
                    $b->booking_code,
                    $b->user->name ?? 'N/A',
                    $b->user->email ?? 'N/A',
                    $b->flight->flight_number ?? 'N/A',
                    $b->flight->airline->name ?? 'N/A',
                    $b->flight->departureAirport->iata_code ?? 'N/A',
                    $b->flight->arrivalAirport->iata_code ?? 'N/A',
                    $b->total_passengers,
                    'Rp ' . number_format($b->total_price, 0, ',', '.'),
                    $b->payment ? ucfirst($b->payment->payment_status) : 'Unpaid',
                    ucfirst($b->status),
                    $b->created_at->format('d/m/Y H:i')
                ]);
            }

            fputcsv($handle, ['']);
            fputcsv($handle, ['Laporan ini dibuat otomatis oleh drg.Maskapai Manager Portal']);
            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export report data as PDF.
     */
    public function exportPdf(Request $request)
    {
        list($period, $startDate, $endDate) = $this->getDateRange($request);
        $now = now();

        $bookingsQuery = Booking::with([
            'user',
            'flight.airline',
            'flight.departureAirport',
            'flight.arrivalAirport',
            'payment'
        ]);

        if ($startDate && $endDate) {
            $bookingsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $bookings = $bookingsQuery->latest()->get();

        $totalRevenue = $bookings->sum(fn($b) => $b->payment && $b->payment->payment_status === 'paid' ? $b->payment->amount : 0);
        $totalBookings = $bookings->count();
        $totalPassengers = $bookings->sum('total_passengers');
        $confirmedBookings = $bookings->where('status', 'confirmed')->count();
        $pendingBookings = $bookings->where('status', 'pending')->count();
        $cancelledBookings = $bookings->where('status', 'cancelled')->count();

        // Top routes and airlines in this query
        $topRoutes = Booking::where('bookings.status', '!=', 'cancelled')
            ->join('flights', 'bookings.flight_id', '=', 'flights.id')
            ->join('airports as dep', 'flights.departure_airport_id', '=', 'dep.id')
            ->join('airports as arr', 'flights.arrival_airport_id', '=', 'arr.id')
            ->leftJoin('payments', function($join) {
                $join->on('bookings.id', '=', 'payments.booking_id')
                     ->where('payments.payment_status', '=', 'paid');
            })
            ->selectRaw('CONCAT(dep.iata_code, " → ", arr.iata_code) as route, COUNT(bookings.id) as total_bookings, SUM(payments.amount) as total_revenue')
            ->when($startDate && $endDate, function($q) use ($startDate, $endDate) {
                $q->whereBetween('bookings.created_at', [$startDate, $endDate]);
            })
            ->groupBy('route')
            ->orderByDesc('total_bookings')
            ->take(5)
            ->get();

        $topAirlines = Booking::where('bookings.status', '!=', 'cancelled')
            ->join('flights', 'bookings.flight_id', '=', 'flights.id')
            ->join('airlines', 'flights.airline_id', '=', 'airlines.id')
            ->leftJoin('payments', function($join) {
                $join->on('bookings.id', '=', 'payments.booking_id')
                     ->where('payments.payment_status', '=', 'paid');
            })
            ->selectRaw('airlines.name, COUNT(bookings.id) as total_bookings, SUM(payments.amount) as total_revenue')
            ->when($startDate && $endDate, function($q) use ($startDate, $endDate) {
                $q->whereBetween('bookings.created_at', [$startDate, $endDate]);
            })
            ->groupBy('airlines.name')
            ->orderByDesc('total_bookings')
            ->take(5)
            ->get();

        $data = compact(
            'period',
            'startDate',
            'endDate',
            'bookings',
            'totalRevenue',
            'totalBookings',
            'totalPassengers',
            'confirmedBookings',
            'pendingBookings',
            'cancelledBookings',
            'topRoutes',
            'topAirlines'
        );

        $pdf = Pdf::loadView('manager.reports.pdf', $data);
        return $pdf->download('Laporan-Eksekutif-Manager-' . now()->format('Y-m-d') . '.pdf');
    }
}
