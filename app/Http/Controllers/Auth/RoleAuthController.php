<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RoleLoginRequest;
use App\Models\User;
use App\Rules\RecaptchaRule;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RoleAuthController extends Controller
{
    private array $roles = ['customer', 'staff', 'manager', 'admin'];

    public function showLogin(string $role): View
    {
        $this->assertValidRole($role);

        return view("auth.{$role}.login", compact('role'));
    }

    public function login(RoleLoginRequest $request, string $role): RedirectResponse
    {
        $this->assertValidRole($role);

        // Authenticate - akan throw ValidationException jika gagal
        $request->authenticate($role);

        // Get user setelah login sukses
        $user = Auth::user();

        if (!$user) {
            Log::error('Login failed: Auth::user() null after authenticate', [
                'email' => $request->email,
                'role' => $role,
            ]);
            return redirect()->route("{$role}.login")
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }

        Log::info('Login authenticated', [
            'user_id' => $user->id,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
        ]);

        // Jika email belum diverifikasi, redirect ke halaman verifikasi
        // TAPI JANGAN LOGOUT USER! Biarkan session tetap ada agar bisa akses route verification.notice
        if (is_null($user->email_verified_at)) {
            Log::info('Login: email not verified, redirecting to verification notice', [
                'user_id' => $user->id,
            ]);

            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            return redirect()->route('verification.notice')
                ->with('warning', 'Akun Anda belum diverifikasi. Silakan cek email Anda untuk memverifikasi akun.');
        }

        // Email sudah verified, regenerate session dan redirect
        $request->session()->regenerate();

        Log::info('Login SUCCESS - verified user', [
            'user_id' => $user->id,
            'role' => $user->role,
            'redirect_to' => $this->dashboardRoute($role),
        ]);

        return redirect()->intended($this->dashboardRoute($role));
    }

    public function showRegister(string $role): View|RedirectResponse
    {
        if ($role !== 'customer') {
            abort(404);
        }

        return view('auth.customer.register', compact('role'));
    }

    public function register(Request $request, string $role): RedirectResponse
    {
        if ($role !== 'customer') {
            abort(404);
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'g-recaptcha-response' => ['nullable', new RecaptchaRule()],
        ];

        $request->validate($rules);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        event(new Registered($user));

        // Login user setelah register agar bisa akses halaman verifikasi
        Auth::login($user);

        // Redirect ke halaman verifikasi email dengan pesan sukses
        return redirect()->route('verification.notice')
            ->with('success', 'Akun berhasil dibuat. Silakan cek email Anda untuk memverifikasi akun.');
    }

    public function destroy(Request $request, string $role): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route("{$role}.login");
    }

    private function assertValidRole(string $role): void
    {
        if (!in_array($role, $this->roles, true)) {
            abort(404);
        }
    }

    private function dashboardRoute(string $role): string
    {
        return match ($role) {
            'admin' => route('admin.dashboard'),
            'staff' => route('staff.dashboard'),
            'manager' => route('manager.dashboard'),
            default => route('customer.home'),
        };
    }
}