<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with([
            'user',
            'flight.airline',
            'flight.departureAirport',
            'flight.arrivalAirport',
            'passengers',
            'payment'
        ])
            ->latest()
            ->paginate(20);

        return view('staff.bookings', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load([
            'user',
            'flight.airline',
            'flight.airplane',
            'flight.departureAirport',
            'flight.arrivalAirport',
            'passengers',
            'payment'
        ]);
        return view('staff.booking-show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:paid,confirmed,checked_in,boarded,in_progress,completed,cancelled',
        ]);

        $oldStatus = $booking->status;
        $newStatus = $request->status;

        // Check if transition is valid using model's guard
        if (!Booking::isValidTransition($oldStatus, $newStatus)) {
            return back()->with('error', 'Transisi status tidak valid dari ' . $oldStatus . ' ke ' . $newStatus);
        }

        // Additional business rule check: cannot cancel manually if paid or confirmed
        if ($newStatus === 'cancelled' && in_array($oldStatus, ['paid', 'confirmed'])) {
            return back()->with('error', 'Tidak dapat membatalkan booking yang sudah paid atau confirmed.');
        }

        // Update booking status
        $booking->update(['status' => $newStatus]);

        // Sync payment status if changed to paid/confirmed
        if (in_array($newStatus, ['paid', 'confirmed'])) {
            if ($booking->payment) {
                $booking->payment->updateStatusSafely('paid', true);
            } else {
                Payment::create([
                    'booking_id' => $booking->id,
                    'payment_method' => 'manual',
                    'amount' => $booking->total_price,
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                ]);
            }
        }

        if ($newStatus === 'cancelled') {
            if ($booking->payment) {
                $booking->payment->updateStatusSafely('failed');
            }
            $booking->flight->increment('available_seats', $booking->total_passengers);

            // Release seats back to available
            $bookedSeatNumbers = $booking->passengers()
                ->whereNotNull('seat_number')
                ->pluck('seat_number')
                ->toArray();

            if (!empty($bookedSeatNumbers) && $booking->flight) {
                \App\Models\Seat::where('airplane_id', $booking->flight->airplane_id)
                    ->whereIn('seat_number', $bookedSeatNumbers)
                    ->update([
                        'status' => 'available',
                        'booking_id' => null,
                    ]);
            }
        }

        ActivityLog::log('booking_status', 'Status booking ' . $booking->booking_code . ' diubah menjadi ' . $newStatus);

        return back()->with('success', 'Status booking berhasil diupdate!');
    }

    /**
     * Verify payment - set payment status to paid and booking to confirmed
     */
    public function verifyPayment(Booking $booking)
    {
        if ($booking->payment) {
            $booking->payment->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);
        } else {
            // Create payment record if not exist
            Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => 'manual',
                'amount' => $booking->total_price,
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);
        }

        // Langsung ke 'confirmed' dalam satu update (pending -> confirmed valid
        // per Booking::isValidTransition), menghindari state transisi 'paid' yang
        // tidak perlu.
        $booking->update([
            'status' => 'confirmed',
        ]);

        ActivityLog::log('payment_verify', 'Pembayaran booking ' . $booking->booking_code . ' diverifikasi oleh staff');

        return back()->with('success', 'Pembayaran booking ' . $booking->booking_code . ' berhasil diverifikasi!');
    }
}