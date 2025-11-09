<?php

namespace App\Filament\Resources\Patients\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
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

class LesionsRelationManager extends RelationManager
{
    protected static string $relationship = 'lesions';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('type')->label('Tipo')->required(),
            TextInput::make('body_part')->label('Parte afectada')->required(),
            TextInput::make('cause')->label('Causa'),
            DatePicker::make('event_date')->label('Fecha del evento')->maxDate(now())->required(),
            Select::make('severity')->label('Severidad')->options([
                'leve' => 'Leve',
                'moderada' => 'Moderada',
                'grave' => 'Grave',
            ])->required()->native(false),
            Select::make('origin')->label('Origen')->options([
                'domestica' => 'Doméstica',
                'laboral' => 'Laboral',
                'deportiva' => 'Deportiva',
                'transito' => 'Tránsito',
                'otra' => 'Otra',
            ])->required()->native(false),
            Toggle::make('requires_hospitalization')->label('Requiere hospitalización'),
            Select::make('treatment_status')->label('Tratamiento')->options([
                'activa' => 'Activa',
                'resuelta' => 'Resuelta',
            ])->default('activa')->native(false),
            Textarea::make('description')->label('Descripción')->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                TextColumn::make('type')->label('Tipo')->searchable(),
                TextColumn::make('event_date')->label('Fecha')->date(),
                TextColumn::make('severity')->label('Severidad')->badge(),
                TextColumn::make('origin')->label('Origen')->badge(),
                TextColumn::make('treatment_status')->label('Tratamiento')->badge(),
            ])
            ->headerActions([
                CreateAction::make()->label('Registrar lesión')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['registered_by'] = auth()->guard()->id();

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
