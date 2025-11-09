<?php

namespace App\Filament\Resources\Patients\Pages;

use App\Filament\Resources\Patients\PatientResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewPatient extends ViewRecord implements HasInfolists
{
    protected static string $resource = PatientResource::class;

    protected string $view = 'livewire.filament.resources.patients.pages.view-patient';

    public ?string $activeTab = 'patient';

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        TextEntry::make('full_name')
                            ->label('Nombre Completo'),
                        TextEntry::make('email')
                            ->label('Correo Electrónico'),
                        TextEntry::make('phone')
                            ->label('Teléfono'),
                        TextEntry::make('address')
                            ->label('Dirección'),
                        TextEntry::make('dni')
                            ->label('Cédula'),
                        TextEntry::make('weight')
                            ->label('Peso (kg)'),
                        TextEntry::make('age')
                            ->label('Edad (años)'),
                        TextEntry::make('blood_type')
                            ->label('Tipo de Sangre'),
                        IconEntry::make('is_active')
                            ->label('Activo')
                            ->boolean(),
                    ]),
            ]);
    }
}
