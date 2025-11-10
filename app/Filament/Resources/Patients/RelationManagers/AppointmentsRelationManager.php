<?php

namespace App\Filament\Resources\Patients\RelationManagers;

use App\Models\Appointment;
use App\Models\Doctor;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AppointmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'appointments';

    protected static ?string $title = 'Citas Médicas';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('doctor_id')
                    ->label('Doctor')
                    ->relationship(
                        name: 'doctor',
                        titleAttribute: 'first_name',
                        modifyQueryUsing: fn(Builder $query) => $query
                            ->orderBy('first_name')
                            ->orderBy('last_name'),
                    )
                    ->getOptionLabelFromRecordUsing(fn(Doctor $record) => $record->full_name)
                    ->searchable(['first_name', 'last_name'])
                    ->preload()
                    ->required(),

                DatePicker::make('requested_date')
                    ->label('Fecha Solicitada')
                    ->required()
                    ->minDate(today()),

                TextInput::make('reason')
                    ->label('Motivo de la Consulta')
                    ->required(),

                Textarea::make('patient_notes')
                    ->label('Notas del Paciente')
                    ->rows(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reason')
            ->columns([
                TextColumn::make('doctor.full_name')
                    ->label('Doctor')
                    ->searchable(['doctor.first_name', 'doctor.last_name']),

                TextColumn::make('requested_date')
                    ->label('Fecha Solicitada')
                    ->date('d/m/Y'),

                TextColumn::make('scheduled_date')
                    ->label('Fecha Programada')
                    ->date('d/m/Y H:i')
                    ->placeholder('No programada'),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        'completed' => 'info',
                        'cancelled' => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'accepted' => 'Aceptada',
                        'rejected' => 'Rechazada',
                        'completed' => 'Completada',
                        'cancelled' => 'Cancelada',
                    }),

                TextColumn::make('reason')
                    ->label('Motivo')
                    ->limit(30),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Solicitar Cita')
                    ->modalWidth('lg'),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('cancel')
                        ->label('Cancelar')
                        ->icon('heroicon-o-x-mark')
                        ->color('warning')
                        ->visible(fn(Appointment $record): bool => ! $record->isCompleted())
                        ->requiresConfirmation()
                        ->action(function (Appointment $record): void {
                            $record->cancel(auth()->guard()->user(), 'Cancelada por el paciente');
                        })
                        ->successNotificationTitle('Cita cancelada'),

                    EditAction::make()
                        ->visible(fn(Appointment $record): bool => $record->isPending()),

                    DeleteAction::make()
                        ->visible(fn(Appointment $record): bool => $record->isPending()),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn($records): bool => $records->every->isPending()),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
