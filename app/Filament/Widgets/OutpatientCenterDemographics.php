<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithOutpatientCenter;
use App\Models\Patient;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class OutpatientCenterDemographics extends ChartWidget
{
    use InteractsWithOutpatientCenter;

    protected static ?int $sort = 3;

    protected static bool $isLazy = true;

    protected ?string $heading = 'Distribución etaria de pacientes';

    protected ?string $pollingInterval = '300s';

    protected ?string $maxHeight = '420px'; // o la altura que necesites

    protected ?array $options = [
        'maintainAspectRatio' => false,
        'plugins' => [
            'legend' => [
                'position' => 'bottom',
            ],
        ],
    ];

    protected function getData(): array
    {
        $center = $this->resolveOutpatientCenter();

        if (! $center) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $cacheKey = "dashboard:outpatient-center:demographics:{$center->id}";

        return Cache::remember($cacheKey, 300, function () use ($center) {
            $segments = [
                '0-12' => 0,
                '13-18' => 0,
                '19-39' => 0,
                '40-59' => 0,
                '60+' => 0,
                'Sin dato' => 0,
            ];

            Patient::query()
                ->where('outpatient_center_id', $center->id)
                ->get(['age'])
                ->each(function (Patient $patient) use (&$segments) {
                    $age = $patient->age;

                    if ($age === null) {
                        $segments['Sin dato']++;

                        return;
                    }

                    if ($age <= 12) {
                        $segments['0-12']++;

                        return;
                    }

                    if ($age <= 18) {
                        $segments['13-18']++;

                        return;
                    }

                    if ($age <= 39) {
                        $segments['19-39']++;

                        return;
                    }

                    if ($age <= 59) {
                        $segments['40-59']++;

                        return;
                    }

                    $segments['60+']++;
                });

            return [
                'datasets' => [
                    [
                        'label' => 'Pacientes',
                        'data' => array_values($segments),
                        'backgroundColor' => [
                            '#38bdf8',
                            '#22c55e',
                            '#a855f7',
                            '#f97316',
                            '#ef4444',
                            '#94a3b8',
                        ],
                    ],
                ],
                'labels' => array_keys($segments),
            ];
        });
    }

    protected function getType(): string
    {
        return 'doughnut';
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
