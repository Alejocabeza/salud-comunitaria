<?php

namespace App\Filament\Resources\Patients\Pages;

use App\Filament\Resources\Patients\PatientResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;

class EditPatient extends EditRecord implements HasForms
{
    protected static string $resource = PatientResource::class;

    protected string $view = 'livewire.filament.resources.patients.pages.edit-patient';

    public ?string $activeTab = 'patient';

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return PatientResource::form($schema);
    }
}
