<?php

namespace App\Filament\Resources\Diseases;

use App\Filament\Resources\Diseases\Pages\ManageDiseases;
use App\Models\Disease;
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

class DiseaseResource extends Resource
{
    protected static ?string $model = Disease::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Beaker;

    protected static ?string $recordTitleAttribute = 'Disease';

    public static function getModelLabel(): string
    {
        return 'Enfermedades';
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Recursos de Salud';
    }

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return Heroicon::Beaker;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
            TextInput::make('slug')
                ->label('Slug')
                ->disabled()
                ->dehydrated(),
            TextInput::make('icd_code')
                ->label('Código ICD')
                ->maxLength(50),
            TextInput::make('category')
                ->label('Categoría'),
            Textarea::make('description')
                ->label('Descripción')
                ->rows(3)
                ->columnSpanFull(),
            Toggle::make('contagious')
                ->label('Contagiosa')
                ->inline(false),
            Select::make('severity')
                ->label('Severidad')
                ->options([
                    'mild' => 'Leve',
                    'moderate' => 'Moderada',
                    'severe' => 'Severa',
                    'critical' => 'Crítica',
                ])
                ->required()
                ->native(false),
            Toggle::make('active')
                ->label('Activa')
                ->default(true),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('name')->label('Nombre'),
            TextEntry::make('slug')->label('Slug'),
            TextEntry::make('icd_code')->label('Código ICD')->placeholder('-'),
            TextEntry::make('category')->label('Categoría')->placeholder('-'),
            TextEntry::make('description')->label('Descripción')->placeholder('-'),
            IconEntry::make('contagious')->label('Contagiosa')->boolean(),
            TextEntry::make('severity')->label('Severidad')->badge(),
            IconEntry::make('active')->label('Activa')->boolean(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Disease')
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('icd_code')
                    ->label('ICD')
                    ->searchable(),
                TextColumn::make('category')
                    ->label('Categoría')
                    ->searchable(),
                BadgeColumn::make('severity')
                    ->label('Severidad')
                    ->colors([
                        'success' => 'mild',
                        'warning' => 'moderate',
                        'danger' => 'severe',
                        'gray' => 'critical',
                    ])
                    ->sortable(),
                IconColumn::make('contagious')
                    ->label('Contagiosa')
                    ->boolean(),
                IconColumn::make('active')
                    ->label('Activa')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('severity')
                    ->label('Severidad')
                    ->options([
                        'mild' => 'Leve',
                        'moderate' => 'Moderada',
                        'severe' => 'Severa',
                        'critical' => 'Crítica',
                    ]),
                Filter::make('contagious')
                    ->label('Contagiosa')
                    ->query(fn (Builder $q) => $q->where('contagious', true)),
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
            'index' => ManageDiseases::route('/'),
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
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->can('Create:Disease');
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->can('ViewAll:Disease');
    }
}
