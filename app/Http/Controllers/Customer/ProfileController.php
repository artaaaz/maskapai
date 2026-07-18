<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Sync pending bookings first so that profile loads showing the latest payment status
        $pendingBookings = \App\Models\Booking::where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();

        foreach ($pendingBookings as $booking) {
            try {
                app(\App\Services\MidtransService::class)->syncBookingPayment($booking);
            } catch (\Exception $e) {
                \Log::warning("Live sync failed for booking {$booking->booking_code} during profile load: " . $e->getMessage());
            }
        }
        
        // Ambil semua booking milik customer, diurutkan terbaru
        $allBookings = \App\Models\Booking::with([
            'flight.airline',
            'flight.departureAirport',
            'flight.arrivalAirport',
            'payment',
            'passengers',
        ])
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        
        // Upcoming trips: booking dengan status aktif dan jadwal belum lewat
        $upcomingBookings = \App\Models\Booking::with([
            'flight.airline',
            'flight.departureAirport',
            'flight.arrivalAirport',
            'payment',
        ])
        ->where('user_id', $user->id)
        // Include in_progress and completed so bookings remain visible
        // after all passengers have boarded (status may transition to in_progress).
        ->whereIn('status', ['paid', 'confirmed', 'checked_in', 'boarded', 'in_progress', 'completed'])
        ->whereHas('flight', fn($q) => $q->where('departure_time', '>=', now()->subHours(2)))
        ->orderBy('created_at', 'desc')
        ->get();
        
        // Statistik
        $totalBookings = \App\Models\Booking::where('user_id', $user->id)->count();
        $confirmedTrips = \App\Models\Booking::where('user_id', $user->id)
            ->whereIn('status', ['paid', 'confirmed', 'checked_in', 'boarded', 'completed'])
            ->count();
        $totalSpent = \App\Models\Booking::where('user_id', $user->id)
            ->whereHas('payment', fn($q) => $q->where('payment_status', 'paid'))
            ->sum('total_price');
        
        return view('customer.profile', compact(
            'allBookings',
            'upcomingBookings',
            'totalBookings',
            'confirmedTrips',
            'totalSpent'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();

        return back()->with('success', 'Profile berhasil diupdate!');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = Auth::user();

        // Hapus avatar lama kalo ada
        if ($user->avatar) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        // Simpan avatar baru
        $filename = 'user-' . $user->id . '-' . time() . '.' . $request->file('avatar')->extension();
        $request->file('avatar')->storeAs('avatars', $filename, 'public');
        
        $user->avatar = $filename;
        $user->save();

        return back()->with('success', 'Foto profile berhasil diupdate!');
    }
}