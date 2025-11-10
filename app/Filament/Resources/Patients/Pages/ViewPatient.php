<?php

namespace App\Filament\Resources\Patients\Pages;

use App\Filament\Resources\Patients\PatientResource;
use App\Filament\Widgets\ActiveDiseasesWidget;
use App\Filament\Widgets\PatientHealthStats;
use App\Filament\Widgets\PendingMedicationRequestsWidget;
use App\Filament\Widgets\UpcomingAppointmentsWidget;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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

    protected function getWidgets(): array
    {
        // Solo mostrar widgets si el usuario tiene el rol 'Paciente'
        if (! Auth::check() || ! Auth::user()->hasRole('Paciente')) {
            return [];
        }

        Session::put('current_patient_view_id', $this->record->id);

        return [
            PatientHealthStats::class,
            UpcomingAppointmentsWidget::class,
            ActiveDiseasesWidget::class,
            PendingMedicationRequestsWidget::class,
        ];
    }
}
