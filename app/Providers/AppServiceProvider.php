<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        RateLimiter::for('pages', fn ($r) => Limit::perMinute(120)->by('pages:'.$r->ip()));
        RateLimiter::for('writing', fn ($r) => Limit::perMinute(5)->by('writing:'.$r->ip()));
        RateLimiter::for('clock', fn ($r) => Limit::perMinute(60)->by('clock:'.$r->ip()));

    }
}
