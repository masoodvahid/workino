<?php

namespace App\Filament\Resources\Prices;

use App\Filament\Resources\Prices\Pages\CreatePrice;
use App\Filament\Resources\Prices\Pages\EditPrice;
use App\Filament\Resources\Prices\Pages\ListPrices;
use App\Filament\Resources\Prices\Schemas\PriceForm;
use App\Filament\Resources\Prices\Tables\PricesTable;
use App\Models\Price;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class PriceResource extends Resource
{
    protected static ?string $model = Price::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CpuChip;

    protected static ?string $navigationLabel = 'قیمت‌گذاری';

    protected static ?string $modelLabel = 'قیمت';

    protected static ?string $pluralModelLabel = 'قیمت‌ها';

    protected static string|UnitEnum|null $navigationGroup = 'رزرو';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return PriceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PricesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPrices::route('/'),
            'create' => CreatePrice::route('/create'),
            'edit' => EditPrice::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['subSpace.space'])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

        $user = static::currentUser();

        if ($user instanceof User && $user->isSpaceUser()) {
            $query->whereHas('subSpace.space.spaceUsers', fn (Builder $query): Builder => $query
                ->where('user_id', $user->id)
                ->where('status', 'active'));
        }

        return $query;
    }

    public static function canViewAny(): bool
    {
        $user = static::currentUser();

        if (! $user) {
            return false;
        }

        return $user->isAdmin() || $user->hasPanelPermission('spaces.view_any');
    }

    public static function canCreate(): bool
    {
        $user = static::currentUser();

        if (! $user) {
            return false;
        }

        return $user->isAdmin() || $user->hasPanelPermission('spaces.update');
    }

    public static function canEdit(Model $record): bool
    {
        return static::canMutateRecord($record);
    }

    public static function canDelete(Model $record): bool
    {
        return static::canMutateRecord($record);
    }

    public static function canDeleteAny(): bool
    {
        return static::canCreate();
    }

    public static function canForceDelete(Model $record): bool
    {
        return static::canDelete($record);
    }

    public static function canForceDeleteAny(): bool
    {
        return static::canDeleteAny();
    }

    public static function canRestore(Model $record): bool
    {
        return static::canDelete($record);
    }

    public static function canRestoreAny(): bool
    {
        return static::canDeleteAny();
    }

    private static function canMutateRecord(Model $record): bool
    {
        $user = static::currentUser();

        if (! $user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if (! $user->hasPanelPermission('spaces.update') || ! $record instanceof Price) {
            return false;
        }

        return $record->subSpace()
            ->whereHas('space.spaceUsers', fn (Builder $query): Builder => $query
                ->where('user_id', $user->id)
                ->where('status', 'active'))
            ->exists();
    }

    private static function currentUser(): ?User
    {
        $user = auth()->user();

        return $user instanceof User ? $user : null;
    }
}
