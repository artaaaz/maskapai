<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Seat;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

#[Signature('bookings:cancel-expired')]
#[Description('Cancel pending bookings whose payment window has expired and release their seats')]
class CancelExpiredBookings extends Command
{
    /**
     * Execute the console command.
     *
     * Hanya menyentuh booking yang:
     * - status masih 'pending' (belum pernah paid)
     * - payment.expired_at sudah lewat
     * Booking yang sudah paid/confirmed/checked_in/dst TIDAK PERNAH disentuh di sini.
     */
    public function handle()
    {
        $expiredBookings = Booking::where('status', 'pending')
            ->whereHas('payment', function ($q) {
                $q->where('payment_status', 'pending')
                    ->whereNotNull('expired_at')
                    ->where('expired_at', '<', now());
            })
            ->with(['payment', 'passengers', 'flight'])
            ->get();

        $count = 0;

        foreach ($expiredBookings as $booking) {
            // Guard tambahan: pastikan tidak menyentuh booking yang ternyata sudah paid
            // (mis. race condition dengan webhook Midtrans yang baru masuk).
            if ($booking->payment && $booking->payment->payment_status === 'paid') {
                continue;
            }

            $booking->update(['status' => 'cancelled']);
            $booking->payment?->updateStatusSafely('failed');

            // Release seats back to available
            $bookedSeatNumbers = $booking->passengers()
                ->whereNotNull('seat_number')
                ->pluck('seat_number')
                ->toArray();

            if (!empty($bookedSeatNumbers) && $booking->flight) {
                Seat::where('airplane_id', $booking->flight->airplane_id)
                    ->whereIn('seat_number', $bookedSeatNumbers)
                    ->update([
                        'status' => 'available',
                        'booking_id' => null,
                    ]);
            }

            \App\Models\ActivityLog::log('booking_expired', 'Booking ' . $booking->booking_code . ' otomatis dibatalkan karena melewati batas waktu pembayaran.');

            $count++;
        }

        Log::info("CancelExpiredBookings: {$count} booking dibatalkan otomatis.");
        $this->info("{$count} expired booking(s) cancelled.");
    }
}
