<?php

namespace App\Filament\Resources\MedicalHistories\Schemas;

use App\Models\Patient;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class MedicalHistoryParentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('patient_id')
                ->label('Paciente')
                ->relationship(
                    name: 'patient',
                    titleAttribute: 'first_name',
                    modifyQueryUsing: fn(Builder $query) => $query
                        ->orderBy('first_name')
                        ->orderBy('last_name'),
                )
                ->getOptionLabelFromRecordUsing(fn(Patient $record) => $record->full_name)
                ->searchable(['first_name', 'last_name'])
                ->required(),
        ]);
    }
}
