<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\InsuranceOption;
use App\Models\BookingExtra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function create(Flight $flight)
    {
        $flight->load(['airline', 'departureAirport', 'arrivalAirport']);
        $insuranceOptions = InsuranceOption::where('is_active', true)->get();
        
        $returnFlight = null;
        if (request()->has('return_flight_id')) {
            $returnFlight = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])->find(request('return_flight_id'));
        }
        
        return view('customer.booking-create', compact('flight', 'returnFlight', 'insuranceOptions'));
    }

    public function store(Request $request)
    {
        try {
            // Validasi
            $request->validate([
                'flight_id' => 'required|exists:flights,id',
                'return_flight_id' => 'nullable|exists:flights,id',
                'booker_title' => 'required|in:Mr,Mrs,Ms',
                'booker_name' => 'required|string|max:255',
                'booker_phone' => 'required|string|max:20',
                'booker_email' => 'required|email|max:255',
                'trip_type' => 'nullable|in:one_way,round_trip',
                'travel_class' => 'nullable|in:' . implode(',', array_keys(config('travel_class'))),
                'return_date' => 'nullable|date',
                'passengers' => 'required|array|min:1',
                'passengers.*.title' => 'required|in:Mr,Mrs,Ms',
                'passengers.*.full_name' => 'required|string|max:255',
                'passengers.*.gender' => 'required|in:male,female',
                'passengers.*.birth_date' => 'required|date',
                'passengers.*.passport_number' => 'required|string|max:255',
            ]);

            $flight = Flight::findOrFail($request->flight_id);
            $returnFlight = null;
            if ($request->return_flight_id && $request->trip_type === 'round_trip') {
                $returnFlight = Flight::findOrFail($request->return_flight_id);
            }

            $travelClass = $request->travel_class ?? 'economy';
            $multiplier = config('travel_class.' . $travelClass . '.multiplier', 1);
            $passengerCount = count($request->passengers);

            if ($flight->available_seats < $passengerCount) {
                return back()->with('error', 'Kursi penerbangan pergi tidak mencukupi!');
            }
            if ($returnFlight && $returnFlight->available_seats < $passengerCount) {
                return back()->with('error', 'Kursi penerbangan pulang tidak mencukupi!');
            }

            $basePrice = $flight->price * $multiplier * $passengerCount;
            if ($returnFlight) {
                $basePrice += $returnFlight->price * $multiplier * $passengerCount;
            }
            $convenienceFee = 25000;
            $taxAmount = $basePrice * 0.11;
            $totalPrice = $basePrice + $convenienceFee + $taxAmount;

            $booking = Booking::create([
                'user_id' => Auth::id(),
                'flight_id' => $flight->id,
                'return_flight_id' => $returnFlight ? $returnFlight->id : null,
                'booking_code' => 'BK-' . strtoupper(Str::random(8)),
                'total_passengers' => $passengerCount,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'trip_type' => $request->trip_type ?? 'one_way',
                'travel_class' => $travelClass,
                'return_date' => $request->trip_type === 'round_trip' ? $request->return_date : null,
                'convenience_fee' => $convenienceFee,
                'tax_amount' => $taxAmount,
                'points_earned' => floor($totalPrice / 1000),
            ]);

            foreach ($request->passengers as $passenger) {
                Passenger::create([
                    'booking_id' => $booking->id,
                    'title' => $passenger['title'],
                    'full_name' => $passenger['full_name'],
                    'gender' => $passenger['gender'],
                    'birth_date' => $passenger['birth_date'],
                    'passport_number' => $passenger['passport_number'],
                    'phone' => $passenger['phone'] ?? $request->booker_phone,
                    'email' => $passenger['email'] ?? $request->booker_email,
                    'seat_number' => null,
                    'travel_class' => $travelClass,
                ]);
            }

            $flight->decrement('available_seats', $passengerCount);
            if ($returnFlight) {
                $returnFlight->decrement('available_seats', $passengerCount);
            }

            // CREATE MIDTRANS TRANSACTION
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$clientKey = config('services.midtrans.client_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $transactionDetails = [
                'order_id' => $booking->booking_code,
                'gross_amount' => (int) round($totalPrice),
            ];

            $customerDetails = [
                'first_name' => $request->booker_name,
                'email' => $request->booker_email,
                'phone' => $request->booker_phone,
            ];

            $itemDetails = [
                [
                    'id' => $flight->flight_number,
                    'price' => (int) round($flight->price * $multiplier),
                    'quantity' => $passengerCount,
                    'name' => 'Pergi: ' . $flight->airline->name . ' (' . $flight->departureAirport->iata_code . '-' . $flight->arrivalAirport->iata_code . ')',
                ],
            ];

            if ($returnFlight) {
                $itemDetails[] = [
                    'id' => $returnFlight->flight_number,
                    'price' => (int) round($returnFlight->price * $multiplier),
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

            $itemDetails[] = [
                'id' => 'tax',
                'price' => (int) round($taxAmount),
                'quantity' => 1,
                'name' => 'Pajak (11%)',
            ];

            $params = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                'notification_url' => route('midtrans.notification'),
            ];

            $snapToken = Snap::getSnapToken($params);

            // Create payment record
            Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => 'midtrans',
                'amount' => $totalPrice,
                'payment_status' => 'pending',
                'payment_gateway' => 'Midtrans',
                'transaction_code' => $snapToken,
                'expired_at' => now()->addHours(24),
            ]);

            // Redirect ke halaman booking dengan snap token
            return redirect()->route('customer.booking.payment', ['booking' => $booking, 'snap_token' => $snapToken]);

        } catch (\Exception $e) {
            Log::error('Booking Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'payment'])
            ->latest()
            ->paginate(10);

        return view('customer.bookings', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if (request()->has('payment')) {
            app(\App\Services\MidtransService::class)->syncBookingPayment($booking);
            $booking->refresh();
        }

        $booking->load(['flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'flight.airplane', 'passengers', 'payment']);

        return view('customer.booking-show', compact('booking'));
    }

    public function eTicket(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'flight.airplane', 'passengers', 'payment']);

        return view('customer.e-ticket', compact('booking'));
    }
}