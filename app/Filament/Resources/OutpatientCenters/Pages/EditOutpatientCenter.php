<?php

namespace App\Filament\Resources\OutpatientCenters\Pages;

use App\Filament\Resources\OutpatientCenters\OutpatientCenterResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOutpatientCenter extends EditRecord
{
    protected static string $resource = OutpatientCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
