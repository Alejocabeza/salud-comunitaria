<?php

namespace App\Filament\Resources\MedicalHistories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class MedicalHistoryParentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('patient_id')
                ->label('Paciente')
                ->relationship('patient', 'full_name')
                ->searchable()
                ->required(),
        ]);
    }
}
