<?php

namespace App\Providers;

use App\Auth\DniEloquentUserProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Auth;
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

        Auth::provider('dni-eloquent', function ($app, array $config) {
            return new DniEloquentUserProvider($app['hash'], $config['model']);
        });

        ResetPassword::createUrlUsing(function ($notifiable, string $token): string {
            $email = $notifiable->getEmailForPasswordReset();

            return rtrim(config('app.url'), '/').'/reset-password/'.$token.'?email='.urlencode($email);
        });
    }
}
