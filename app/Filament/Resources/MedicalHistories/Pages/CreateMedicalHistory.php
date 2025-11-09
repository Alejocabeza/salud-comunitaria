<?php

namespace App\Filament\Resources\MedicalHistories\Pages;

use App\Filament\Resources\MedicalHistories\MedicalHistoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMedicalHistory extends CreateRecord
{
    protected static string $resource = MedicalHistoryResource::class;

    /**
     * Redirect to the edit page after creating so relation managers are visible.
     */
    protected function getRedirectUrl(): string
    {
        return MedicalHistoryResource::getUrl('view', ['record' => $this->record]);
    }
}
