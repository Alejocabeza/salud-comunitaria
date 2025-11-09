<?php

namespace App\Filament\Resources\Diseases\Pages;

use App\Filament\Resources\Diseases\DiseaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDisease extends CreateRecord
{
    protected static string $resource = DiseaseResource::class;

    /**
     * Redirect to the edit page after creating so relation managers are visible.
     */
    protected function getRedirectUrl(): string
    {
        return DiseaseResource::getUrl('view', ['record' => $this->record]);
    }
}
