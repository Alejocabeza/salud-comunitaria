<?php

namespace App\Providers;

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
        \App\Models\OutpatientCenter::observe(\App\Observers\OutpatientCenterObserver::class);
        \App\Models\Doctor::observe(\App\Observers\DoctorObserver::class);
        \App\Models\Patient::observe(\App\Observers\PatientObserver::class);
    }
}
