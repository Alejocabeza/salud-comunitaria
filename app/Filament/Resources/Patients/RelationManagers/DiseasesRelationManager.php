<?php

namespace App\Filament\Resources\Patients\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DiseasesRelationManager extends RelationManager
{
    protected static string $relationship = 'diseases';

    protected static ?string $title = 'Enfermedades';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('icd_code')
                    ->label('Código ICD')
                    ->nullable(),
                TextInput::make('category')
                    ->label('Categoría')
                    ->nullable(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
                Toggle::make('contagious')
                    ->label('Contagiosa'),
                Toggle::make('active')
                    ->label('Activa')
                    ->default(true),

                DatePicker::make('diagnosed_at')
                    ->label('Fecha diagnóstico'),
                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'confirmed' => 'Confirmada',
                        'suspected' => 'Sospechosa',
                        'resolved' => 'Resuelta',
                    ])
                    ->required(),
                Textarea::make('notes')
                    ->label('Notas')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Enfermedad')
                    ->searchable(),
                TextColumn::make('pivot.diagnosed_at')
                    ->label('Diagnóstico')
                    ->date(),
                TextColumn::make('pivot.status')
                    ->label('Estado')
                    ->badge(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Crear enfermedad')
                    ->modalWidth('lg'),
                AttachAction::make()
                    ->label('Asociar enfermedad')
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn(Builder $q) => $q->where('active', true))
                    ->schema([
                        DatePicker::make('diagnosed_at')
                            ->label('Fecha diagnóstico'),
                        Select::make('status')
                            ->label('Estado')
                            ->options([
                                'confirmed' => 'Confirmada',
                                'suspected' => 'Sospechosa',
                                'resolved' => 'Resuelta',
                            ])
                            ->required(),
                        Textarea::make('notes')
                            ->label('Notas')
                            ->columnSpanFull(),
                    ]),
            ])
            ->recordActions([
                DetachAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
