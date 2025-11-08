<?php

namespace App\Observers;

use App\Events\ActionLoggerEvent;
use App\Helps\PasswordGenerate;
use App\Models\Doctor;
use App\Models\User;
use App\Notifications\SendInitialPassword;

class DoctorObserver
{
    /**
     * Handle the Doctor "created" event.
     */
    public function created(Doctor $doctor): void
    {
        if ($doctor->is_active) {
            $plainPassword = PasswordGenerate::make('password');
            $user = User::create([
                'name' => $doctor->full_name,
                'email' => $doctor->email,
                'password' => $plainPassword,
            ]);
            $user->assignRole('Doctor');
            // Notificar al usuario con la contraseña en claro (el cast 'hashed' del modelo hará el hash)
            $user->notify(new SendInitialPassword($plainPassword));
            event(new ActionLoggerEvent(
                'create',
                Doctor::class,
                auth()->guard('web')->user(),
            ));
        }
    }

    /**
     * Handle the Doctor "updated" event.
     */
    public function updated(Doctor $doctor): void
    {
        if ($doctor->is_active) {
            User::where('email', $doctor->email)->update([
                'name' => $doctor->name,
                'email' => $doctor->email,
            ]);
            event(new ActionLoggerEvent(
                'update',
                Doctor::class,
                auth()->guard('web')->user(),
            ));
        }
    }

    /**
     * Handle the Doctor "deleted" event.
     */
    public function deleted(Doctor $doctor): void
    {
        User::where('email', $doctor->email)->update(['active' => false]);
        event(new ActionLoggerEvent(
            'delete',
            Doctor::class,
            auth()->guard('web')->user(),
        ));
    }

    /**
     * Handle the Doctor "restored" event.
     */
    public function restored(Doctor $doctor): void
    {
        User::where('email', $doctor->email)->update(['active' => true]);
        event(new ActionLoggerEvent(
            'restore',
            Doctor::class,
            auth()->guard('web')->user(),
        ));
    }

    /**
     * Handle the Doctor "force deleted" event.
     */
    public function forceDeleted(Doctor $doctor): void
    {
        User::where('email', $doctor->email)->delete();
        event(new ActionLoggerEvent(
            'force delete',
            Doctor::class,
            auth()->guard('web')->user(),
        ));
    }
}
