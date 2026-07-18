<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CustomVerifyEmailController extends Controller
{
    public function verify($id)
    {
        $user = User::findOrFail($id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        Auth::login($user);

        return redirect()
            ->route('customer.home')
            ->with('success', 'Email berhasil diverifikasi. Selamat datang!');
    }
}