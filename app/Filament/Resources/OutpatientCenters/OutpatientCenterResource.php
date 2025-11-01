<?php

namespace App\Filament\Resources\OutpatientCenters;

use App\Filament\Resources\OutpatientCenters\Pages\ManageOutpatientCenters;
use App\Models\OutpatientCenter;
use BackedEnum;
use BladeUI\Icons\Components\Icon;
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
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use phpDocumentor\Reflection\Types\Boolean;
use UnitEnum;

class OutpatientCenterResource extends Resource
{
    protected static ?string $model = OutpatientCenter::class;
    protected static string|BackedEnum|null $navigationIcon = null;
    protected static ?string $recordTitleAttribute = 'OutpatientCenter';
    protected static ?int $navigationSort = 2;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return Heroicon::BuildingOffice2;
    }

    public static function getModelLabel(): string
    {
        return 'Centro Ambulatorio';
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Recursos de Salud';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Titulo')
                    ->required(),
                TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('phone')
                    ->label('Teléfono')
                    ->required(),
                TextInput::make('responsible')
                    ->label('Responsable')
                    ->required(),
                TextInput::make('address')
                    ->label('Dirección')
                    ->required(),
                TextInput::make('capacity')
                    ->label('Capacidad')
                    ->required()
                    ->numeric(),
                TextInput::make('current_occupancy')
                    ->label('Ocupación Actual')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->maxValue(fn(callable $get) => $get('capacity'))
                    ->helperText('Debe ser menor o igual a la capacidad.'),
                TextInput::make('dni')
                    ->label('RIF')
                    ->required()
                    ->unique(ignoreRecord: true),
                Checkbox::make('is_active')
                    ->columnSpanFull()
                    ->label('¿Activo?')
                    ->default(true),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')->label('Titulo'),
                TextEntry::make('email')
                    ->label('Correo Electrónico'),
                TextEntry::make('phone')
                    ->label('Teléfono'),
                TextEntry::make('responsible')
                    ->label('Responsable'),
                TextEntry::make('dni')
                    ->label('RIF'),
                TextEntry::make('address')
                    ->label('Dirección'),
                TextEntry::make('capacity')
                    ->numeric()
                    ->label('Capacidad'),
                TextEntry::make('current_occupancy')
                    ->numeric()
                    ->label('Ocupación Actual'),
                IconEntry::make('is_active')
                    ->label('¿Activo?')
                    ->boolean(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('OutpatientCenter')
            ->columns([
                TextColumn::make('title')
                    ->label('Titulo')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable(),
                TextColumn::make('responsible')
                    ->label('Responsable')
                    ->searchable(),
                TextColumn::make('dni')
                    ->label('RIF')
                    ->searchable(),
                TextColumn::make('address')
                    ->label('Dirección')
                    ->searchable(),
                TextColumn::make('capacity')
                    ->label('Capacidad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('current_occupancy')
                    ->label('Ocupación Actual')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('¿Activo?')
                    ->boolean(),
                TextColumn::make('deleted_at')
                    ->label('Eliminado el')
                    ->dateTime()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                ])->icon(Heroicon::Bars4)
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
            'index' => ManageOutpatientCenters::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
