<?php

namespace App\Filament\Widgets;

use App\Models\Logger;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Cache;

class RecentErrorsWidget extends Widget
{
    protected static ?int $sort = 4;

    protected string $view = 'filament.widgets.recent-errors';

    protected static bool $isLazy = true;

    public $recent = [];

    public static function canView(): bool
    {
        $user = auth()->guard()->user();
        if (! $user) {
            return false;
        }
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole('Super Admin') || $user->hasRole('super_admin');
        }

        return false;
    }

    public function mount(): void
    {
        $this->recent = Cache::remember('superadmin:recent_errors', 30, function () {
            return Logger::where('level', 'error')->latest()->take(10)->get();
        });
    }
}
