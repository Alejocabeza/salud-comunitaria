<?php

namespace App\Providers;

use App\Models\Doctor;
use App\Models\OutpatientCenter;
use App\Observers\DoctorObserver;
use App\Observers\OutpatientCenterObserver;
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
        OutpatientCenter::observe(OutpatientCenterObserver::class);
        Doctor::observe(DoctorObserver::class);
    }
}
