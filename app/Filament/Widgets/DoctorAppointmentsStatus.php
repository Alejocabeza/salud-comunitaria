<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Doctor;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DoctorAppointmentsStatus extends ChartWidget
{
    protected static ?int $sort = 3;

    protected static bool $isLazy = true;

    protected ?string $heading = 'Estado de citas';

    protected ?string $pollingInterval = '300s';

    protected ?string $maxHeight = '420px';

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

        $cacheKey = "dashboard:doctor:appointments-status:{$doctor->id}";

        return Cache::remember($cacheKey, 300, function () use ($doctor) {
            $statuses = [
                'pending' => 0,
                'accepted' => 0,
                'completed' => 0,
                'cancelled' => 0,
                'rejected' => 0,
            ];

            Appointment::query()
                ->where('doctor_id', $doctor->id)
                ->whereBetween('created_at', [now()->subDays(30), now()])
                ->get(['status'])
                ->each(function (Appointment $appointment) use (&$statuses) {
                    $status = $appointment->status;
                    if (isset($statuses[$status])) {
                        $statuses[$status]++;
                    }
                });

            $statusLabels = [
                'pending' => 'Pendientes',
                'accepted' => 'Aceptadas',
                'completed' => 'Completadas',
                'cancelled' => 'Canceladas',
                'rejected' => 'Rechazadas',
            ];

            return [
                'datasets' => [
                    [
                        'label' => 'Citas',
                        'data' => array_values($statuses),
                        'backgroundColor' => [
                            '#f59e0b', // pending - amber
                            '#3b82f6', // accepted - blue
                            '#10b981', // completed - emerald
                            '#6b7280', // cancelled - gray
                            '#ef4444', // rejected - red
                        ],
                    ],
                ],
                'labels' => array_map(fn ($status) => $statusLabels[$status], array_keys($statuses)),
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

        return method_exists($user, 'hasRole') && $user->hasRole('Doctor');
    }
}
