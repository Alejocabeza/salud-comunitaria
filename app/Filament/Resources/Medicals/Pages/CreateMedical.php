<?php

namespace App\Filament\Resources\Medicals\Pages;

use App\Filament\Resources\Medicals\MedicalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMedical extends CreateRecord
{
    protected static string $resource = MedicalResource::class;

    /**
     * Redirect to the edit page after creating so relation managers are visible.
     */
    protected function getRedirectUrl(): string
    {
        return MedicalResource::getUrl('view', ['record' => $this->record]);
    }
}
