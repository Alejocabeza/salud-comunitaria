<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithOutpatientCenter;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OutpatientCenterDiseaseBreakdown extends ChartWidget
{
    use InteractsWithOutpatientCenter;

    protected static ?int $sort = 4;

    protected static bool $isLazy = true;

    protected ?string $heading = 'Patologías más frecuentes';

    protected ?string $pollingInterval = '300s';

    protected function getData(): array
    {
        $center = $this->resolveOutpatientCenter();

        if (! $center) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $cacheKey = "dashboard:outpatient-center:diseases:{$center->id}";

        $result = Cache::remember($cacheKey, 300, function () use ($center) {
            return DB::table('patient_disease')
                ->join('patients', 'patient_disease.patient_id', '=', 'patients.id')
                ->join('diseases', 'patient_disease.disease_id', '=', 'diseases.id')
                ->where('patients.outpatient_center_id', $center->id)
                ->whereNull('patients.deleted_at')
                ->whereNull('diseases.deleted_at')
                ->selectRaw('diseases.name as name, count(*) as total')
                ->groupBy('diseases.name')
                ->orderByDesc('total')
                ->get();
        });

        if ($result->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'label' => 'Casos',
                        'data' => [],
                        'backgroundColor' => [],
                    ],
                ],
                'labels' => [],
            ];
        }

        $top = $result->take(5);
        $others = $result->skip(5)->sum('total');

        $labels = $top->pluck('name')->all();
        $data = $top->pluck('total')->all();

        if ($others > 0) {
            $labels[] = 'Otros';
            $data[] = $others;
        }

        $colors = [
            '#2563eb',
            '#f97316',
            '#22c55e',
            '#ec4899',
            '#8b5cf6',
            '#94a3b8',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Casos registrados',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public static function canView(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return method_exists($user, 'hasRole') && $user->hasRole('Manager');
    }
}
