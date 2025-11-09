<?php

namespace App\Filament\Resources\Doctors\Pages;

use App\Filament\Resources\Doctors\DoctorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDoctor extends CreateRecord
{
    protected static string $resource = DoctorResource::class;

    /**
     * Redirect to the edit page after creating so relation managers are visible.
     */
    protected function getRedirectUrl(): string
    {
        return DoctorResource::getUrl('view', ['record' => $this->record]);
    }
}
