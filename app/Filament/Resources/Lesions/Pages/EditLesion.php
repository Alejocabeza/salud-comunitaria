<?php

namespace App\Filament\Resources\Lesions\Pages;

use App\Filament\Resources\Lesions\LesionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLesion extends EditRecord
{
    protected static string $resource = LesionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
