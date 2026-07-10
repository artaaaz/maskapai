<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'flight.departureAirport', 'flight.arrivalAirport', 'flight.airline', 'passengers'])
            ->latest()
            ->paginate(20);

        return view('staff.bookings', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'flight.departureAirport', 'flight.arrivalAirport', 'flight.airline', 'passengers']);
        return view('staff.booking-show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:confirmed,cancelled',
        ]);

        $booking->update(['status' => $request->status]);

        if ($request->status === 'cancelled') {
            $booking->flight->increment('available_seats', $booking->total_passengers);
        }

        return back()->with('success', 'Status booking berhasil diupdate!');
    }
}
