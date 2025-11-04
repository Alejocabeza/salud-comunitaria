<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Logger;
use Illuminate\Support\Facades\Cache;

class TopErrorsWidget extends Widget
{
    protected static ?int $sort = 3;

    protected string $view = 'filament.widgets.top-errors';

    protected static bool $isLazy = true;

    public $top = [];

    public static function canView(): bool
    {
        $user = auth()->user();
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
        $this->top = Cache::remember('superadmin:top_errors', 30, function () {
            return Logger::where('level', 'error')
                ->selectRaw('message, count(*) as cnt')
                ->groupBy('message')
                ->orderByDesc('cnt')
                ->take(10)
                ->get();
        });
    }
}
