<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Route default (jangan dihapus)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ========================================
// MIDTRANS WEBHOOK - TANPA CSRF
// ========================================

Route::post('/midtrans/notification', [\App\Http\Controllers\Customer\PaymentController::class, 'notification'])
    ->name('midtrans.notification')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// ========================================
// FLIGHT SEARCH API
// ========================================

Route::get('/flights/search', [\App\Http\Controllers\Api\FlightSearchApiController::class, 'search'])->name('api.flights.search');

// ========================================
// PROMO API
// ========================================

Route::prefix('promos')->name('api.promos.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\PromoApiController::class, 'index'])->name('index');
    Route::get('/active', [\App\Http\Controllers\Api\PromoApiController::class, 'active'])->name('active');
    Route::post('/validate', [\App\Http\Controllers\Api\PromoValidationController::class, 'validate'])->name('validate');
});
