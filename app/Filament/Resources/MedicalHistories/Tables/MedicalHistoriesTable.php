<?php

namespace App\Filament\Resources\MedicalHistories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
                TextColumn::make('date')->label('Fecha')->date(),
                TextColumn::make('type')->label('Tipo')->badge(),
                TextColumn::make('summary')->label('Resumen')->searchable(),
                TextColumn::make('patient.full_name')->label('Paciente')->searchable(),
                TextColumn::make('doctor.full_name')->label('Médico')->searchable(),
                TextColumn::make('attachments')->label('Adjuntos')->getStateUsing(fn ($record) => is_array($record->attachments) ? count($record->attachments) : 0),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
