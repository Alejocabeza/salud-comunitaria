<?php

namespace App\Observers;

use App\Events\ActionLoggerEvent;
use App\Helps\PasswordGenerate;
use App\Models\OutpatientCenter;
use App\Models\User;

class OutpatientCenterObserver
{
    /**
     * Handle the OutpatientCenter "created" event.
     */
    public function created(OutpatientCenter $outpatientCenter): void
    {
        if ($outpatientCenter->is_active) {
            $user = User::create([
                'name' => $outpatientCenter->title,
                'email' => $outpatientCenter->email,
                'password' => PasswordGenerate::make('password'),
            ]);
            $user->assignRole('manager');
            event(new ActionLoggerEvent(
                'Crear Centro de Atención Ambulatoria',
                OutpatientCenter::class,
                auth()->guard('web')->user(),
            ));
        }
    }

    /**
     * Handle the OutpatientCenter "updated" event.
     */
    public function updated(OutpatientCenter $outpatientCenter): void
    {
        if ($outpatientCenter->is_active) {
            User::where('email', $outpatientCenter->email)->update([
                'name' => $outpatientCenter->title,
                'email' => $outpatientCenter->email,
            ]);
            event(new ActionLoggerEvent(
                'Actualizar Centro de Atención Ambulatoria',
                OutpatientCenter::class,
                auth()->guard('web')->user(),
            ));
        }
    }

    /**
     * Handle the OutpatientCenter "deleted" event.
     */
    public function deleted(OutpatientCenter $outpatientCenter): void
    {
        User::where('email', $outpatientCenter->email)->update(['active' => false]);
        event(new ActionLoggerEvent(
            'Eliminar Centro de Atención Ambulatoria',
            OutpatientCenter::class,
            auth()->guard('web')->user(),
        ));
    }

    /**
     * Handle the OutpatientCenter "restored" event.
     */
    public function restored(OutpatientCenter $outpatientCenter): void
    {
        User::where('email', $outpatientCenter->email)->update(['active' => true]);
        event(new ActionLoggerEvent(
            'Restaurar Centro de Atención Ambulatoria',
            OutpatientCenter::class,
            auth()->guard('web')->user(),
        ));
    }

    /**
     * Handle the OutpatientCenter "force deleted" event.
     */
    public function forceDeleted(OutpatientCenter $outpatientCenter): void
    {
        User::where('email', $outpatientCenter->email)->delete();
        event(new ActionLoggerEvent(
            'Eliminar Centro de Atención Ambulatoria Permanentemente',
            OutpatientCenter::class,
            auth()->guard('web')->user(),
        ));
    }
}
