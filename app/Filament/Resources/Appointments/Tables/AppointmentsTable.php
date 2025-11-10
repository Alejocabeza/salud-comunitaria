<?php

namespace App\Filament\Resources\Appointments\Tables;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.full_name')
                    ->label('Paciente')
                    ->searchable(['patient.first_name', 'patient.last_name'])
                    ->sortable(['patient.first_name', 'patient.last_name']),

                TextColumn::make('doctor.full_name')
                    ->label('Doctor')
                    ->searchable(['doctor.first_name', 'doctor.last_name'])
                    ->sortable(['doctor.first_name', 'doctor.last_name']),

                TextColumn::make('requested_date')
                    ->label('Fecha Solicitada')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('scheduled_date')
                    ->label('Fecha Programada')
                    ->date('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('No programada'),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        'completed' => 'info',
                        'cancelled' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'accepted' => 'Aceptada',
                        'rejected' => 'Rechazada',
                        'completed' => 'Completada',
                        'cancelled' => 'Cancelada',
                    }),

                TextColumn::make('reason')
                    ->label('Motivo')
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }

                        return $state;
                    }),

                TextColumn::make('outpatientCenter.title')
                    ->label('Centro')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'accepted' => 'Aceptada',
                        'rejected' => 'Rechazada',
                        'completed' => 'Completada',
                        'cancelled' => 'Cancelada',
                    ]),

                SelectFilter::make('doctor')
                    ->label('Doctor')
                    ->relationship(
                        'doctor',
                        'first_name',
                        fn (Builder $query) => $query
                            ->orderBy('first_name')
                            ->orderBy('last_name'),
                    )
                    ->getOptionLabelFromRecordUsing(fn (Doctor $record) => $record->full_name)
                    ->searchable(['first_name', 'last_name']),

                SelectFilter::make('patient')
                    ->label('Paciente')
                    ->relationship(
                        'patient',
                        'first_name',
                        fn (Builder $query) => $query
                            ->orderBy('first_name')
                            ->orderBy('last_name'),
                    )
                    ->getOptionLabelFromRecordUsing(fn (Patient $record) => $record->full_name)
                    ->searchable(['first_name', 'last_name']),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('accept')
                        ->label('Aceptar')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn () => auth()->guard()->user()->hasRole('Doctor'))
                        ->authorize(fn() => true)
                        ->form([
                            \Filament\Forms\Components\DateTimePicker::make('scheduled_date')
                                ->label('Fecha y Hora Programada')
                                ->required()
                                ->minDate(now()),
                            \Filament\Forms\Components\Textarea::make('doctor_notes')
                                ->label('Notas del Doctor')
                                ->rows(3),
                        ])
                        ->action(function (Appointment $record, array $data): void {
                            $record->accept(auth()->guard()->user(), $data['scheduled_date'], $data['doctor_notes']);
                        })
                        ->successNotificationTitle('Cita aceptada exitosamente'),

                    Action::make('reject')
                        ->label('Rechazar')
                        ->icon('heroicon-o-x-circle')
                        ->visible(fn () => auth()->guard()->user()->hasRole('Doctor'))
                        ->color('danger')
                        ->authorize(fn() => true)
                        ->form([
                            \Filament\Forms\Components\Textarea::make('doctor_notes')
                                ->label('Motivo del Rechazo')
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function (Appointment $record, array $data): void {
                            $record->reject(auth()->guard()->user(), $data['doctor_notes']);
                        })
                        ->successNotificationTitle('Cita rechazada'),

                    Action::make('complete')
                        ->label('Marcar como Completada')
                        ->icon('heroicon-o-check-badge')
                        ->visible(fn () => auth()->guard()->user()->hasRole('Doctor'))
                        ->color('info')
                        ->authorize(fn() => true)
                        ->requiresConfirmation()
                        ->action(function (Appointment $record): void {
                            $record->complete(auth()->guard()->user());
                        })
                        ->successNotificationTitle('Cita marcada como completada'),

                    Action::make('cancel')
                        ->label('Cancelar')
                        ->icon('heroicon-o-x-mark')
                        ->color('warning')
                        ->visible(fn () => auth()->guard()->user()->hasRole('Doctor'))
                        ->authorize(fn() => true)
                        ->form([
                            \Filament\Forms\Components\Textarea::make('doctor_notes')
                                ->label('Motivo de la Cancelación')
                                ->rows(3),
                        ])
                        ->action(function (Appointment $record, array $data): void {
                            $record->cancel(auth()->guard()->user(), $data['doctor_notes']);
                        })
                        ->successNotificationTitle('Cita cancelada'),

                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    RestoreAction::make(),
                    ForceDeleteAction::make(),

                ])->icon('heroicon-s-bars-4'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
