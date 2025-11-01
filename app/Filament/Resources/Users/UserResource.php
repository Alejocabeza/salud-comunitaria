<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string|BackedEnum|null $navigationIcon = null;
    protected static ?int $navigationSort = 1;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return Heroicon::UserGroup;
    }

    protected static ?string $recordTitleAttribute = 'User';

    public static function getModelLabel(): string
    {
        return 'Usuario';
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Recursos Humanos';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->confirmed()
                    ->required(),
                TextInput::make('password_confirmation')
                    ->password()
                    ->label('Confirmar Contraseña')
                    ->required()
                    ->same('password'),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')->label('Nombre'),
                TextEntry::make('email')
                    ->label('Correo Electrónico'),
                TextEntry::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('User')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable(),
                IconColumn::make('active')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([])
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUsers::route('/'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }
        return $user->can('ViewAny:User');
    }
}
