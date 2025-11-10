<?php

namespace App\Filament\Resources\MedicationRequests;

use App\Filament\Resources\MedicationRequests\Pages\CreateMedicationRequest;
use App\Filament\Resources\MedicationRequests\Pages\EditMedicationRequest;
use App\Filament\Resources\MedicationRequests\Pages\ManageMedicationRequests;
use App\Filament\Resources\MedicationRequests\Pages\ViewMedicationRequest;
use App\Models\MedicationRequest;
use App\Models\Patient;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class MedicationRequestResource extends Resource
{
    protected static ?string $model = MedicationRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'MedicationRequest';

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return Heroicon::BuildingOffice2;
    }

    public static function getModelLabel(): string
    {
        return 'Solicitud de Medicamento';
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Recursos de Salud';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('patient_id')
                    ->default(fn () => Patient::where('email', auth()->guard()->user()->email)->first()->id),

                Hidden::make('outpatient_center_id')
                    ->default(fn () => Patient::where('email', auth()->guard()->user()->email)->first()->outpatient_center_id),

                Select::make('medical_resource_id')
                    ->label('Medicamento')
                    ->relationship('medicalResource', 'name')
                    ->live()
                    ->preload()
                    ->searchable()
                    ->required()
                    ->reactive(),
                TextInput::make('quantity')
                    ->label('Cantidad')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('processed_at')
                    ->label('Procesado en')
                    ->default(now())
                    ->readOnly(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('patient.full_name')
                    ->label('Paciente'),
                TextEntry::make('medicalResource.name')
                    ->label('Medicamento'),
                TextEntry::make('quantity')
                    ->label('Cantidad')
                    ->numeric(),
                IconEntry::make('status')->boolean(),
                TextEntry::make('processed_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('MedicationRequest')
            ->columns([
                TextColumn::make('patient.full_name')
                    ->label('Paciente')
                    ->sortable(),
                TextColumn::make('medicalResource.name')
                    ->label('Medicamento')
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->searchable(),
                TextColumn::make('processed_at')
                    ->label('Procesado en')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                ])->icon(Heroicon::Bars4),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMedicationRequests::route('/'),
            'create' => CreateMedicationRequest::route('/create'),
            'view' => ViewMedicationRequest::route('/{record}'),
            'edit' => EditMedicationRequest::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        $user = auth()->guard()->user();
        if (! $user) {
            return false;
        }

        return $user->can('Create:MedicationRequest');
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->guard()->user();
        if (! $user) {
            return false;
        }

        return $user->can('ViewAll:MedicationRequest');
    }
}
