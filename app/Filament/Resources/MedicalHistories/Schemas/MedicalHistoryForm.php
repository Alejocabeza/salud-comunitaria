<?php

namespace App\Filament\Resources\MedicalHistories\Schemas;

use App\Models\Patient;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MedicalHistoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('patient_id')
                    ->default(fn() => Patient::where('email', auth()->guard()->user()->email)->first()->id),
                Hidden::make('medical_history_id')
                    ->default(fn() => Patient::where('email', auth()->guard()->user()->email)->first()->medicalHistory->id),
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
                    ->extraAttributes(['style' => 'min-height: 75px;'])
                    ->required(),

                // Select::make('doctor_id')
                //     ->label('Médico')
                //     ->relationship('doctor', 'full_name')
                //     ->searchable()
                //     ->nullable(),

                FileUpload::make('attachments')
                    ->label('Adjuntos (PDF / imágenes)')
                    ->multiple()
                    ->disk('local')
                    ->directory('medical_histories')
                    ->maxSize(10240)
                    ->acceptedFileTypes(['application/pdf', 'image/png', 'image/jpeg'])
                    ->preserveFilenames(),

                RichEditor::make('notes')
                    ->label('Notas')
                    ->columnSpanFull(),

            ]);
    }
}
