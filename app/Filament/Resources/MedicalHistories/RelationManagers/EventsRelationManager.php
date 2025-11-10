<?php

namespace App\Filament\Resources\MedicalHistories\RelationManagers;

use App\Filament\Resources\MedicalHistories\Schemas\MedicalHistoryForm;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $recordTitleAttribute = 'summary';

    protected static ?string $title = 'Eventos Médicos';

    public function form(Schema $schema): Schema
    {
        return MedicalHistoryForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')->label('Fecha')->date(),
                TextColumn::make('type')->label('Tipo')->badge(),
                TextColumn::make('summary')->label('Resumen')->searchable(),
                TextColumn::make('has_images_attachments')->label('Adjuntos')->getStateUsing(fn($record) => $record->attachments && count($record->attachments) > 0 ? 'Sí' : 'No'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Agregar Evento Médico')
                    ->authorize(fn() => true),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()->authorize(fn() => true),
                    EditAction::make()->authorize(fn() => true),
                    DeleteAction::make()->authorize(fn() => true),
                    RestoreAction::make()->authorize(fn() => true),
                    ForceDeleteAction::make()->authorize(fn() => true),
                ])->icon(Heroicon::Bars4),
            ]);
    }
}
