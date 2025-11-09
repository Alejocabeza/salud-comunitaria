<?php

namespace App\Filament\Resources\OutpatientCenters\Pages;

use App\Filament\Resources\OutpatientCenters\OutpatientCenterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOutpatientCenter extends CreateRecord
{
    protected static string $resource = OutpatientCenterResource::class;

    /**
     * Redirect to the edit page after creating so relation managers are visible.
     */
    protected function getRedirectUrl(): string
    {
        return OutpatientCenterResource::getUrl('view', ['record' => $this->record]);
    }
}
