<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Locale Indonesia untuk Carbon (isoFormat, diffForHumans, dll)
        Carbon::setLocale('id');

        // Force HTTPS in production
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
