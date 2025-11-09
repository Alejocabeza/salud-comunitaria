<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Trait FillsCreatedBy
 *
 * Automatically fills the `created_by` attribute on models when they're
 * created, using the currently authenticated user's id when available.
 *
 * Usage:
 *   use App\Models\Traits\FillsCreatedBy;
 *
 * The trait listens to the Eloquent creating event. If running in console
 * (seeders, factories) and there's no authenticated user, it will not set
 * the value unless the model defines a `$createdByFallback` property.
 */
trait FillsCreatedBy
{
    /**
     * Boot the trait and attach the creating listener.
     */
    public static function bootFillsCreatedBy(): void
    {
        static::creating(function (Model $model): void {
            if (! empty($model->created_by)) {
                return;
            }

            if (Auth::check()) {
                $model->created_by = Auth::id();

                return;
            }

            if (app()->runningInConsole() && property_exists($model, 'createdByFallback')) {
                $fallback = $model->createdByFallback;
                if (! empty($fallback)) {
                    $model->created_by = $fallback;
                }
            }
        });
    }
}
