<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaRule implements ValidationRule
{
    /**
     * reCAPTCHA v2 Checkbox validation rule
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!config('services.recaptcha.enabled', true)) {
            return;
        }

        // Di local development, bypass verify untuk memudahkan testing
        if (config('app.env') === 'local') {
            Log::info('reCAPTCHA v2 bypassed in local development');
            return;
        }

        if (empty($value)) {
            $fail('Verifikasi keamanan belum dilakukan. Silakan centang "Saya bukan robot".');
            return;
        }

        try {
            $response = Http::asForm()->timeout(10)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => config('services.recaptcha.secret_key'),
                'response' => $value,
            ]);

            $body = $response->json();
            $success = $body['success'] ?? false;

            if (!$success) {
                $errorCodes = $body['error-codes'] ?? [];
                Log::warning('reCAPTCHA v2 verification failed', [
                    'error-codes' => $errorCodes,
                ]);
                $fail('Verifikasi keamanan gagal. Silakan coba lagi.');
            }
        } catch (\Exception $e) {
            Log::warning('reCAPTCHA v2 connection error: ' . $e->getMessage());
            // Jika gagal koneksi, bypass untuk development
            if (config('app.env') === 'local') {
                Log::warning('reCAPTCHA v2 bypassed due to connection error in local');
                return;
            }
            $fail('Gagal menghubungi server verifikasi keamanan. Silakan coba lagi.');
        }
    }
}