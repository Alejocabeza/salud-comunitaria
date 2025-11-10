<?php

namespace App\Filament\Widgets;

use App\Models\MedicationRequest;
use App\Models\Patient;
use Filament\Widgets\ChartWidget;

class PendingMedicationRequestsWidget extends ChartWidget
{
    protected ?string $heading = 'Solicitudes de Medicamentos Pendientes';

    protected function getData(): array
    {
        $patientId = Patient::where('email', auth()->guard()->user()->email)->first()->id;

        if (! $patientId) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $medications = MedicationRequest::with('medicalResource')
            ->where('patient_id', $patientId)
            ->where('status', 'pending')
            ->get()
            ->pluck('medicalResource.name')
            ->filter()
            ->values()
            ->toArray();

        $data = array_fill(0, count($medications), 1); // Each medication has count 1

        return [
            'datasets' => [
                [
                    'label' => 'Medicamentos',
                    'data' => $data,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.8)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $medications,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
