<?php

namespace App\Filament\Resources\Patients\RelationManagers;

use App\Models\Doctor;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MedicalHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'medicalHistories';

    protected static ?string $title = 'Historial Medico';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                    ->relationship('doctor', 'name')
                    ->searchable()
                    ->nullable()
                    ->default(fn () => optional(Auth::user())->email ? Doctor::where('email', auth()->guard()->user()->email)->value('id') : null)
                    ->disabled(fn () => Auth::user() && Auth::user()->hasRole('Doctor')),

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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('summary')
            ->columns([
                TextColumn::make('date')->label('Fecha')->date(),
                TextColumn::make('type')->label('Tipo')->badge(),
                TextColumn::make('summary')->label('Resumen')->searchable(),
                TextColumn::make('doctor.name')->label('Médico')->searchable(),
                TextColumn::make('attachments')->label('Adjuntos')->getStateUsing(fn ($record) => is_array($record->attachments) ? count($record->attachments) : 0),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Crear registro')
                    ->modalWidth('lg')
                    ->visible(fn (): bool => Auth::user() && Auth::user()->hasRole('Doctor')),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
