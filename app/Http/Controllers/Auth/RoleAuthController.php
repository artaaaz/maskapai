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

        $request->authenticate($role);
        $request->session()->regenerate();

        // Jika email belum diverifikasi, arahkan ke halaman verifikasi email
        if ($role === 'customer' && is_null($request->user()->email_verified_at)) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Email Anda belum terverifikasi. Silakan verifikasi email Anda terlebih dahulu sebelum dapat melakukan pemesanan.');
        }

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

        return redirect()->route('customer.login')->with('success', 'Registrasi berhasil! Silakan periksa email Anda untuk memverifikasi akun sebelum masuk.');
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
