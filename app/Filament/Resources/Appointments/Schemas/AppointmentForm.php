<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Models\Doctor;
use App\Models\Patient;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información de la Cita')
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        Hidden::make('patient_id')
                            ->default(fn () => Patient::where('email', auth()->guard()->user()->email)->first()->id ?? null),

                        Select::make('doctor_id')
                            ->label('Doctor')
                            ->relationship(
                                name: 'doctor',
                                titleAttribute: 'first_name',
                                modifyQueryUsing: fn (Builder $query) => $query
                                    ->orderBy('first_name')
                                    ->orderBy('last_name'),
                            )
                            ->getOptionLabelFromRecordUsing(fn (Doctor $record) => $record->full_name)
                            ->searchable(['first_name', 'last_name'])
                            ->preload()
                            ->required(),

                        Select::make('outpatient_center_id')
                            ->label('Centro de Atención')
                            ->relationship('outpatientCenter', 'title')
                            ->searchable()
                            ->preload(),

                        DatePicker::make('requested_date')
                            ->label('Fecha Solicitada')
                            ->default(today())
                            ->required()
                            ->minDate(today()),

                        DateTimePicker::make('scheduled_date')
                            ->label('Fecha Programada')
                            ->visible(fn ($context) => $context === 'edit' && auth()->guard()->user()->hasRole('Doctor')),

                        Select::make('status')
                            ->label('Estado')
                            ->options([
                                'pending' => 'Pendiente',
                                'accepted' => 'Aceptada',
                                'rejected' => 'Rechazada',
                                'completed' => 'Completada',
                                'cancelled' => 'Cancelada',
                            ])
                            ->required()
                            ->visible(fn ($context) => $context === 'edit' && auth()->guard()->user()->hasRole('Doctor')),

                        TextInput::make('reason')
                            ->label('Motivo de la Consulta')
                            ->required(),
                    ]),

                Section::make('Notas')
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        Textarea::make('patient_notes')
                            ->label('Notas del Paciente')
                            ->rows(3),

                        Textarea::make('doctor_notes')
                            ->label('Notas del Doctor')
                            ->rows(3)
                            ->visible(fn ($context) => $context === 'edit' && auth()->guard()->user()->hasRole('Doctor')),
                    ]),

                Hidden::make('created_by')
                    ->default(fn () => auth()->guard()->user()?->id),

                Hidden::make('updated_by')
                    ->default(fn () => auth()->guard()->user()?->id),
            ]);
    }
}
