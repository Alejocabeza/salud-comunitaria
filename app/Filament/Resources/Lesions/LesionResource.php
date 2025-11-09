<?php

namespace App\Filament\Resources\Lesions;

use App\Filament\Resources\Lesions\Pages\CreateLesion;
use App\Filament\Resources\Lesions\Pages\EditLesion;
use App\Filament\Resources\Lesions\Pages\ManageLesions;
use App\Filament\Resources\Lesions\Pages\ViewLesion;
use App\Models\Lesion;
use App\Models\Patient;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class LesionResource extends Resource
{
    protected static ?string $model = Lesion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Heart;

    protected static ?string $recordTitleAttribute = 'Lesion';

    public static function getModelLabel(): string
    {
        return 'Lesiones';
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Recursos de Salud';
    }

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return Heroicon::Heart;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('patient_id')
                ->label('Paciente')
                ->relationship('patient', 'first_name')
                ->getOptionLabelFromRecordUsing(fn (Patient $record) => $record->full_name)
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('type')->label('Tipo')->required(),
            TextInput::make('body_part')->label('Parte afectada')->required(),
            TextInput::make('cause')->label('Causa'),
            DatePicker::make('event_date')->label('Fecha del evento')->maxDate(now())->required(),
            Select::make('severity')->label('Severidad')->options([
                'leve' => 'Leve',
                'moderada' => 'Moderada',
                'grave' => 'Grave',
            ])->required()->native(false),
            Select::make('origin')->label('Origen')->options([
                'domestica' => 'Doméstica',
                'laboral' => 'Laboral',
                'deportiva' => 'Deportiva',
                'transito' => 'Tránsito',
                'otra' => 'Otra',
            ])->required()->native(false),
            Toggle::make('requires_hospitalization')->label('Requiere hospitalización'),
            Select::make('treatment_status')->label('Estado del tratamiento')->options([
                'activa' => 'Activa',
                'resuelta' => 'Resuelta',
            ])->default('activa')->native(false),
            Textarea::make('description')->label('Descripción')->rows(3)->columnSpanFull(),
            Hidden::make('registered_by')->default(fn () => auth()->guard()->id()),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('patient.full_name')->label('Paciente'),
            TextEntry::make('type')->label('Tipo'),
            TextEntry::make('body_part')->label('Parte afectada'),
            TextEntry::make('cause')->label('Causa')->placeholder('-'),
            TextEntry::make('event_date')->label('Fecha')->date(),
            TextEntry::make('severity')->label('Severidad')->badge(),
            TextEntry::make('origin')->label('Origen')->badge(),
            IconEntry::make('requires_hospitalization')->label('Requiere hospitalización')->boolean(),
            TextEntry::make('treatment_status')->label('Tratamiento')->badge(),
            TextEntry::make('description')->label('Descripción')->placeholder('-'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Lesion')
            ->columns([
                TextColumn::make('patient.full_name')->label('Paciente')->searchable()->sortable(),
                TextColumn::make('type')->label('Tipo')->searchable()->sortable(),
                TextColumn::make('body_part')->label('Parte')->searchable()->sortable(),
                BadgeColumn::make('severity')->label('Severidad')->colors([
                    'success' => 'leve',
                    'warning' => 'moderada',
                    'danger' => 'grave',
                ])->sortable(),
                TextColumn::make('origin')->label('Origen')->badge()->sortable(),
                IconColumn::make('requires_hospitalization')->label('Hospitalización')->boolean(),
                TextColumn::make('event_date')->label('Fecha')->date()->sortable(),
                TextColumn::make('treatment_status')->label('Tratamiento')->badge()->sortable(),
            ])
            ->filters([
                SelectFilter::make('severity')->label('Severidad')->options([
                    'leve' => 'Leve',
                    'moderada' => 'Moderada',
                    'grave' => 'Grave',
                ]),
                SelectFilter::make('origin')->label('Origen')->options([
                    'domestica' => 'Doméstica',
                    'laboral' => 'Laboral',
                    'deportiva' => 'Deportiva',
                    'transito' => 'Tránsito',
                    'otra' => 'Otra',
                ]),
                Filter::make('requires_hospitalization')->label('Requiere hospitalización')->query(fn (Builder $q) => $q->where('requires_hospitalization', true)),
            ])
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
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageLesions::route('/'),
            'create' => CreateLesion::route('/create'),
            'view' => ViewLesion::route('/{record}'),
            'edit' => EditLesion::route('/{record}/edit'),
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

        return $user->can('Create:Lesion');
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->guard()->user();
        if (! $user) {
            return false;
        }

        return $user->can('ViewAll:Lesion');
    }
}
