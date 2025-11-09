<?php

namespace App\Filament\Resources\Lesions\Pages;

use App\Filament\Resources\Lesions\LesionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageLesions extends ManageRecords
{
    protected static string $resource = LesionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
