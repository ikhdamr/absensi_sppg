<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <--- INI TAMBAHANNYA

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
        // Memaksa Laravel memancarkan desain (CSS/JS) dengan HTTPS saat di-online-kan
        if (str_contains(request()->getHost(), 'loca.lt') || str_contains(request()->getHost(), 'ngrok')) {
            URL::forceScheme('https');
        }
    }
}
