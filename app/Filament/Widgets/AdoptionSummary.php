<?php

namespace App\Filament\Widgets;

use App\Models\OutpatientCenter;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdoptionSummary extends ChartWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Nuevos centros (últimos 12 meses)';

    protected static bool $isLazy = false;

    protected ?string $pollingInterval = '60s';

    protected function getData(): array
    {
        $data = Cache::remember('superadmin:adoption_trend_12m', 60, function () {
            $start = now()->subMonths(11)->startOfMonth();

            $driver = DB::getDriverName();
            if ($driver === 'pgsql') {
                $formatExpr = "to_char(created_at::timestamp, 'YYYY-MM')";
            } elseif ($driver === 'sqlite') {
                $formatExpr = "strftime('%Y-%m', created_at)";
            } else {
                $formatExpr = "DATE_FORMAT(created_at, '%Y-%m')";
            }

            $rows = OutpatientCenter::selectRaw("{$formatExpr} as month, count(*) as total")
                ->where('created_at', '>=', $start)
                ->groupBy(DB::raw($formatExpr))
                ->orderByRaw($formatExpr)
                ->get()
                ->keyBy('month');

            $labels = [];
            $values = [];

            for ($i = 0; $i < 12; $i++) {
                $m = $start->copy()->addMonths($i);
                $key = $m->format('Y-m');
                $labels[] = $m->format('M Y');
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
                    'label' => 'Nuevos Centros Ambulatorios',
                    'data' => $data['data'],
                    'backgroundColor' => 'rgba(59,130,246,0.5)',
                    'borderColor' => 'rgba(59,130,246,1)',
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
