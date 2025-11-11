<?php

namespace App\Filament\Resources\MedicalHistories\Tables;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
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
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('generate_report')
                        ->label('Generar Reporte')
                        ->icon(Heroicon::DocumentArrowDown)
                        ->action(function ($record) {
                            $pdf = Pdf::loadView('reports.medical_history', [
                                'medicalHistory' => $record->load(['patient', 'events.doctor']),
                            ]);

                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->output();
                            }, 'reporte-historial-medico-'.$record->id.'.pdf');
                        }),
                    EditAction::make(),
                    DeleteAction::make(),
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                ])->icon(Heroicon::Bars4),
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
