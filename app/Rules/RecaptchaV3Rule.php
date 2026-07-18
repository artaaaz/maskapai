<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class RecaptchaV3Rule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!config('services.recaptcha.enabled', true)) {
            return;
        }

        if (config('app.env') === 'local' && (empty(config('services.recaptcha.secret_key')) || config('services.recaptcha.secret_key') === 'YOUR_RECAPTCHA_SECRET_KEY')) {
            return;
        }

        try {
            $response = Http::asForm()->timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => config('services.recaptcha.secret_key'),
                'response' => $value,
            ]);

            $body = $response->json();

            // reCAPTCHA v2 (checkbox) hanya mengembalikan field 'success' tanpa 'score'
            // reCAPTCHA v3 mengembalikan 'success' dan 'score'
            $success = $body['success'] ?? false;

            if (!$success) {
                if (config('app.env') === 'local') {
                    \Log::warning('reCAPTCHA verification failed locally but bypassed for testing: ' . json_encode($body));
                    return;
                }
                $fail('Verifikasi keamanan gagal. Silakan coba lagi.');
            }

            // Jika ada score (v3), pastikan minimal 0.5
            if (isset($body['score']) && $body['score'] < 0.5) {
                if (config('app.env') === 'local') {
                    \Log::warning('reCAPTCHA v3 score too low locally but bypassed for testing: ' . json_encode($body));
                    return;
                }
                $fail('Verifikasi keamanan gagal. Silakan coba lagi.');
            }
        } catch (\Exception $e) {
            if (config('app.env') === 'local') {
                \Log::warning('reCAPTCHA verification connection failed, bypassed in local: ' . $e->getMessage());
                return;
            }
            $fail('Gagal menghubungi server verifikasi keamanan.');
        }
    }
}
