<?php

namespace App\Filament\Resources\MedicationRequests\Pages;

use App\Filament\Resources\MedicationRequests\MedicationRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMedicationRequest extends ViewRecord
{
    protected static string $resource = MedicationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
