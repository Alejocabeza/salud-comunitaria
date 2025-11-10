<?php

namespace App\Filament\Resources\MedicalHistories\Pages;

use App\Filament\Resources\MedicalHistories\MedicalHistoryResource;
use App\Models\Patient;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMedicalHistories extends ListRecords
{
    protected static string $resource = MedicalHistoryResource::class;

    public function mount(): void
    {
        parent::mount();

        $user = auth()->user();

        if ($user->hasRole('Paciente')) {
            $patient = Patient::where('dni', $user->dni)->first();

            if ($patient) {
                $medicalHistory = $patient->medicalHistory;

                if ($medicalHistory) {
                    redirect(static::getResource()::getUrl('view', ['record' => $medicalHistory]));
                }
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
