<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Patient;
use Filament\Widgets\ChartWidget;

class UpcomingAppointmentsWidget extends ChartWidget
{
    protected ?string $heading = 'Citas Próximas por Estado';

    protected function getData(): array
    {
        $patientId = Patient::where('email', auth()->guard()->user()->email)->first()->id;

        if (! $patientId) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $statuses = ['pending', 'accepted', 'rejected', 'completed', 'cancelled'];

        $data = [];
        foreach ($statuses as $status) {
            $count = Appointment::where('patient_id', $patientId)
                ->where('requested_date', '>=', now())
                ->where('status', $status)
                ->count();
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Número de Citas',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                    ],
                    'borderColor' => [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Programadas', 'Confirmadas', 'Completadas', 'Canceladas'],
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

        return method_exists($user, 'hasRole') && $user->hasRole('Paciente');
    }
}
