<?php

namespace App\Filament\Resources\Loggers;

use App\Filament\Resources\Loggers\Pages\ManageLoggers;
use App\Models\Logger;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class LoggerResource extends Resource
{
    protected static ?string $model = Logger::class;
    protected static string|BackedEnum|null $navigationIcon = null;
    protected static ?string $recordTitleAttribute = 'Logger';
    protected static ?int $navigationSort = 3;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return Heroicon::DocumentText;
    }

    public static function getModelLabel(): string
    {
        return 'Sistema de Registro';
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Gestión del Sistema';
    }


    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Logger')
            ->columns([
                TextColumn::make('action')
                    ->label('Acción'),
                TextColumn::make('model')
                    ->label('Modelo'),
                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->numeric(),
                TextColumn::make('created_at')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([])
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageLoggers::route('/'),
        ];
    }
}
