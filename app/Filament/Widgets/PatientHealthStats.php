<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Disease;
use App\Models\Lesion;
use App\Models\MedicationRequest;
use App\Models\Patient;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class PatientHealthStats extends ChartWidget
{
    protected ?string $heading = 'Estadísticas de Salud del Paciente';

    protected function getData(): array
    {
        $patientId = Patient::where('email', auth()->guard()->user()->email)->first()->id;

        if (! $patientId) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $cacheKey = "patient-health-stats:{$patientId}";

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($patientId) {
            $totalAppointments = Appointment::where('patient_id', $patientId)->count();
            $activeDiseases = Disease::whereHas('patients', function ($query) use ($patientId) {
                $query->where('patient_id', $patientId)->where('status', 'active');
            })->count();
            $activeLesions = Lesion::where('patient_id', $patientId)->where('treatment_status', 'active')->count();
            $pendingMedications = MedicationRequest::where('patient_id', $patientId)->where('status', 'pending')->count();

            return [
                $totalAppointments,
                $activeDiseases,
                $activeLesions,
                $pendingMedications,
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Estadísticas',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                    ],
                    'borderColor' => [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Citas Totales', 'Enfermedades Activas', 'Lesiones Activas', 'Medicamentos Pendientes'],
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
