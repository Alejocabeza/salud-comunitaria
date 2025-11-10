<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithOutpatientCenter;
use App\Models\Appointment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class OutpatientCenterAppointmentsTrend extends ChartWidget
{
    use InteractsWithOutpatientCenter;

    protected static ?int $sort = 2;

    protected static bool $isLazy = true;

    protected ?string $heading = 'Tendencia de citas (14 días)';

    protected ?string $pollingInterval = '120s';

    protected function getData(): array
    {
        $center = $this->resolveOutpatientCenter();

        if (! $center) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $cacheKey = "dashboard:outpatient-center:appointments-trend:{$center->id}";

        return Cache::remember($cacheKey, 120, function () use ($center) {
            $days = 14;
            $start = now()->subDays($days - 1)->startOfDay();
            $end = now();

            $labels = [];
            for ($i = 0; $i < $days; $i++) {
                $labels[] = $start->copy()->addDays($i)->format('d M');
            }

            $series = [
                'completed' => array_fill(0, $days, 0),
                'in_progress' => array_fill(0, $days, 0),
                'cancelled' => array_fill(0, $days, 0),
            ];

            Appointment::query()
                ->where('outpatient_center_id', $center->id)
                ->whereNotNull('scheduled_date')
                ->whereBetween('scheduled_date', [$start, $end])
                ->get(['scheduled_date', 'status'])
                ->each(function (Appointment $appointment) use (&$series, $start, $days) {
                    $scheduledDay = $appointment->scheduled_date->copy()->startOfDay();
                    $index = $scheduledDay->diffInDays($start);

                    if ($index < 0 || $index >= $days) {
                        return;
                    }

                    if ($appointment->status === 'completed') {
                        $series['completed'][$index]++;

                        return;
                    }

                    if (in_array($appointment->status, ['pending', 'accepted'], true)) {
                        $series['in_progress'][$index]++;

                        return;
                    }

                    if (in_array($appointment->status, ['cancelled', 'rejected'], true)) {
                        $series['cancelled'][$index]++;
                    }
                });

            return [
                'datasets' => [
                    [
                        'label' => 'Completadas',
                        'data' => $series['completed'],
                        'borderColor' => 'rgba(34,197,94,1)',
                        'backgroundColor' => 'rgba(34,197,94,0.2)',
                        'fill' => true,
                        'tension' => 0.4,
                    ],
                    [
                        'label' => 'En proceso',
                        'data' => $series['in_progress'],
                        'borderColor' => 'rgba(59,130,246,1)',
                        'backgroundColor' => 'rgba(59,130,246,0.2)',
                        'fill' => true,
                        'tension' => 0.4,
                    ],
                    [
                        'label' => 'Canceladas/Rechazadas',
                        'data' => $series['cancelled'],
                        'borderColor' => 'rgba(239,68,68,1)',
                        'backgroundColor' => 'rgba(239,68,68,0.2)',
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

        return method_exists($user, 'hasRole') && $user->hasRole('Manager');
    }
}
