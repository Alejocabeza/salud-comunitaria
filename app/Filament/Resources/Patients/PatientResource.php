<?php

namespace App\Filament\Resources\Patients;

use App\Filament\Resources\Patients\Pages\CreatePatient;
use App\Filament\Resources\Patients\Pages\EditPatient;
use App\Filament\Resources\Patients\Pages\ManagePatients;
use App\Filament\Resources\Patients\Pages\ViewPatient;
use App\Filament\Resources\Patients\RelationManagers\AppointmentsRelationManager;
use App\Filament\Resources\Patients\RelationManagers\DiseasesRelationManager;
use App\Filament\Resources\Patients\RelationManagers\LesionsRelationManager;
use App\Filament\Resources\Patients\RelationManagers\MedicalHistoriesRelationManager;
use App\Models\Doctor;
use App\Models\Patient;
use BackedEnum;
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
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Patient';

    public static function getModelLabel(): string
    {
        return 'Paciente';
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Recursos de Salud';
    }

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return Heroicon::UserPlus;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        Hidden::make('outpatient_center_id')
                            ->default(fn () => Doctor::where('email', auth()->guard()->user()->email)->first()->outpatient_center_id),
                        TextInput::make('first_name')
                            ->label('Nombre')
                            ->required(),
                        TextInput::make('last_name')
                            ->label('Apellido')
                            ->required(),
                        TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->required(),
                        TextInput::make('phone')
                            ->label('Teléfono'),
                        TextInput::make('address')
                            ->label('Dirección'),
                        TextInput::make('dni')
                            ->label('Cedula')
                            ->required(),
                        TextInput::make('weight')
                            ->label('Peso')
                            ->numeric(),
                        TextInput::make('age')
                            ->label('Edad')
                            ->numeric(),
                        TextInput::make('blood_type')
                            ->label('Tipo de Sangre'),
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->columnSpanFull()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Patient')
            ->columns([
                TextColumn::make('full_name')
                    ->label('Nombre Completo')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable(),
                TextColumn::make('address')
                    ->label('Dirección')
                    ->searchable(),
                TextColumn::make('dni')
                    ->label('Cedula')
                    ->searchable(),
                TextColumn::make('weight')
                    ->label('Peso')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('age')
                    ->label('Edad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('blood_type')
                    ->label('Tipo de Sangre')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    Action::make('generate_report')
                        ->label('Generar Reporte')
                        ->icon(Heroicon::DocumentArrowDown)
                        ->action(function (Patient $record) {
                            $pdf = Pdf::loadView('reports.patient', [
                                'patient' => $record->load(['medicalHistories', 'appointments.doctor', 'diseases', 'lesions']),
                            ]);

                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->output();
                            }, 'reporte-paciente-'.$record->id.'.pdf');
                        }),
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

    public static function getPages(): array
    {
        return [
            'index' => ManagePatients::route('/'),
            'create' => CreatePatient::route('/create'),
            'edit' => EditPatient::route('/{record}/edit'),
            'view' => ViewPatient::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            AppointmentsRelationManager::class,
            DiseasesRelationManager::class,
            LesionsRelationManager::class,
            MedicalHistoriesRelationManager::class,
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function canCreate(): bool
    {
        $user = auth()->guard()->user();
        if (! $user) {
            return false;
        }

        return $user->can('Create:Patient');
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->guard()->user();
        if (! $user) {
            return false;
        }

        return $user->can('ViewAll:Patient');
    }
}
