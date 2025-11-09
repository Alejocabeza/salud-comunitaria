<?php

namespace App\Filament\Resources\Lesions\Pages;

use App\Filament\Resources\Lesions\LesionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLesion extends CreateRecord
{
    protected static string $resource = LesionResource::class;

    /**
     * Redirect to the edit page after creating so relation managers are visible.
     */
    protected function getRedirectUrl(): string
    {
        return LesionResource::getUrl('view', ['record' => $this->record]);
    }
}
