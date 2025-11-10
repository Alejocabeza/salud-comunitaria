<?php

namespace App\Filament\Resources\MedicalHistories;

use App\Filament\Resources\MedicalHistories\Pages\CreateMedicalHistory;
use App\Filament\Resources\MedicalHistories\Pages\ListMedicalHistories;
use App\Filament\Resources\MedicalHistories\Pages\ViewMedicalHistory;
use App\Filament\Resources\MedicalHistories\RelationManagers\EventsRelationManager;
use App\Filament\Resources\MedicalHistories\Schemas\MedicalHistoryParentForm;
use App\Filament\Resources\MedicalHistories\Tables\MedicalHistoriesTable;
use App\Models\MedicalHistory;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class MedicalHistoryResource extends Resource
{
    protected static ?string $model = MedicalHistory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return Heroicon::ChatBubbleLeft;
    }

    public static function getModelLabel(): string
    {
        return 'Historial Médico';
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Recursos de Salud';
    }

    public static function form(Schema $schema): Schema
    {
        return MedicalHistoryParentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MedicalHistoriesTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('patient.full_name')->label('Paciente'),
                TextEntry::make('events_count')->label('Número de eventos')->getStateUsing(fn ($record) => $record->events()->count()),
                TextEntry::make('last_event')->label('Último evento')->getStateUsing(fn ($record) => optional($record->events()->orderByDesc('date')->first())->summary),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            EventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMedicalHistories::route('/'),
            'create' => CreateMedicalHistory::route('/create'),
            'view' => ViewMedicalHistory::route('/{record}'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->guard()->user();
        if (! $user) {
            return false;
        }

        return $user->can('ViewAll:MedicalHistory');
    }
}
