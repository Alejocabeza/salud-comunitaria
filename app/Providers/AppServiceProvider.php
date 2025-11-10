<?php

namespace App\Providers;

use App\Auth\DniEloquentUserProvider;
use App\Models\Appointment;
use App\Models\MedicalHistoryEvent;
use App\Policies\AppointmentPolicy;
use App\Policies\MedicalHistoryEventPolicy;
use Filament\Support\Assets\Css as FilamentCss;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
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

        // Register policy for MedicalHistoryEvent so Gate/Filament can find it.
        Gate::policy(MedicalHistoryEvent::class, MedicalHistoryEventPolicy::class);
        Gate::policy(Appointment::class, AppointmentPolicy::class);

        Auth::provider('dni-eloquent', function ($app, array $config) {
            return new DniEloquentUserProvider($app['hash'], $config['model']);
        });

        ResetPassword::createUrlUsing(function ($notifiable, string $token): string {
            $email = $notifiable->getEmailForPasswordReset();

            return rtrim(config('app.url'), '/').'/reset-password/'.$token.'?email='.urlencode($email);
        });

        // Register filament-specific widget CSS to force uniform chart heights
        if (class_exists(FilamentAsset::class)) {
            FilamentAsset::register([
                FilamentCss::make('filament-widgets', Vite::asset('resources/css/filament-widgets.css')),
            ]);
        }
    }
}
