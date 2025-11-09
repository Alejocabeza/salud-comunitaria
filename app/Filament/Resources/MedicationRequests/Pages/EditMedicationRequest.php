<?php

namespace App\Filament\Resources\MedicationRequests\Pages;

use App\Filament\Resources\MedicationRequests\MedicationRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMedicationRequest extends EditRecord
{
    protected static string $resource = MedicationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
