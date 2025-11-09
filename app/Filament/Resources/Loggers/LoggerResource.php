<?php

namespace App\Filament\Resources\Loggers;

use App\Filament\Resources\Loggers\Pages\ManageLoggers;
use App\Models\Logger;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class LoggerResource extends Resource
{
    protected static ?string $model = Logger::class;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static ?string $recordTitleAttribute = 'Logger';

    protected static ?int $navigationSort = 4;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return Heroicon::DocumentText;
    }

    public static function getModelLabel(): string
    {
        return 'Logs';
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

    /**
     * Limit the query depending on the current user.
     * Admins see everything; non-admins only see their own log entries.
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->guard()->user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if (method_exists($user, 'hasRole') && ($user->hasRole('Super Admin') || $user->hasRole('super_admin'))) {
            return $query;
        }

        return $query->where('user_id', $user->id);
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->guard()->user();
        if (! $user) {
            return false;
        }

        return $user->can('ViewAll:Logger');
    }
}
