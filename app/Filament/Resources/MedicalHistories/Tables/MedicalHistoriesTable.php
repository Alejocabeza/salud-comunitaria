<?php

namespace App\Filament\Resources\MedicalHistories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class MedicalHistoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.full_name')->label('Paciente')->searchable(),
                TextColumn::make('events_count')
                    ->label('Eventos')
                    ->getStateUsing(fn ($record) => $record->events()->count()),
                TextColumn::make('last_event_date')
                    ->label('Último Evento')
                    ->getStateUsing(fn ($record) => optional($record->events()->orderByDesc('date')->first())->date)->date(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
