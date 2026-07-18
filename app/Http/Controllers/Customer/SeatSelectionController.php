<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\Passenger;
use App\Services\SeatLayoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SeatSelectionController extends Controller
{
    public function select(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['flight.airline', 'flight.airplane', 'flight.departureAirport', 'flight.arrivalAirport', 'passengers', 'flightClass']);

        $airplaneId = $booking->flight->airplane_id;
        $selectedClassName = $booking->flightClass?->class_name ?? 'economy';
        $seatQuota = $booking->flightClass?->seat_quota ?? 0;

        // BUG 2 FIX: dulu jika tabel seats kosong (pesawat lama/tanpa layout),
        // query di bawah selalu mengembalikan collection kosong dan customer
        // melihat "Tidak ada kursi tersedia" walau seat_quota > 0. Sekarang kita
        // pastikan dulu kursi untuk class ini cukup (auto-generate jika kurang)
        // sebelum diquery, tanpa mengubah struktur tabel manapun.
        SeatLayoutService::ensureSeatsForClass($booking->flight->airplane, $selectedClassName, $seatQuota);

        // Get seats for the airplane filtered by class
        // Tabel seats = layout pesawat (template)
        // Jumlah yang ditampilkan = seat_quota dari flight_class
        $seats = Seat::where('airplane_id', $airplaneId)
            ->whereRaw('LOWER(`class`) = ?', [strtolower($selectedClassName)])
            ->orderBy('seat_number')
            ->take($seatQuota)
            ->get();

        // Get all seat numbers already booked for this flight (excluding this booking)
        $bookedSeats = Passenger::whereHas('booking', function ($query) use ($booking) {
            $query->where('flight_id', $booking->flight_id)
                  ->where('status', '!=', 'cancelled')
                  ->where('id', '!=', $booking->id);
        })->whereNotNull('seat_number')->pluck('seat_number')->toArray();

        // Also get seat_reservations for real-time blocking via seat_id -> seats.seat_number
        $reservedSeats = \App\Models\SeatReservation::where('seat_reservations.flight_id', $booking->flight_id)
            ->where('seat_reservations.flight_class_id', $booking->flight_class_id)
            ->whereIn('seat_reservations.status', ['reserved', 'paid'])
            ->where(function ($q) use ($booking) {
                $q->where('seat_reservations.booking_id', '!=', $booking->id)
                  ->orWhereNull('seat_reservations.booking_id');
            })
            ->join('seats', 'seats.id', '=', 'seat_reservations.seat_id')
            ->pluck('seats.seat_number')
            ->toArray();

        $bookedSeats = array_unique(array_merge($bookedSeats, $reservedSeats));

        // Determine availability dynamically
        foreach ($seats as $seat) {
            if (in_array($seat->seat_number, $bookedSeats)) {
                $seat->status = 'booked';
            } else {
                $seat->status = 'available';
            }
        }

        // Group by row for the view
        $groupedSeats = $seats->groupBy(function ($seat) {
            preg_match('/^(\d+)/', $seat->seat_number, $matches);
            return $matches[1] ?? '0';
        })->sortKeys();

        return view('customer.seat-selection', [
            'booking' => $booking,
            'seats' => $groupedSeats,
            'selectedClassName' => $selectedClassName,
        ]);
    }

    public function store(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'seats' => 'required', // JSON array of selected seat IDs
        ]);

        $seatIds = json_decode($request->seats, true);

        if (!is_array($seatIds) || empty($seatIds)) {
            return back()->with('error', 'Silakan pilih kursi terlebih dahulu.');
        }

        $passengers = $booking->passengers;
        if (count($seatIds) !== $passengers->count()) {
            return back()->with('error', 'Jumlah kursi terpilih (' . count($seatIds) . ') harus sama dengan jumlah penumpang (' . $passengers->count() . ').');
        }

        DB::beginTransaction();
        
        try {
            // Retrieve physical seats matching airplane
            $seats = Seat::whereIn('id', $seatIds)
                ->where('airplane_id', $booking->flight->airplane_id)
                ->get();

            if ($seats->count() !== count($seatIds)) {
                throw new \Exception("Beberapa kursi tidak valid.");
            }

            // Check if any of these seat numbers are already booked for this flight by other bookings
            $bookedSeatNumbers = Passenger::whereHas('booking', function ($query) use ($booking) {
                $query->where('flight_id', $booking->flight_id)
                      ->where('status', '!=', 'cancelled')
                      ->where('id', '!=', $booking->id);
            })->whereNotNull('seat_number')
              ->whereIn('seat_number', $seats->pluck('seat_number'))
              ->pluck('seat_number')
              ->toArray();

            if (!empty($bookedSeatNumbers)) {
                throw new \Exception("Kursi " . implode(', ', $bookedSeatNumbers) . " sudah dipesan oleh penumpang lain.");
            }

            // Assign each seat to a passenger
            $bookedSeatsList = [];
            foreach ($passengers as $index => $passenger) {
                $seat = $seats[$index];
                $passenger->update([
                    'seat_number' => $seat->seat_number,
                ]);
                $bookedSeatsList[] = $seat->seat_number;
            }

            DB::commit();

            return redirect()->route('customer.booking.show', $booking)
                ->with('success', 'Kursi berhasil dipilih: ' . implode(', ', $bookedSeatsList));
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function getAvailableSeats(Flight $flight)
    {
        $airplaneId = $flight->airplane_id;

        // BUG 2 FIX: pastikan kursi untuk tiap kelas penerbangan ini sudah cukup
        // sebelum diambil, supaya endpoint ini tidak pernah mengembalikan array
        // kosong hanya karena tabel seats belum pernah diisi untuk pesawat ini.
        foreach ($flight->flightClasses as $flightClass) {
            SeatLayoutService::ensureSeatsForClass($flight->airplane, $flightClass->class_name, $flightClass->seat_quota);
        }

        // Get all physical seats
        $seats = Seat::where('airplane_id', $airplaneId)
            ->select('id', 'seat_number', 'class')
            ->get();

        // Get booked seat numbers for this flight
        $bookedSeats = Passenger::whereHas('booking', function ($query) use ($flight) {
            $query->where('flight_id', $flight->id)
                  ->where('status', '!=', 'cancelled');
        })->whereNotNull('seat_number')->pluck('seat_number')->toArray();

        foreach ($seats as $seat) {
            $seat->status = in_array($seat->seat_number, $bookedSeats) ? 'booked' : 'available';
        }

        return response()->json([
            'seats' => $seats,
        ]);
    }
}