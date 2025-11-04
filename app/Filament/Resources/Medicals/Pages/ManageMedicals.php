<?php

namespace App\Filament\Resources\Medicals\Pages;

use App\Filament\Resources\Medicals\MedicalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMedicals extends ManageRecords
{
    protected static string $resource = MedicalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
