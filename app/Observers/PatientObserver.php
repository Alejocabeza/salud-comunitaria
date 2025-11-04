<?php

namespace App\Observers;

use App\Events\ActionLoggerEvent;
use App\Models\Patient;
use App\Models\User;

class PatientObserver
{
    /**
     * Handle the Patient "created" event.
     */
    public function created(Patient $patient): void
    {
        if ($patient->is_active) {
            $user = User::create([
                'name' => $patient->full_name,
                'email' => $patient->email,
                'password' => bcrypt('password'),
            ]);
            $user->assignRole('Paciente');
            event(new ActionLoggerEvent(
                'Crear Paciente',
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
                'name' => $patient->name,
                'email' => $patient->email,
            ]);
            event(new ActionLoggerEvent(
                'Actualizar Paciente',
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
            'Eliminar Paciente',
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
            'Restaurar Patient',
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
            'Eliminar Permanentemente Patient',
            Patient::class,
            auth()->guard('web')->user(),
        ));
    }
}
