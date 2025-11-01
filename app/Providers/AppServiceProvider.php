<?php

namespace App\Providers;

use App\Models\OutpatientCenter;
use App\Observers\OutpatientCenterObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

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
        OutpatientCenter::observe(OutpatientCenterObserver::class);

        Auth::provider('active-eloquent', function ($app, array $config) {
            return new ActiveEloquentUserProvider($app['hash'], $config['model']);
        });
    }
}
