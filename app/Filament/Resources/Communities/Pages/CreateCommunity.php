<?php

namespace App\Filament\Resources\Communities\Pages;

use App\Filament\Resources\Communities\CommunityResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCommunity extends CreateRecord
{
    protected static string $resource = CommunityResource::class;

    /**
     * Redirect to the edit page after creating so relation managers are visible.
     */
    protected function getRedirectUrl(): string
    {
        return CommunityResource::getUrl('view', ['record' => $this->record]);
    }
}
