<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Promo;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct(private MidtransService $midtrans)
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        if ($booking->payment && $booking->payment->payment_status === 'paid') {
            return redirect()->route('customer.booking.show', $booking)
                ->with('info', 'Pembayaran sudah dilakukan');
        }

        $booking->load(['flight.departureAirport', 'flight.arrivalAirport', 'flight.airline', 'returnFlight.departureAirport', 'returnFlight.arrivalAirport', 'returnFlight.airline', 'passengers', 'payment']);

        $promos = Promo::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->get();

        return view('customer.payment', compact('booking', 'promos'));
    }

    public function process(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        try {
            $request->validate([
                'payment_method' => 'required',
            ]);

            // GUNAKAN GRAND TOTAL dari model Booking (sudah include: harga tiket + convenience fee + pajak + asuransi - diskon)
            $booking->loadMissing(['extras', 'flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'returnFlight.airline', 'returnFlight.departureAirport', 'returnFlight.arrivalAirport']);
            $finalAmount = $booking->grand_total;
            $vaNumber = config('booking.va_prefix', '7800113') . str_pad($booking->id, 8, '0', STR_PAD_LEFT);

            $transactionDetails = [
                'order_id' => $booking->booking_code,
                'gross_amount' => (int) round($finalAmount),
            ];

            $customerDetails = [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone ?? config('booking.fallback_phone', '08123456789'),
            ];

            // Gunakan harga dari flight_classes, bukan flights.price
            $flightClassPrice = $booking->flightClass ? $booking->flightClass->price : 0;
            
            $itemDetails = [
                [
                    'id' => $booking->flight->flight_number,
                    'price' => (int) round($flightClassPrice),
                    'quantity' => $booking->total_passengers,
                    'name' => 'Pergi: ' . $booking->flight->airline->name . ' (' . $booking->flight->flight_number . ') ' . $booking->flight->departureAirport->iata_code . '-' . $booking->flight->arrivalAirport->iata_code,
                ],
            ];

            if ($booking->returnFlight) {
                $returnClassPrice = $booking->returnFlight->flightClasses()
                    ->where('class_name', $booking->travel_class ?? 'economy')
                    ->first()?->price ?? 0;
                $itemDetails[] = [
                    'id' => $booking->returnFlight->flight_number,
                    'price' => (int) round($returnClassPrice),
                    'quantity' => $booking->total_passengers,
                    'name' => 'Pulang: ' . $booking->returnFlight->airline->name . ' (' . $booking->returnFlight->flight_number . ')',
                ];
            }

            $itemDetails[] = [
                'id' => 'conv_fee',
                'price' => (int) round($booking->convenience_fee),
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

        \Log::info('Midtrans Params', $params);

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            
            // Detect DNS/connection errors
            if (str_contains($errorMsg, 'Could not resolve host') || 
                str_contains($errorMsg, 'ssl connect error') ||
                str_contains($errorMsg, 'SSL certificate problem') ||
                str_contains($errorMsg, 'Operation timed out') ||
                str_contains($errorMsg, 'couldn\'t connect to host')) {
                
                \Log::error('Midtrans Connection Error: ' . $errorMsg, [
                    'booking_id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal terhubung ke Midtrans. Periksa koneksi internet atau konfigurasi server.',
                ], 502);
            }
            
            \Log::error('Midtrans Snap Error: ' . $errorMsg, [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran: ' . $errorMsg,
            ], 500);
        }

            Payment::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'payment_method' => $request->payment_method,
                    'amount' => $finalAmount,
                    'payment_status' => 'pending',
                    'payment_gateway' => $this->getGatewayName($request->payment_method),
                    'virtual_account_number' => in_array($request->payment_method, ['bca_va', 'mandiri_va', 'bni_va', 'bri_va']) ? $vaNumber : null,
                    'transaction_code' => $snapToken,
                    'expired_at' => now()->addHours(24),
                ]
            );

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'va_number' => $vaNumber,
                'amount' => $finalAmount,
                'payment_method' => $request->payment_method,
            ]);
        } catch (\Exception $e) {
            \Log::error('Payment Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function verify(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$booking->payment) {
            return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
        }

        $result = $this->midtrans->syncBookingPayment($booking);
        $booking->refresh();

        if ($booking->payment->payment_status === 'paid') {
            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran berhasil',
                'booking_status' => $booking->status,
            ]);
        }

        return response()->json([
            'status' => 'pending',
            'message' => 'Menunggu pembayaran',
            'sync' => $result,
        ]);
    }

    public function finish(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $this->midtrans->syncBookingPayment($booking);
        $booking->refresh();

        $message = $booking->payment?->payment_status === 'paid'
            ? 'Pembayaran berhasil dikonfirmasi!'
            : 'Status pembayaran sedang diproses.';

        return redirect()->route('customer.booking.show', $booking)->with('success', $message);
    }

    public function notification(Request $request)
    {
        \Log::info('Midtrans Notification Received', [
            'data' => $request->all(),
        ]);

        try {
            $result = $this->midtrans->handleNotification();

            if (!$result['success']) {
                return response()->json(['status' => 'error', 'message' => $result['message']], 404);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \Log::error('Midtrans Notification Error: ' . $e->getMessage());

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    private function getGatewayName($method): string
    {
        return match ($method) {
            'bca_va' => 'BCA Virtual Account',
            'mandiri_va' => 'Mandiri Virtual Account',
            'bni_va' => 'BNI Virtual Account',
            'bri_va' => 'BRI Virtual Account',
            'credit_card' => 'Credit/Debit Card',
            'dana' => 'DANA',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            default => $method,
        };
    }
}
