<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        if (app()->isProduction() && config('app.enable_ssl')) {
            \URL::forceScheme('https');
        }

        Paginator::useBootstrapFive();
        Paginator::useBootstrapFour();
    }
}
