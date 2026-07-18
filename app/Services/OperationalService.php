<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Passenger;
use App\Models\Flight;
use Carbon\Carbon;

class OperationalService
{
    /**
     * Proses No Show otomatis untuk passenger yang belum check-in
     * setelah waktu keberangkatan lewat.
     */
    public function processNoShow(): void
    {
        // Beri jeda toleransi 30 menit setelah jadwal keberangkatan sebelum
        // menandai penumpang sebagai no_show. Ini adalah pengaman tambahan
        // terhadap selisih jam antara app server dan DB server / perbedaan
        // konfigurasi timezone antar environment, di luar perbaikan timezone
        // di config/app.php.
        $cutoff = now()->subMinutes(30);

        // Cari passenger dengan status 'waiting' yang flightnya sudah berangkat
        $expiredPassengers = Passenger::where('status', 'waiting')
            ->whereHas('booking.flight', function ($q) use ($cutoff) {
                $q->where('departure_time', '<', $cutoff);
            })
            ->where(function ($q) {
                $q->whereNull('has_checked_in')
                  ->orWhere('has_checked_in', false);
            })
            ->get();

        foreach ($expiredPassengers as $passenger) {
            $passenger->update([
                'status' => 'no_show',
            ]);

            \App\Models\ActivityLog::log('no_show', 'Penumpang ' . $passenger->full_name . ' otomatis No Show (tidak check-in)');

            // Sync booking status after marking passenger no_show
            try {
                $this->syncBookingStatus($passenger->booking);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to sync booking after no_show for passenger ' . $passenger->id . ': ' . $e->getMessage());
            }
        }
    }

    /**
     * Update status booking berdasarkan status passenger.
     * Sinkronisasi: booking.status MENGIKUTI passenger.status tertinggi.
     */
    public function syncBookingStatus(Booking $booking): void
    {
        // JANGAN sync jika status booking adalah pending atau cancelled
        if (in_array($booking->status, ['pending', 'cancelled'])) {
            return;
        }

        $passengers = $booking->passengers;
        $totalPassengers = $passengers->count();

        if ($totalPassengers === 0) return;

        $waitingCount = $passengers->where('status', 'waiting')->count();
        $checkedInCount = $passengers->where('status', 'checked_in')->count();
        $boardedCount = $passengers->where('status', 'boarded')->count();
        $completedCount = $passengers->where('status', 'completed')->count();
        $noShowCount = $passengers->where('status', 'no_show')->count();

        // 1. Semua completed / no_show -> completed
        if ($completedCount + $noShowCount === $totalPassengers) {
            $booking->update(['status' => 'completed']);
            return;
        }

        // 2. Semua boarded (atau mixed boarded/completed/no_show) -> in_progress
        if ($boardedCount + $completedCount + $noShowCount === $totalPassengers) {
            $booking->update(['status' => 'in_progress']);
            return;
        }

        // 3. Ada boarded -> boarded
        if ($boardedCount > 0) {
            $booking->update(['status' => 'boarded']);
            return;
        }

        // 4. Ada checked_in -> checked_in
        if ($checkedInCount > 0) {
            $booking->update(['status' => 'checked_in']);
            return;
        }

        // 5. Semua waiting -> confirmed
        if ($waitingCount === $totalPassengers) {
            $booking->update(['status' => 'confirmed']);
            return;
        }
    }

    /**
     * Proses semua booking yang memiliki passenger untuk sinkronisasi status.
     */
    public function syncAllBookingStatuses(): void
    {
        $bookings = Booking::whereIn('status', ['confirmed', 'checked_in', 'boarded', 'in_progress'])->get();
        foreach ($bookings as $booking) {
            $this->syncBookingStatus($booking);
        }
    }

    /**
     * Run all operational processes (called on dashboard load or via scheduler).
     */
    public function run(): void
    {
        $this->processNoShow();
        $this->syncAllBookingStatuses();
    }

    /**
     * Check in passenger.
     */
    public function checkIn(Passenger $passenger): void
    {
        $passenger->update([
            'has_checked_in' => true,
            'checked_in_at' => now(),
            'status' => 'checked_in',
        ]);

        \App\Models\ActivityLog::log('check_in', 'Check-in penumpang: ' . $passenger->full_name);
        
        // Sync booking status after passenger checked in
        try {
            $this->syncBookingStatus($passenger->booking);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to sync booking after check-in for passenger ' . $passenger->id . ': ' . $e->getMessage());
        }
    }

    /**
     * Board passenger.
     */
    public function board(Passenger $passenger): void
    {
        $passenger->update([
            'has_boarded' => true,
            'boarded_at' => now(),
            'status' => 'boarded',
        ]);

        // Sync booking status
        $this->syncBookingStatus($passenger->booking);

        \App\Models\ActivityLog::log('board', 'Penumpang boarding: ' . $passenger->full_name);
    }

    /**
     * Check out passenger (arrival completed).
     */
    public function checkOut(Passenger $passenger): void
    {
        $passenger->update([
            'checked_out_at' => now(),
            'status' => 'completed',
        ]);

        // Sync booking status
        $this->syncBookingStatus($passenger->booking);

        \App\Models\ActivityLog::log('check_out', 'Check-out penumpang: ' . $passenger->full_name);
    }
}