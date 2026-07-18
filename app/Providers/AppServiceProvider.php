<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS untuk production atau jika FORCE_HTTPS=true
        if (env('FORCE_HTTPS', false) || $this->app->environment('production')) {
            URL::forceScheme('https');
        }
        
        // Untuk ngrok, detect dari header X-Forwarded-Proto
        if (request()->header('X-Forwarded-Proto') === 'https' || 
            request()->header('HTTP_X_FORWARDED_PROTO') === 'https') {
            URL::forceScheme('https');
        }
    }
}