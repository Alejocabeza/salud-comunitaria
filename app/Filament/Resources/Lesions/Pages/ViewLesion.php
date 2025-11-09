<?php

namespace App\Filament\Resources\Lesions\Pages;

use App\Filament\Resources\Lesions\LesionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLesion extends ViewRecord
{
    protected static string $resource = LesionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
