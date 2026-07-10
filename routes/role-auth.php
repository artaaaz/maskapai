<?php

use App\Http\Controllers\Auth\RoleAuthController;
use Illuminate\Support\Facades\Route;

$roles = ['customer', 'staff', 'manager', 'admin'];

Route::middleware('guest')->group(function () use ($roles) {
    foreach ($roles as $role) {
        Route::get("{$role}/login", [RoleAuthController::class, 'showLogin'])
            ->defaults('role', $role)
            ->name("{$role}.login");

        Route::post("{$role}/login", [RoleAuthController::class, 'login'])
            ->defaults('role', $role)
            ->name("{$role}.login.store");
    }

    Route::get('customer/daftar', [RoleAuthController::class, 'showRegister'])
        ->defaults('role', 'customer')
        ->name('customer.register');

    Route::post('customer/daftar', [RoleAuthController::class, 'register'])
        ->defaults('role', 'customer')
        ->name('customer.register.store');

    Route::get('login', fn () => redirect()->route('customer.login'))->name('login');
    Route::get('register', fn () => redirect()->route('customer.register'))->name('register');
});

Route::middleware('auth')->group(function () use ($roles) {
    foreach ($roles as $role) {
        Route::post("{$role}/logout", [RoleAuthController::class, 'destroy'])
            ->defaults('role', $role)
            ->name("{$role}.logout");
    }
});
