<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified.
     * 
     * CRITICAL: This does NOT rely on $request->user() because
     * the user clicking the link from their email is NOT authenticated.
     * Instead, we find the user by ID from the signed URL.
     */
    public function __invoke(Request $request, $id, $hash): RedirectResponse
    {
        Log::info('=== EMAIL VERIFICATION ATTEMPT ===', [
            'id' => $id,
            'hash' => $hash,
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // 1. Verify the signed URL is valid
        if (!URL::hasValidSignature($request)) {
            $errorMsg = 'Link verifikasi tidak valid. Silakan daftar ulang atau hubungi dukungan.';
            
            if ($request->has('expires')) {
                $expires = (int) $request->get('expires');
                if (now()->timestamp > $expires) {
                    $errorMsg = 'Link verifikasi sudah kedaluwarsa. Silakan daftar ulang atau hubungi dukungan.';
                }
            }
            
            Log::warning('Email verification failed: Invalid or expired signature', [
                'id' => $id,
                'expires' => $request->get('expires'),
                'signature' => $request->get('signature'),
                'full_url' => $request->fullUrl(),
                'app_url' => config('app.url'),
            ]);
            
            return redirect()->route('login')
                ->with('error', $errorMsg);
        }

        // 2. Find the user by ID
        $user = User::find($id);

        if (!$user) {
            Log::error('Email verification failed: User not found', ['id' => $id]);
            return redirect()->route('login')
                ->with('error', 'Pengguna tidak ditemukan. Silakan daftar ulang.');
        }

        Log::info('User found for verification', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'current_verified_at' => $user->email_verified_at,
        ]);

        // 3. Verify the hash matches
        $expectedHash = sha1($user->getEmailForVerification());
        if (!hash_equals((string) $hash, $expectedHash)) {
            Log::warning('Email verification failed: Hash mismatch', [
                'expected' => $expectedHash,
                'received' => $hash,
            ]);
            return redirect()->route('login')
                ->with('error', 'Link verifikasi tidak valid. Silakan daftar ulang.');
        }

        // 4. Check if already verified
        if ($user->hasVerifiedEmail()) {
            Log::info('Email already verified', ['user_id' => $user->id, 'email' => $user->email]);

            // Auto-login and redirect
            Auth::login($user, true);
            $request->session()->regenerate();

            return $this->redirectByRole($user)
                ->with('info', 'Email sudah pernah diverifikasi sebelumnya.');
        }

        // 5. Mark as verified - FORCE UPDATE langsung ke database
        try {
            // Method 1: Gunakan markEmailAsVerified() bawaan Laravel
            $marked = $user->markEmailAsVerified();
            
            Log::info('markEmailAsVerified result', [
                'return_value' => $marked,
                'email_verified_at_model' => $user->email_verified_at,
            ]);
            
            // Method 2: Force update langsung ke DB sebagai backup jika method 1 gagal
            $freshUser = User::find($user->id);
            if (is_null($freshUser->email_verified_at)) {
                User::where('id', $user->id)->update([
                    'email_verified_at' => now(),
                ]);
                Log::info('Force update email_verified_at via DB raw update - markEmailAsVerified did not persist');
            }
            
            // Refresh the user model
            $user->refresh();
            
            // Fire event
            event(new Verified($user));

            Log::info('✅ Email verification SUCCESS', [
                'user_id' => $user->id,
                'email' => $user->email,
                'verified_at' => $user->email_verified_at,
            ]);
        } catch (\Exception $e) {
            Log::error('Email verification failed: Database error', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Fallback: coba direct DB update
            try {
                User::where('id', $user->id)->update([
                    'email_verified_at' => now(),
                ]);
                $user->refresh();
                event(new Verified($user));
                
                Log::info('✅ Email verification SUCCESS after fallback DB update', [
                    'user_id' => $user->id,
                    'verified_at' => $user->email_verified_at,
                ]);
            } catch (\Exception $e2) {
                Log::error('Email verification failed even after fallback', [
                    'user_id' => $user->id,
                    'error' => $e2->getMessage(),
                ]);
                return redirect()->route('login')
                    ->with('error', 'Terjadi kesalahan saat memverifikasi email. Silakan coba lagi.');
            }
        }

        // 6. Auto-login the user
        Auth::login($user, true);
        $request->session()->regenerate();

        Log::info('User auto-logged in after verification', [
            'user_id' => $user->id,
            'role' => $user->role,
            'session_id' => $request->session()->getId(),
        ]);

        // 7. Redirect based on role
        return $this->redirectByRole($user)
            ->with('success', 'Email berhasil diverifikasi. Selamat datang di drgMaskapai!');
    }

    /**
     * Redirect user based on their role.
     */
    private function redirectByRole($user): RedirectResponse
    {
        $route = match ($user->role) {
            'admin' => route('admin.dashboard'),
            'staff' => route('staff.dashboard'),
            'manager' => route('manager.dashboard'),
            default => route('customer.home'),
        };

        Log::info('Redirecting user after verification', [
            'user_id' => $user->id,
            'role' => $user->role,
            'route' => $route,
        ]);

        return redirect()->to($route);
    }
}