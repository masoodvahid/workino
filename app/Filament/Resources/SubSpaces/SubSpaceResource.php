<?php

namespace App\Filament\Resources\SubSpaces;

use App\Filament\Resources\SubSpaces\Pages\CreateSubSpace;
use App\Filament\Resources\SubSpaces\Pages\EditSubSpace;
use App\Filament\Resources\SubSpaces\Pages\ListSubSpaces;
use App\Filament\Resources\SubSpaces\Schemas\SubSpaceForm;
use App\Filament\Resources\SubSpaces\Tables\SubSpacesTable;
use App\Models\SubSpace;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubSpaceResource extends Resource
{
    protected static ?string $model = SubSpace::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return SubSpaceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubSpacesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubSpaces::route('/'),
            'create' => CreateSubSpace::route('/create'),
            'edit' => EditSubSpace::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['space', 'subSpaceMetas', 'prices'])
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
        return static::currentUser()?->hasPanelPermission('spaces.view_any') ?? false;
    }

    public static function canCreate(): bool
    {
        return static::currentUser()?->hasPanelPermission('spaces.update') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        $user = static::currentUser();

        if (! $user || ! $user->hasPanelPermission('spaces.update')) {
            return false;
        }

        return $user->isAdmin() || $record->space->spaceUsers()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();
    }

    public static function canDelete(Model $record): bool
    {
        return static::canEdit($record);
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

    private static function currentUser(): ?User
    {
        $user = auth()->user();

        return $user instanceof User ? $user : null;
    }
}
