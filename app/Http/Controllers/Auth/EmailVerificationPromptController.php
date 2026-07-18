<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     * If already verified, redirect based on role.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return $this->redirectByRole($user)
                ->with('success', 'Email Anda sudah terverifikasi.');
        }

        return view('auth.verify-email');
    }

    private function redirectByRole($user): RedirectResponse
    {
        return redirect()->to(match ($user->role) {
            'admin' => route('admin.dashboard'),
            'staff' => route('staff.dashboard'),
            'manager' => route('manager.dashboard'),
            default => route('customer.home'),
        });
    }
}