<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ========================================
// MIDTRANS WEBHOOK (tanpa CSRF)
// ========================================
Route::post('/midtrans/notification', [PaymentController::class, 'notification'])
    ->name('midtrans.notification');

// ========================================
// HOME & DASHBOARD
// ========================================

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('customer.login');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'staff' => redirect()->route('staff.dashboard'),
        'manager' => redirect()->route('manager.dashboard'),
        default => redirect()->route('customer.home'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// ========================================
// ADMIN ROUTES
// ========================================

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('airlines', \App\Http\Controllers\Admin\AirlineController::class);
    Route::resource('airports', \App\Http\Controllers\Admin\AirportController::class);
    Route::resource('airplanes', \App\Http\Controllers\Admin\AirplaneController::class);
    Route::resource('flights', \App\Http\Controllers\Admin\FlightController::class);
});

// ========================================
// STAFF ROUTES
// ========================================

Route::middleware(['auth', 'verified', 'role:staff,admin'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');

    // Bookings
    Route::get('/bookings', [\App\Http\Controllers\Staff\BookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\Staff\BookingController::class, 'show'])->name('booking.show');
    Route::put('/bookings/{booking}/status', [\App\Http\Controllers\Staff\BookingController::class, 'updateStatus'])->name('booking.updateStatus');

    // Passengers
    Route::get('/passengers', [\App\Http\Controllers\Staff\DashboardController::class, 'passengers'])->name('passengers');
    Route::get('/passengers/{passenger}', [\App\Http\Controllers\Staff\DashboardController::class, 'passengerDetail'])->name('passenger.show');
    Route::post('/passengers/{passenger}/check-in', [\App\Http\Controllers\Staff\DashboardController::class, 'checkIn'])->name('passenger.checkin');
    Route::post('/passengers/{passenger}/board', [\App\Http\Controllers\Staff\DashboardController::class, 'boardPassenger'])->name('passenger.board');
    Route::post('/passengers/{passenger}/check-out', [\App\Http\Controllers\Staff\DashboardController::class, 'checkOut'])->name('passenger.checkout');

    // Reports
    Route::get('/reports', function () {
        return view('staff.reports');
    })->name('reports');
});

// ========================================
// MANAGER ROUTES
// ========================================

Route::middleware(['auth', 'verified', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', function () {
        return view('manager.dashboard');
    })->name('dashboard');

    Route::get('/reports', function () {
        return view('manager.reports');
    })->name('reports');
});

// ========================================
// CUSTOMER ROUTES (SEMUA DI SINI)
// ========================================

Route::middleware(['auth', 'verified', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {

    // Home
    Route::get('/home', [\App\Http\Controllers\Customer\HomeController::class, 'index'])->name('home');

    // Search Flights
    Route::get('/search-flights', [\App\Http\Controllers\Customer\FlightSearchController::class, 'index'])->name('search');
    Route::post('/search-flights', [\App\Http\Controllers\Customer\FlightSearchController::class, 'search'])->name('search.process');
    Route::get('/flights/{flight}', [\App\Http\Controllers\Customer\FlightSearchController::class, 'show'])->name('flight.show');

    // Booking
    Route::get('/bookings/create/{flight}', [\App\Http\Controllers\Customer\BookingController::class, 'create'])->name('booking.create');
    Route::post('/bookings', [\App\Http\Controllers\Customer\BookingController::class, 'store'])->name('booking.store');
    Route::get('/my-bookings', [\App\Http\Controllers\Customer\BookingController::class, 'index'])->name('bookings');
    Route::get('/my-bookings/{booking}', [\App\Http\Controllers\Customer\BookingController::class, 'show'])->name('booking.show');
    Route::get('/my-bookings/{booking}/e-ticket', [\App\Http\Controllers\Customer\BookingController::class, 'eTicket'])->name('booking.e-ticket');

    // Payment
    Route::get('/payment/{booking}', [\App\Http\Controllers\Customer\PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{booking}', [\App\Http\Controllers\Customer\PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/{booking}/instructions', [\App\Http\Controllers\Customer\PaymentController::class, 'instructions'])->name('payment.instructions');
    Route::post('/payment/{booking}/verify', [\App\Http\Controllers\Customer\PaymentController::class, 'verify'])->name('payment.verify');
    Route::get('/payment/{booking}/finish', [\App\Http\Controllers\Customer\PaymentController::class, 'finish'])->name('payment.finish');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\Customer\ProfileController::class, 'index'])->name('profile');
    Route::patch('/profile', [\App\Http\Controllers\Customer\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [\App\Http\Controllers\Customer\ProfileController::class, 'updateAvatar'])->name('profile.avatar');

    // TEST: Manual Payment Success (HAPUS NANTI)
    Route::post('/payment/{booking}/test-success', function (\App\Models\Booking $booking) {
        $user = auth()->user();
        if (!$user || $booking->user_id !== $user->id) {
            abort(403);
        }

        if ($booking->payment) {
            $booking->payment->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);
        }

        $booking->update(['status' => 'confirmed']);

        return redirect()->route('customer.booking.show', $booking)
            ->with('success', 'Pembayaran berhasil! (Test Mode)');
    })->name('payment.test-success');
});

// Seat Selection
Route::get('/customer/booking/{booking}/select-seat', [\App\Http\Controllers\Customer\SeatSelectionController::class, 'select'])
    ->name('customer.booking.select-seat');
Route::post('/customer/booking/{booking}/select-seat', [\App\Http\Controllers\Customer\SeatSelectionController::class, 'store'])
    ->name('customer.booking.store-seat');
Route::get('/customer/flight/{flight}/seats', [\App\Http\Controllers\Customer\SeatSelectionController::class, 'getAvailableSeats'])
    ->name('customer.flight.seats');
    
Route::get('/customer/booking/{booking}/payment/{snap_token}', function (\App\Models\Booking $booking, $snapToken) {
    if ($booking->user_id !== auth()->id()) {
        abort(403);
    }
    return view('customer.booking-payment', compact('booking', 'snapToken'));
})->name('customer.booking.payment');


Route::get('/midtrans-test', function () {

    Config::$serverKey = config('services.midtrans.server_key');
    Config::$clientKey = config('services.midtrans.client_key');
    Config::$isProduction = false;
    Config::$isSanitized = true;
    Config::$is3ds = true;

    $params = [
        'transaction_details' => [
            'order_id' => 'TEST-' . time(),
            'gross_amount' => 10000,
        ],
        'customer_details' => [
            'first_name' => 'Test',
            'email' => 'test@example.com',
        ],
    ];

    return Snap::getSnapToken($params);
});


// ========================================
// AUTH & PROFILE
// ========================================

require __DIR__ . '/role-auth.php';
require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

