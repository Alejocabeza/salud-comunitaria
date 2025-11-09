<?php

namespace App\Filament\Resources\MedicationRequests\Pages;

use App\Filament\Resources\MedicationRequests\MedicationRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMedicationRequest extends CreateRecord
{
    protected static string $resource = MedicationRequestResource::class;

    /**
     * Redirect to the edit page after creating so relation managers are visible.
     */
    protected function getRedirectUrl(): string
    {
        return MedicationRequestResource::getUrl('view', ['record' => $this->record]);
    }
}
