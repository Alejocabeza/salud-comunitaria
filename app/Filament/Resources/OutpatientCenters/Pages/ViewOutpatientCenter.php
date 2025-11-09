<?php

namespace App\Filament\Resources\OutpatientCenters\Pages;

use App\Filament\Resources\OutpatientCenters\OutpatientCenterResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOutpatientCenter extends ViewRecord
{
    protected static string $resource = OutpatientCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
