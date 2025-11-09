<?php

namespace App\Filament\Resources\MedicalHistories;

use App\Filament\Resources\MedicalHistories\Pages\CreateMedicalHistory;
use App\Filament\Resources\MedicalHistories\Pages\EditMedicalHistory;
use App\Filament\Resources\MedicalHistories\Pages\ListMedicalHistories;
use App\Filament\Resources\MedicalHistories\Schemas\MedicalHistoryForm;
use App\Filament\Resources\MedicalHistories\Tables\MedicalHistoriesTable;
use App\Models\MedicalHistory;
use BackedEnum;
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
        return MedicalHistoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MedicalHistoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMedicalHistories::route('/'),
            'create' => CreateMedicalHistory::route('/create'),
            'edit' => EditMedicalHistory::route('/{record}/edit'),
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

        return $user->can('ViewAll:MedicalHistoryResource');
    }
}
