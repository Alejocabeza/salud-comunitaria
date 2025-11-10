<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Doctor;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DoctorAppointmentsTrend extends ChartWidget
{
    protected static ?int $sort = 2;

    protected static bool $isLazy = true;

    protected ?string $heading = 'Tendencia de citas completadas';

    protected ?string $pollingInterval = '300s';

    protected ?string $maxHeight = '420px';

    protected ?array $options = [
        'maintainAspectRatio' => false,
        'plugins' => [
            'legend' => [
                'position' => 'bottom',
            ],
        ],
        'scales' => [
            'y' => [
                'beginAtZero' => true,
            ],
        ],
    ];

    protected function getData(): array
    {
        $user = Auth::user();

        if (! $user || ! method_exists($user, 'hasRole') || ! $user->hasRole('Doctor')) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $doctor = Doctor::where('email', $user->email)->first();

        if (! $doctor) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $cacheKey = "dashboard:doctor:appointments-trend:{$doctor->id}";

        return Cache::remember($cacheKey, 300, function () use ($doctor) {
            $data = [];
            $labels = [];

            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $startOfMonth = $date->copy()->startOfMonth();
                $endOfMonth = $date->copy()->endOfMonth();

                $count = Appointment::query()
                    ->where('doctor_id', $doctor->id)
                    ->where('status', 'completed')
                    ->whereBetween('completed_at', [$startOfMonth, $endOfMonth])
                    ->count();

                $data[] = $count;
                $labels[] = $date->format('M Y');
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Citas completadas',
                        'data' => $data,
                        'borderColor' => '#22c55e',
                        'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                        'fill' => true,
                        'tension' => 0.4,
                    ],
                ],
                'labels' => $labels,
            ];
        });
    }

    protected function getType(): string
    {
        return 'line';
    }

    public static function canView(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return method_exists($user, 'hasRole') && $user->hasRole('Doctor');
    }
}
