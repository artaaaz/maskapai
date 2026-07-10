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