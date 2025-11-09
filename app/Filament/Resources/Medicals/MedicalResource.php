<?php

namespace App\Filament\Resources\Medicals;

use App\Filament\Resources\Medicals\Pages\CreateMedical;
use App\Filament\Resources\Medicals\Pages\EditMedical;
use App\Filament\Resources\Medicals\Pages\ManageMedicals;
use App\Filament\Resources\Medicals\Pages\ViewMedical;
use App\Models\MedicalResource as Medical;
use BackedEnum;
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
use Filament\Forms\Components\RichEditor;
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
use UnitEnum;

class MedicalResource extends Resource
{
    protected static ?string $model = Medical::class;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static ?string $recordTitleAttribute = 'MedicalResource';

    protected static ?int $navigationSort = 3;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return Heroicon::PresentationChartBar;
    }

    public static function getModelLabel(): string
    {
        return 'Medicamentos';
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Gestión de Recursos';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU')
                    ->required(),
                TextInput::make('quantity')
                    ->label('Cantidad')
                    ->required(),
                TextInput::make('unit')
                    ->label('Unidad')
                    ->required(),
                TextInput::make('expiry_date')
                    ->label('Fecha de Expiración')
                    ->required(),
                Checkbox::make('available_to_public')
                    ->columnSpanFull()
                    ->label('Disponible para el Público')
                    ->required(),
                RichEditor::make('description')
                    ->columnSpanFull()
                    ->label('Descripción')
                    ->required()
                    ->extraAttributes(['style' => 'min-height: 200px;']),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Recurso Médico'),
                TextEntry::make('sku')
                    ->label('SKU'),
                TextEntry::make('quantity')
                    ->label('Cantidad'),
                TextEntry::make('unit')
                    ->label('Unidad'),
                TextEntry::make('expiry_date')
                    ->label('Fecha de Expiración'),
                IconEntry::make('available_to_public')
                    ->label('Disponible para el Público'),
                TextEntry::make('description')
                    ->columnSpanFull()
                    ->label('Descripción'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('MedicalResource')
            ->columns([
                TextColumn::make('name')
                    ->label('Recurso Médico')
                    ->searchable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit')
                    ->label('Unidad')
                    ->searchable(),
                TextColumn::make('expiry_date')
                    ->label('Fecha de Expiración')
                    ->date()
                    ->sortable(),
                IconColumn::make('available_to_public')
                    ->label('Disponible para el Público')
                    ->boolean(),
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
            'index' => ManageMedicals::route('/'),
            'create' => CreateMedical::route('/create'),
            'view' => ViewMedical::route('/{record}'),
            'edit' => EditMedical::route('/{record}/edit'),
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

        return $user->can('Create:MedicalResource');
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->guard()->user();
        if (! $user) {
            return false;
        }

        return $user->can('ViewAll:MedicalResource');
    }
}
