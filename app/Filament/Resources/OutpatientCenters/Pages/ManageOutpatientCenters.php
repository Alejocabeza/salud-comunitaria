<?php

namespace App\Filament\Resources\OutpatientCenters\Pages;

use App\Filament\Resources\OutpatientCenters\OutpatientCenterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageOutpatientCenters extends ManageRecords
{
    protected static string $resource = OutpatientCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
