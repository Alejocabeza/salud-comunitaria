<?php

namespace App\Observers;

use App\Events\ActionLoggerEvent;
use App\Helps\PasswordGenerate;
use App\Models\Patient;
use App\Models\User;
use App\Notifications\SendInitialPassword;

class PatientObserver
{
    /**
     * Handle the Patient "created" event.
     */
    public function created(Patient $patient): void
    {
        if ($patient->is_active) {
            $plainPassword = PasswordGenerate::make('password');
            $user = User::create([
                'name' => $patient->full_name,
                'email' => $patient->email,
                'dni' => $patient->dni,
                'password' => $plainPassword,
            ]);
            $user->assignRole('Paciente');
            $user->notify(new SendInitialPassword($plainPassword));
            event(new ActionLoggerEvent(
                'create',
                Patient::class,
                auth()->guard('web')->user(),
            ));
        }
    }

    /**
     * Handle the Patient "updated" event.
     */
    public function updated(Patient $patient): void
    {
        if ($patient->is_active) {
            User::where('email', $patient->email)->update([
                'name' => $patient->full_name,
                'dni' => $patient->dni,
                'email' => $patient->email,
            ]);
            event(new ActionLoggerEvent(
                'update',
                Patient::class,
                auth()->guard('web')->user(),
            ));
        }
    }

    /**
     * Handle the Patient "deleted" event.
     */
    public function deleted(Patient $patient): void
    {
        User::where('email', $patient->email)->update(['active' => false]);
        event(new ActionLoggerEvent(
            'delete',
            Patient::class,
            auth()->guard('web')->user(),
        ));
    }

    /**
     * Handle the Patient "restored" event.
     */
    public function restored(Patient $patient): void
    {
        User::where('email', $patient->email)->update(['active' => true]);
        event(new ActionLoggerEvent(
            'restore',
            Patient::class,
            auth()->guard('web')->user(),
        ));
    }

    /**
     * Handle the Patient "force deleted" event.
     */
    public function forceDeleted(Patient $patient): void
    {
        User::where('email', $patient->email)->delete();
        event(new ActionLoggerEvent(
            'force delete',
            Patient::class,
            auth()->guard('web')->user(),
        ));
    }
}
