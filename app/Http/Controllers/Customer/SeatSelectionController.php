<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\Passenger;
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

        $booking->load(['flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'passengers']);

        $airplaneId = $booking->flight->airplane_id;
        
        // Get all physical seats config for this airplane
        $seats = Seat::where('airplane_id', $airplaneId)
            ->orderByRaw("CASE 
                WHEN class = 'first' THEN 1
                WHEN class = 'business' THEN 2
                WHEN class = 'premium_economy' THEN 3
                WHEN class = 'economy' THEN 4
            END")
            ->orderBy('seat_number')
            ->get();

        // Get all seat numbers already booked for this flight
        $bookedSeats = Passenger::whereHas('booking', function ($query) use ($booking) {
            $query->where('flight_id', $booking->flight_id)
                  ->where('status', '!=', 'cancelled');
        })->whereNotNull('seat_number')->pluck('seat_number')->toArray();

        // Determine availability dynamically based on passenger bookings
        foreach ($seats as $seat) {
            if (in_array($seat->seat_number, $bookedSeats)) {
                $seat->status = 'booked';
            } else {
                $seat->status = 'available';
            }
        }

        // Group by class to match what the blade view expects
        $groupedSeats = $seats->groupBy('class');

        return view('customer.seat-selection', [
            'booking' => $booking,
            'seats' => $groupedSeats,
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