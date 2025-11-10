<?php

namespace App\Filament\Widgets;

use App\Models\Logger;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SystemStabilityOverview extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Actividad de logs (últimos 7 días)';

    protected static bool $isLazy = true;

    protected ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $data = Cache::remember('superadmin:logs_trend_7d', 30, function () {
            $start = now()->subDays(6)->startOfDay();

            $driver = DB::getDriverName();

            if ($driver === 'pgsql') {
                $dayExpr = "to_char(created_at, 'YYYY-MM-DD')";
            } elseif ($driver === 'sqlite') {
                $dayExpr = "strftime('%Y-%m-%d', created_at)";
            } else {
                $dayExpr = 'DATE(created_at)';
            }

            $rows = Logger::selectRaw("{$dayExpr} as day, count(*) as total")
                ->where('created_at', '>=', $start)
                ->groupBy('day')
                ->orderBy('day')
                ->get()
                ->keyBy('day');

            $labels = [];
            $values = [];

            for ($i = 0; $i < 7; $i++) {
                $d = $start->copy()->addDays($i);
                $key = $d->toDateString();
                $labels[] = $d->format('d M');
                $values[] = $rows->has($key) ? (int) $rows->get($key)->total : 0;
            }

            return [
                'labels' => $labels,
                'data' => $values,
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Registros',
                    'data' => $data['data'],
                    'backgroundColor' => 'rgba(239,68,68,0.2)',
                    'borderColor' => 'rgba(239,68,68,1)',
                    'fill' => true,
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

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
}
