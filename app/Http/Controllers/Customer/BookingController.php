<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\InsuranceOption;
use App\Models\BookingExtra;
use App\Models\Seat;
use App\Services\SeatLayoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class BookingController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    /**
     * Flight Detail page (guest-friendly)
     * Shows flight info + class selection
     */
    public function flightDetail(Flight $flight, Request $request)
    {
        $flight->load([
            'airline', 
            'departureAirport', 
            'arrivalAirport', 
            'airplane.seats', 
            'flightClasses'
        ]);
        
        $travelClass = $request->get('travel_class', 'economy');
        $passengerCount = max(1, (int) $request->get('passenger_count', 1));

        $selectedFlightClass = $flight->flightClasses->firstWhere('class_name', $travelClass);
        if (!$selectedFlightClass) {
            $selectedFlightClass = $flight->flightClasses->firstWhere('class_name', 'economy');
        }
        if (!$selectedFlightClass) {
            $selectedFlightClass = $flight->flightClasses->sortBy('price')->first();
        }

        $bookedSeatNumbers = Passenger::whereHas('booking', function ($query) use ($flight) {
            $query->where('flight_id', $flight->id)
                  ->where('status', '!=', 'cancelled');
        })->whereNotNull('seat_number')->pluck('seat_number')->toArray();

        return view('customer.flight-detail', compact(
            'flight', 
            'travelClass', 
            'passengerCount', 
            'bookedSeatNumbers', 
            'selectedFlightClass'
        ));
    }

    /**
     * Seat Selection page (requires login)
     * Shows available seats for the selected flight class
     * After user selects seats, proceeds to booking form
     */
    public function seatSelection(Flight $flight, Request $request)
    {
        $flight->load([
            'airline', 
            'departureAirport', 
            'arrivalAirport', 
            'airplane.seats', 
            'flightClasses'
        ]);

        $selectedClassId = $request->get('flight_class_id');
        $passengerCount = max(1, (int) $request->get('passenger_count', 1));
        $selectedFlightClass = null;

        if ($selectedClassId) {
            $selectedFlightClass = $flight->flightClasses()->find($selectedClassId);
        }

        // Auto-select if only one class
        if (!$selectedFlightClass && $flight->flightClasses->count() === 1) {
            $selectedFlightClass = $flight->flightClasses->first();
        }

        if (!$selectedFlightClass) {
            return redirect()->route('customer.flights.detail', ['flight' => $flight])
                ->with('error', 'Silakan pilih kelas penerbangan terlebih dahulu.');
        }

        // Get seats for the airplane filtered by class
        // Tabel seats = layout pesawat (template), bukan jumlah yang dijual
        // Jumlah yang ditampilkan = seat_quota dari flight_class
        $airplaneId = $flight->airplane_id;
        $seatQuota = $selectedFlightClass->seat_quota;

        // BUG 2 FIX: self-heal - generate/top-up kursi otomatis kalau tabel
        // seats belum cukup untuk kelas ini, sebelum ditampilkan ke customer.
        SeatLayoutService::ensureSeatsForClass($flight->airplane, $selectedFlightClass->class_name, $seatQuota);

        $seats = Seat::where('airplane_id', $airplaneId)
            ->whereRaw('LOWER(`class`) = ?', [strtolower($selectedFlightClass->class_name)])
            ->orderBy('seat_number')
            ->take($seatQuota)
            ->get();

        // Get booked seat numbers for this flight
        $bookedSeatNumbers = Passenger::whereHas('booking', function ($query) use ($flight) {
            $query->where('flight_id', $flight->id)
                  ->where('status', '!=', 'cancelled');
        })->whereNotNull('seat_number')->pluck('seat_number')->toArray();

        // Also check seat_reservations via seat_id -> seats.seat_number
        $reservedSeats = \App\Models\SeatReservation::where('seat_reservations.flight_id', $flight->id)
            ->where('seat_reservations.flight_class_id', $selectedFlightClass->id)
            ->whereIn('seat_reservations.status', ['reserved', 'paid'])
            ->join('seats', 'seats.id', '=', 'seat_reservations.seat_id')
            ->pluck('seats.seat_number')
            ->toArray();

        $bookedSeatNumbers = array_unique(array_merge($bookedSeatNumbers, $reservedSeats));

        // Determine availability dynamically
        foreach ($seats as $seat) {
            if (in_array($seat->seat_number, $bookedSeatNumbers)) {
                $seat->status = 'booked';
            } else {
                $seat->status = 'available';
            }
        }

        // Group by row
        $groupedSeats = $seats->groupBy(function ($seat) {
            preg_match('/^(\d+)/', $seat->seat_number, $matches);
            return $matches[1] ?? '0';
        })->sortKeys();

        $travelClass = $request->get('travel_class', $selectedFlightClass->class_name);

        $selectedClassName = $selectedFlightClass->class_name;

        return view('customer.seat-selection', compact(
            'flight',
            'selectedFlightClass',
            'passengerCount',
            'groupedSeats',
            'selectedClassName',
            'travelClass'
        ));
    }

    /**
     * Process seat selection and redirect to booking form
     */
    public function storeSeats(Request $request, Flight $flight)
    {
        $request->validate([
            'flight_class_id' => 'required|exists:flight_classes,id',
            'passenger_count' => 'required|integer|min:1|max:9',
            'seat_numbers' => 'required|array|min:1',
            'seat_numbers.*' => 'required|string',
            'travel_class' => 'nullable|string',
        ]);

        $passengerCount = (int) $request->passenger_count;
        $seatNumbers = $request->seat_numbers;

        if (count($seatNumbers) !== $passengerCount) {
            return back()->with('error', 'Jumlah kursi harus sama dengan jumlah penumpang.')
                ->withInput();
        }

        // Verify seats are available
        $flight = Flight::with('airplane.seats', 'flightClasses')->findOrFail($flight->id);
        $selectedClass = $flight->flightClasses()->findOrFail($request->flight_class_id);

        $bookedSeatNumbers = Passenger::whereHas('booking', function ($query) use ($flight) {
            $query->where('flight_id', $flight->id)
                  ->where('status', '!=', 'cancelled');
        })->whereNotNull('seat_number')
          ->whereIn('seat_number', $seatNumbers)
          ->pluck('seat_number')
          ->toArray();

        if (!empty($bookedSeatNumbers)) {
            return back()->with('error', 'Kursi ' . implode(', ', $bookedSeatNumbers) . ' sudah dipesan. Silakan pilih kursi lain.')
                ->withInput();
        }

        // Store selected seats in session temporary for booking creation
        $request->session()->put('booking_seats_' . $flight->id, [
            'flight_class_id' => $request->flight_class_id,
            'seat_numbers' => $seatNumbers,
            'passenger_count' => $passengerCount,
            'travel_class' => $request->travel_class ?? $selectedClass->class_name,
        ]);

        return redirect()->route('customer.booking.create', [
            'flight' => $flight,
            'flight_class_id' => $request->flight_class_id,
            'passenger_count' => $passengerCount,
            'travel_class' => $request->travel_class ?? $selectedClass->class_name,
            'seat_numbers' => $seatNumbers,
        ]);
    }

    /**
     * Show booking form (requires login)
     * Receives flight, flight_class_id, and seat_numbers
     */
    public function create(Flight $flight)
    {
        $flight->load(['airline', 'departureAirport', 'arrivalAirport', 'flightClasses']);
        
        $selectedClassId = request('flight_class_id');
        $selectedClass = null;
        
        if ($selectedClassId) {
            $selectedClass = $flight->flightClasses()->find($selectedClassId);
        }
        
        // Auto-select Economy if flight has only one class
        if (!$selectedClass && $flight->flightClasses->count() === 1) {
            $selectedClass = $flight->flightClasses->first();
        }
        
        if (!$selectedClass) {
            return redirect()->route('customer.flights.detail', ['flight' => $flight])
                ->with('error', 'Silakan pilih kelas penerbangan terlebih dahulu.');
        }
        
        $unitPrice = $selectedClass->price;
        
        // Get seat numbers from query params or session
        $seatNumbers = request('seat_numbers', []);
        if (empty($seatNumbers) || !is_array($seatNumbers)) {
            // Try session
            $sessionData = session('booking_seats_' . $flight->id);
            if ($sessionData && $sessionData['flight_class_id'] == $selectedClass->id) {
                $seatNumbers = $sessionData['seat_numbers'] ?? [];
            }
        }
        
        // Ensure seat numbers are selected
        $passengerCount = max(1, (int) request('passenger_count', 1));
        if (empty($seatNumbers) || count($seatNumbers) !== $passengerCount) {
            return redirect()->route('customer.flight-detail.seat-selection', [
                'flight' => $flight,
                'flight_class_id' => $selectedClass->id,
                'passenger_count' => $passengerCount,
            ])->with('error', 'Silakan pilih kursi terlebih dahulu.');
        }
        
        $returnFlight = null;
        if (request()->has('return_flight_id')) {
            $returnFlight = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])->find(request('return_flight_id'));
        }
        
        return view('customer.booking-create', compact(
            'flight', 
            'returnFlight', 
            'selectedClass', 
            'unitPrice',
            'seatNumbers',
            'passengerCount'
        ));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'flight_id' => 'required|exists:flights,id',
                'flight_class_id' => 'required|exists:flight_classes,id',
                'return_flight_id' => 'nullable|exists:flights,id',
                'passenger_count' => 'required|integer|min:1|max:9',
                'booker_title' => 'required|in:Mr,Mrs,Ms',
                'booker_name' => 'required|string|max:255',
                'booker_phone' => 'required|string|max:20',
                'booker_email' => 'required|email|max:255',
                'trip_type' => 'nullable|in:one_way,round_trip',
                'return_date' => 'nullable|date',
                'passengers' => 'required|array|min:1',
                'passengers.*.title' => 'required|in:Mr,Mrs,Ms',
                'passengers.*.full_name' => 'required|string|max:255',
                'passengers.*.gender' => 'required|in:male,female',
                'passengers.*.birth_date' => 'required|date',
                'passengers.*.passport_number' => 'required|string|max:255',
                'seat_numbers' => 'required|array|min:1',
                'seat_numbers.*' => 'required|string',
            ]);

            $flight = Flight::with('airplane.seats', 'flightClasses')->findOrFail($request->flight_id);
            $flightClass = $flight->flightClasses()->findOrFail($request->flight_class_id);
            
            $returnFlight = null;
            if ($request->return_flight_id && $request->trip_type === 'round_trip') {
                $returnFlight = Flight::with('airplane.seats')->findOrFail($request->return_flight_id);
            }

            $passengerCount = max(1, (int) $request->passenger_count);

            if (count($request->passengers) !== $passengerCount) {
                return back()->with('error', 'Jumlah data penumpang harus sesuai jumlah penumpang yang dipilih.')->withInput();
            }
            $classPrice = $flightClass->price;
            $seatNumbers = $request->seat_numbers;

            // Verify seat count matches
            if (count($seatNumbers) !== $passengerCount) {
                return back()->with('error', 'Jumlah kursi tidak sesuai dengan jumlah penumpang.');
            }

            // Verify seats are still available
            $bookedSeatNumbers = Passenger::whereHas('booking', function ($query) use ($flight) {
                $query->where('flight_id', $flight->id)
                      ->where('status', '!=', 'cancelled');
            })->whereNotNull('seat_number')
              ->whereIn('seat_number', $seatNumbers)
              ->pluck('seat_number')
              ->toArray();

            if (!empty($bookedSeatNumbers)) {
                return back()->with('error', 'Kursi ' . implode(', ', $bookedSeatNumbers) . ' sudah dipesan. Silakan booking ulang.')
                    ->withInput();
            }

            // Check available quota
            if ($flightClass->available_quota < $passengerCount) {
                return back()->with('error', 'Kuota kelas ' . str_replace('_', ' ', $flightClass->class_name) . ' tidak mencukupi!');
            }

            // Calculate total using class price
            $basePrice = $classPrice * $passengerCount;
            if ($returnFlight) {
                $returnClassPrice = $returnFlight->flightClasses()
                    ->where('class_name', $request->travel_class ?? 'economy')
                    ->first()?->price ?? 0;
                $basePrice += $returnClassPrice * $passengerCount;
            }
$convenienceFee = config('booking.convenience_fee', 25000);

$taxAmount = 0;

$totalPrice = $basePrice + $convenienceFee;

            DB::beginTransaction();

            $booking = Booking::create([
                'user_id' => Auth::id(),
                'flight_id' => $flight->id,
                'flight_class_id' => $flightClass->id,
                'return_flight_id' => $returnFlight ? $returnFlight->id : null,
                'booking_code' => 'BK-' . strtoupper(Str::random(8)),
                'total_passengers' => $passengerCount,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'trip_type' => $request->trip_type ?? 'one_way',
                'travel_class' => $flightClass->class_name,
                'return_date' => $request->trip_type === 'round_trip' ? $request->return_date : null,
                'convenience_fee' => $convenienceFee,
                'tax_amount' => $taxAmount,
                'points_earned' => floor($totalPrice / 1000),
            ]);

            // Simpan passenger dengan seat_number yang sudah dipilih
            foreach ($request->passengers as $index => $passenger) {
                $seatNumber = $seatNumbers[$index] ?? null;
                Passenger::create([
                    'booking_id' => $booking->id,
                    'title' => $passenger['title'],
                    'full_name' => $passenger['full_name'],
                    'gender' => $passenger['gender'],
                    'birth_date' => $passenger['birth_date'],
                    'passport_number' => $passenger['passport_number'],
                    'phone' => $passenger['phone'] ?? $request->booker_phone,
                    'email' => $passenger['email'] ?? $request->booker_email,
                    'seat_number' => $seatNumber,
                    'travel_class' => $flightClass->class_name,
                    'status' => 'waiting',
                ]);
            }


            // Clear session seat data
            session()->forget('booking_seats_' . $flight->id);

            DB::commit();

            // Create Midtrans payment
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$clientKey = config('services.midtrans.client_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $transactionDetails = [
                'order_id' => $booking->booking_code,
                'gross_amount' => (int) round($booking->grand_total),
            ];

            $customerDetails = [
                'first_name' => $request->booker_name,
                'email' => $request->booker_email,
                'phone' => $request->booker_phone,
            ];

            $itemDetails = [
                [
                    'id' => $flight->flight_number,
                    'price' => (int) round($classPrice),
                    'quantity' => $passengerCount,
                    'name' => '[' . strtoupper($flightClass->class_name) . '] ' . $flight->airline->name . ' (' . $flight->departureAirport->iata_code . '-' . $flight->arrivalAirport->iata_code . ')',
                ],
            ];

            if ($returnFlight) {
                $returnClassPrice = $returnFlight->flightClasses()
                    ->where('class_name', $request->travel_class ?? 'economy')
                    ->first()?->price ?? 0;
                $itemDetails[] = [
                    'id' => $returnFlight->flight_number,
                    'price' => (int) round($returnClassPrice),
                    'quantity' => $passengerCount,
                    'name' => 'Pulang: ' . $returnFlight->airline->name . ' (' . $returnFlight->departureAirport->iata_code . '-' . $returnFlight->arrivalAirport->iata_code . ')',
                ];
            }

            $itemDetails[] = [
                'id' => 'conv_fee',
                'price' => (int) $convenienceFee,
                'quantity' => 1,
                'name' => 'Convenience Fee',
            ];

            // PPN 11% sudah termasuk dalam harga tiket, tidak ditambahkan ke total

            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                'notification_url' => route('midtrans.notification'),
            ];

            try {
                $snapToken = Snap::getSnapToken($params);
            } catch (\Exception $e) {
                DB::rollBack();
                $errorMsg = $e->getMessage();
                
                if (str_contains($errorMsg, 'Could not resolve host') || 
                    str_contains($errorMsg, 'ssl connect error') ||
                    str_contains($errorMsg, 'SSL certificate problem') ||
                    str_contains($errorMsg, 'Operation timed out') ||
                    str_contains($errorMsg, 'couldn\'t connect to host')) {
                    Log::error('Midtrans Connection Error: ' . $errorMsg);
                    return back()->with('error', 'Gagal terhubung ke Midtrans. Periksa koneksi internet atau konfigurasi server.')->withInput();
                }
                
                Log::error('Midtrans Snap Error: ' . $errorMsg);
                return back()->with('error', 'Gagal memproses pembayaran: ' . $errorMsg)->withInput();
            }

            // Create payment record
            Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => 'midtrans',
                'amount' => $booking->grand_total,
                'payment_status' => 'pending',
                'payment_gateway' => 'Midtrans',
                'transaction_code' => $snapToken,
                'expired_at' => now()->addHours(24),
            ]);

            return redirect()->route('customer.booking.payment', ['booking' => $booking, 'snap_token' => $snapToken]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function index()
    {
        // Sync pending bookings first so they get the latest state from Midtrans
        $pendingBookings = Booking::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->get();

        foreach ($pendingBookings as $booking) {
            try {
                app(\App\Services\MidtransService::class)->syncBookingPayment($booking);
            } catch (\Exception $e) {
                \Log::warning("Live sync failed for booking {$booking->booking_code} during list load: " . $e->getMessage());
            }
        }

        // Ambil SEMUA booking milik user tanpa filter status
        $bookings = Booking::where('user_id', Auth::id())
            ->with([
                'flight.airline', 
                'flight.departureAirport', 
                'flight.arrivalAirport', 
                'flight.airplane',
                'flightClass',
                'payment',
                'passengers',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.bookings', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status === 'pending' || request()->has('payment')) {
            try {
                app(\App\Services\MidtransService::class)->syncBookingPayment($booking);
                $booking = $booking->fresh();
            } catch (\Exception $e) {
                \Log::warning("Live sync failed for booking {$booking->booking_code} during show: " . $e->getMessage());
            }
        }

        $booking->load(['flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'flight.airplane', 'flightClass', 'passengers', 'payment']);

        return view('customer.booking-show', compact('booking'));
    }

    public function eTicket(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status === 'pending') {
            try {
                app(\App\Services\MidtransService::class)->syncBookingPayment($booking);
                $booking = $booking->fresh();
            } catch (\Exception $e) {
                \Log::warning("Live sync failed for booking {$booking->booking_code} during eticket load: " . $e->getMessage());
            }
        }

        $booking->load(['flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'flight.airplane', 'flightClass', 'passengers', 'payment']);

        return view('customer.e-ticket', compact('booking'));
    }
}