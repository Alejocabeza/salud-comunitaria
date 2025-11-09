<?php

namespace App\Filament\Resources\MedicationRequests\Pages;

use App\Filament\Resources\MedicationRequests\MedicationRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMedicationRequests extends ManageRecords
{
    protected static string $resource = MedicationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
