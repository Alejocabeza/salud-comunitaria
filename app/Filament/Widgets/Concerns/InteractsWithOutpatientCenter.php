<?php

namespace App\Filament\Widgets\Concerns;

use App\Models\Doctor;
use App\Models\OutpatientCenter;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

trait InteractsWithOutpatientCenter
{
    protected function resolveOutpatientCenter(?Authenticatable $user = null): ?OutpatientCenter
    {
        $user ??= Auth::user();

        if (! $user) {
            return null;
        }

        $center = OutpatientCenter::query()
            ->where('email', $user->email)
            ->first();

        if ($center) {
            return $center;
        }

        $doctor = Doctor::query()
            ->where('email', $user->email)
            ->first();

        return $doctor?->outpatientCenter;
    }
}
