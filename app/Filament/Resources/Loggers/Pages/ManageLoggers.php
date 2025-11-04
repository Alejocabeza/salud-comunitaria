<?php

namespace App\Filament\Resources\Loggers\Pages;

use App\Filament\Resources\Loggers\LoggerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageLoggers extends ManageRecords
{
    protected static string $resource = LoggerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
