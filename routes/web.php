<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

// ========================================
// DEBUG MAIL ROUTE (HAPUS SETELAH TESTING)
// ========================================
Route::get('/debug-mail', function () {
    $config = [
        'MAIL_MAILER' => config('mail.default'),
        'MAIL_HOST' => config('mail.mailers.smtp.host'),
        'MAIL_PORT' => config('mail.mailers.smtp.port'),
        'MAIL_ENCRYPTION' => config('mail.mailers.smtp.encryption'),
        'MAIL_USERNAME' => config('mail.mailers.smtp.username'),
        'MAIL_PASSWORD' => config('mail.mailers.smtp.password') ? '***SET***' : '***NOT SET***',
        'MAIL_FROM_ADDRESS' => config('mail.from.address'),
        'MAIL_FROM_NAME' => config('mail.from.name'),
        'QUEUE_CONNECTION' => config('queue.default'),
    ];

    try {
        Mail::raw('Test email from drgMaskapai - ' . now(), function ($message) {
            $message->to('arthaaazmuridbazma@gmail.com')
                    ->subject('Debug Mail Test - ' . now());
        });

        $result = '✅ Email sent successfully! Check Gmail inbox.';
    } catch (\Exception $e) {
        $result = '❌ FAILED: ' . $e->getMessage();
    }

    return response()->json([
        'config' => $config,
        'result' => $result,
    ]);
});

// ========================================
// MIDTRANS WEBHOOK (tanpa CSRF)
// ========================================
Route::post('/midtrans/notification', [PaymentController::class, 'notification'])
    ->name('midtrans.notification');

// ========================================
// PUBLIC / GUEST ROUTES (Tidak perlu login)
// ========================================
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('customer.home');
});

Route::get('/customer/home', [\App\Http\Controllers\Customer\HomeController::class, 'index'])->name('customer.home');

// Flight Search & Results - Guest friendly
Route::get('/customer/flights/search', [\App\Http\Controllers\Customer\FlightResultsController::class, 'index'])->name('customer.flights.results');
Route::get('/customer/flights/{flight}', [\App\Http\Controllers\Customer\FlightResultsController::class, 'show'])->name('customer.flights.show');

// Airport search autocomplete
Route::get('/customer/airports/search', [\App\Http\Controllers\Customer\AirportController::class, 'search'])->name('customer.airports.search');

// Offline fallback for PWA
Route::get('/offline', function () {
    return response()->view('offline');
})->name('offline');

// Flight Detail - Guest friendly (alias for show)
Route::get('/customer/flights/{flight}/detail', [\App\Http\Controllers\Customer\BookingController::class, 'flightDetail'])->name('customer.flights.detail');

// Seat preview - Guest can view available seats
Route::get('/customer/flight/{flight}/seats', [\App\Http\Controllers\Customer\SeatSelectionController::class, 'getAvailableSeats'])
    ->name('customer.flight.seats');

// Promo - Guest can view promos
Route::get('/customer/promos', [\App\Http\Controllers\Customer\PromoController::class, 'index'])->name('customer.promos');

// ========================================
// DASHBOARD - Role based redirect (harus login & verified)
// ========================================
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
// ADMIN ROUTES (auth + verified + admin)
// ========================================
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('airlines', \App\Http\Controllers\Admin\AirlineController::class);
    Route::resource('airports', \App\Http\Controllers\Admin\AirportController::class);
    Route::resource('airplanes', \App\Http\Controllers\Admin\AirplaneController::class);
    Route::resource('flights', \App\Http\Controllers\Admin\FlightController::class);

    // Flight Classes (nested under flights)
    Route::prefix('flights/{flight}/flight-classes')->name('flights.flight-classes.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\FlightClassController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\FlightClassController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\FlightClassController::class, 'store'])->name('store');
        Route::get('/{flightClass}/edit', [\App\Http\Controllers\Admin\FlightClassController::class, 'edit'])->name('edit');
        Route::put('/{flightClass}', [\App\Http\Controllers\Admin\FlightClassController::class, 'update'])->name('update');
        Route::delete('/{flightClass}', [\App\Http\Controllers\Admin\FlightClassController::class, 'destroy'])->name('destroy');
    });

    // Promo CRUD
    Route::resource('promos', \App\Http\Controllers\Admin\PromoController::class);
    Route::post('promos/{promo}/toggle', [\App\Http\Controllers\Admin\PromoController::class, 'toggle'])->name('promos.toggle');
});

// ========================================
// STAFF ROUTES (auth + verified + staff/admin)
// ========================================
Route::middleware(['auth', 'verified', 'role:staff,admin'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');

    // Bookings
    Route::get('/bookings', [\App\Http\Controllers\Staff\BookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\Staff\BookingController::class, 'show'])->name('booking.show');
    Route::put('/bookings/{booking}/status', [\App\Http\Controllers\Staff\BookingController::class, 'updateStatus'])->name('booking.updateStatus');
    Route::post('/bookings/{booking}/verify-payment', [\App\Http\Controllers\Staff\BookingController::class, 'verifyPayment'])->name('booking.verifyPayment');

    // Passengers
    Route::get('/passengers', [\App\Http\Controllers\Staff\DashboardController::class, 'passengers'])->name('passengers');
    Route::get('/passengers/{passenger}', [\App\Http\Controllers\Staff\DashboardController::class, 'passengerDetail'])->name('passenger.show');
    Route::post('/passengers/{passenger}/check-in', [\App\Http\Controllers\Staff\DashboardController::class, 'checkIn'])->name('passenger.checkin');
    Route::post('/passengers/{passenger}/board', [\App\Http\Controllers\Staff\DashboardController::class, 'boardPassenger'])->name('passenger.board');
    Route::post('/passengers/{passenger}/check-out', [\App\Http\Controllers\Staff\DashboardController::class, 'checkOut'])->name('passenger.checkout');

    // Passenger Monitoring
    Route::get('/monitoring', [\App\Http\Controllers\Staff\DashboardController::class, 'monitoring'])->name('monitoring');

    // Flight Manifest
    Route::get('/flights/{flight}/manifest', [\App\Http\Controllers\Staff\DashboardController::class, 'manifest'])->name('flight.manifest');

    // Reports
    Route::get('/reports', function () {
        return view('staff.reports');
    })->name('reports');
    Route::get('/reports/export/csv', [\App\Http\Controllers\Staff\ReportExportController::class, 'exportCsv'])->name('reports.export.csv');
    Route::get('/reports/print', [\App\Http\Controllers\Staff\ReportExportController::class, 'print'])->name('reports.print');
});

// ========================================
// MANAGER ROUTES (auth + verified + manager)
// ========================================
Route::middleware(['auth', 'verified', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Manager\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/export/excel', [\App\Http\Controllers\Manager\DashboardController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [\App\Http\Controllers\Manager\DashboardController::class, 'exportPdf'])->name('export.pdf');

    Route::get('/reports', [\App\Http\Controllers\Manager\ManagerReportController::class, 'index'])->name('reports');
    Route::get('/reports/export/excel', [\App\Http\Controllers\Manager\ManagerReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export/pdf', [\App\Http\Controllers\Manager\ManagerReportController::class, 'exportPdf'])->name('reports.export.pdf');
});

// ========================================
// CUSTOMER PROTECTED ROUTES (wajib login + verified + customer)
// Booking, Payment, Profile, dll.
// ========================================
Route::middleware(['auth', 'verified', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {

    // Booking - PRODUK TERPROTEKSI
    Route::get('/bookings/create/{flight}', [\App\Http\Controllers\Customer\BookingController::class, 'create'])->name('booking.create');
    Route::post('/bookings', [\App\Http\Controllers\Customer\BookingController::class, 'store'])->name('booking.store');
    
    // Seat Selection BEFORE booking (pre-seat selection flow)
    Route::get('/flight/{flight}/seat-selection', [\App\Http\Controllers\Customer\BookingController::class, 'seatSelection'])->name('flight-detail.seat-selection');
    Route::post('/flight/{flight}/store-seats', [\App\Http\Controllers\Customer\BookingController::class, 'storeSeats'])->name('flight-detail.store-seats');
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

    // Seat Selection (final - setelah login)
    Route::get('/booking/{booking}/select-seat', [\App\Http\Controllers\Customer\SeatSelectionController::class, 'select'])
        ->name('booking.select-seat');
    Route::post('/booking/{booking}/select-seat', [\App\Http\Controllers\Customer\SeatSelectionController::class, 'store'])
        ->name('booking.store-seat');

    // Payment page from midtrans
    Route::get('/booking/{booking}/payment/{snap_token}', function (\App\Models\Booking $booking, $snapToken) {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }
        return view('customer.booking-payment', compact('booking', 'snapToken'));
    })->name('booking.payment');

    // TEST: Manual Payment Success (HANYA untuk environment local, TIDAK PERNAH di production)
    if (app()->environment('local')) {
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
    }
});

// ========================================
// AUTH & PROFILE (Laravel Breeze)
// ========================================
require __DIR__ . '/role-auth.php';
require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});