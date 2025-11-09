<?php

namespace App\Filament\Resources\MedicalHistories\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MedicalHistoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('patient_id')
                    ->label('Paciente')
                    ->relationship('patient', 'full_name')
                    ->searchable()
                    ->required(),

                Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'consulta' => 'Consulta',
                        'diagnóstico' => 'Diagnóstico',
                        'examen' => 'Examen',
                        'medicación' => 'Medicación',
                    ])
                    ->required(),

                DatePicker::make('date')
                    ->label('Fecha')
                    ->required(),

                TextInput::make('summary')
                    ->label('Resumen')
                    ->required(),

                Select::make('doctor_id')
                    ->label('Médico')
                    ->relationship('doctor', 'full_name')
                    ->searchable()
                    ->nullable(),

                Textarea::make('notes')
                    ->label('Notas')
                    ->columnSpanFull(),

                FileUpload::make('attachments')
                    ->label('Adjuntos (PDF / imágenes)')
                    ->multiple()
                    ->disk('local')
                    ->directory('medical_histories')
                    ->maxSize(10240)
                    ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg'])
                    ->preserveFilenames(),
            ]);
    }
}
