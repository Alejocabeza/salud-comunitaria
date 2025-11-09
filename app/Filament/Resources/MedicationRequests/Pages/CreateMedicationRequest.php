<?php

namespace App\Filament\Resources\MedicationRequests\Pages;

use App\Filament\Resources\MedicationRequests\MedicationRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMedicationRequest extends CreateRecord
{
    protected static string $resource = MedicationRequestResource::class;
}
