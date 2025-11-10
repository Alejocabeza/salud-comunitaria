<?php

namespace App\Filament\Resources\MedicalHistories\Pages;

use App\Filament\Resources\MedicalHistories\MedicalHistoryResource;
use Filament\Actions\DeleteAction;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewMedicalHistory extends ViewRecord implements HasInfolists
{
    protected static string $resource = MedicalHistoryResource::class;

    protected string $view = 'livewire.filament.resources.medical-histories.pages.view-medical-history';

    public ?string $activeTab = 'patient';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Paciente')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('patient.full_name')
                            ->label('Nombre Completo'),
                        TextEntry::make('patient.email')
                            ->label('Correo Electrónico'),
                        TextEntry::make('patient.phone')
                            ->label('Teléfono'),
                        TextEntry::make('patient.address')
                            ->label('Dirección'),
                        TextEntry::make('patient.dni')
                            ->label('Cédula'),
                        TextEntry::make('patient.weight')
                            ->label('Peso (kg)'),
                        TextEntry::make('patient.age')
                            ->label('Edad (años)'),
                        TextEntry::make('patient.blood_type')
                            ->label('Tipo de Sangre'),
                        IconEntry::make('patient.is_active')
                            ->label('Activo')
                            ->boolean(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
