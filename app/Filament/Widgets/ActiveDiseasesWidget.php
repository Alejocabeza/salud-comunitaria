<?php

namespace App\Filament\Widgets;

use App\Models\Disease;
use App\Models\Patient;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;

class ActiveDiseasesWidget extends ChartWidget
{
    protected ?string $heading = 'Enfermedades Activas';

    protected function getData(): array
    {
        $patientId = Patient::where('email', auth()->guard()->user()->email)->first()->id;

        if (! $patientId) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $diseases = Disease::query()
            ->whereHas('patients', function (Builder $query) use ($patientId) {
                $query->where('patient_id', $patientId)
                    ->where('status', 'active');
            })
            ->pluck('name')
            ->toArray();

        $data = array_fill(0, count($diseases), 1); // Each disease has count 1

        return [
            'datasets' => [
                [
                    'label' => 'Enfermedades',
                    'data' => $data,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.8)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $diseases,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
