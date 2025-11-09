<?php

namespace App\Filament\Resources\Patients\Pages;

use App\Filament\Resources\Patients\PatientResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePatient extends CreateRecord
{
    protected static string $resource = PatientResource::class;

    /**
     * Redirect to the edit page after creating so relation managers are visible.
     */
    protected function getRedirectUrl(): string
    {
        return PatientResource::getUrl('view', ['record' => $this->record]);
    }
}
