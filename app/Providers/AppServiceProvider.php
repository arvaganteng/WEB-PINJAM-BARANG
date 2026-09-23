<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
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
        Paginator::defaultView('vendor.pagination.custom');

        // Auto-detect ngrok / reverse proxy tunneling
        // Fixes CSS/JS not loading when accessed via ngrok URL
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && !empty($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $scheme = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'https';
            $host   = $_SERVER['HTTP_X_FORWARDED_HOST'];
            URL::forceRootUrl("{$scheme}://{$host}");
            URL::forceScheme($scheme);
        }
    }
}
