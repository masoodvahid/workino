<?php

namespace App\Filament\Resources\Discounts;

use App\Filament\Resources\Discounts\Pages\CreateDiscount;
use App\Filament\Resources\Discounts\Pages\EditDiscount;
use App\Filament\Resources\Discounts\Pages\ListDiscounts;
use App\Filament\Resources\Discounts\Schemas\DiscountForm;
use App\Filament\Resources\Discounts\Tables\DiscountsTable;
use App\Models\Discount;
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

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CpuChip;

    protected static ?string $navigationLabel = 'تخفیف‌ها';

    protected static ?string $modelLabel = 'تخفیف';

    protected static ?string $pluralModelLabel = 'تخفیف‌ها';

    protected static string|UnitEnum|null $navigationGroup = 'رزرو';

    public static function form(Schema $schema): Schema
    {
        return DiscountForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DiscountsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDiscounts::route('/'),
            'create' => CreateDiscount::route('/create'),
            'edit' => EditDiscount::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['space'])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

        $user = static::currentUser();

        if ($user instanceof User && $user->isSpaceUser()) {
            $query->whereHas('space.spaceUsers', fn (Builder $query): Builder => $query
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

        if (! $user->hasPanelPermission('spaces.update') || ! $record instanceof Discount) {
            return false;
        }

        return $record->space()
            ->whereHas('spaceUsers', fn (Builder $query): Builder => $query
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
