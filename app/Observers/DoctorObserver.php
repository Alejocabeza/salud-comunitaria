<?php

namespace App\Observers;

use App\Events\ActionLoggerEvent;
use App\Helps\PasswordGenerate;
use App\Models\Doctor;
use App\Models\User;

class DoctorObserver
{
    /**
     * Handle the Doctor "created" event.
     */
    public function created(Doctor $doctor): void
    {
        if ($doctor->is_active) {
            $user = User::create([
                'name' => $doctor->name,
                'email' => $doctor->email,
                'password' => PasswordGenerate::make('password'),
            ]);
            $user->assignRole('doctor');
            event(new ActionLoggerEvent(
                'Crear Doctor',
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
                'Actualizar Doctor',
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
            'Eliminar Doctor',
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
            'Restaurar Doctor',
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
            'Eliminar Permanentemente Doctor',
            Doctor::class,
            auth()->guard('web')->user(),
        ));
    }
}
