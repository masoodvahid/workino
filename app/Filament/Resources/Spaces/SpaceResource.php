<?php

namespace App\Filament\Resources\Spaces;

use App\Filament\Resources\Spaces\Pages\CreateSpace;
use App\Filament\Resources\Spaces\Pages\EditSpace;
use App\Filament\Resources\Spaces\Pages\ListSpaces;
use App\Filament\Resources\Spaces\Pages\ViewSpace;
use App\Filament\Resources\Spaces\Schemas\SpaceForm;
use App\Filament\Resources\Spaces\Schemas\SpaceInfolist;
use App\Filament\Resources\Spaces\Tables\SpacesTable;
use App\Models\Space;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpaceResource extends Resource
{
    protected static ?string $model = Space::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CpuChip;

    protected static ?string $navigationLabel = 'مراکز';

    protected static ?string $modelLabel = 'مرکز';

    protected static ?string $pluralModelLabel = 'مراکز';

    public static function form(Schema $schema): Schema
    {
        return SpaceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SpaceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SpacesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSpaces::route('/'),
            'create' => CreateSpace::route('/create'),
            'view' => ViewSpace::route('/{record}'),
            'edit' => EditSpace::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        $query = parent::getRecordRouteBindingEloquentQuery()
            ->with(['spaceMetas', 'subSpaces'])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

        $user = auth()->user();

        if ($user instanceof User && $user->isSpaceUser()) {
            $query->whereHas('spaceUsers', fn (Builder $query): Builder => $query
                ->where('user_id', $user->id)
                ->where('status', 'active'));
        }

        return $query;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->withCount('subSpaces');
        $user = auth()->user();

        if ($user instanceof User && $user->isSpaceUser()) {
            $query->whereHas('spaceUsers', fn (Builder $query): Builder => $query
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
        return static::currentUser()?->hasPanelPermission('spaces.create') ?? false;
    }

    public static function canView(Model $record): bool
    {
        $user = static::currentUser();

        if (! $user || ! $user->hasPanelPermission('spaces.view')) {
            return false;
        }

        return $user->isAdmin() || $record->spaceUsers()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();
    }

    public static function canEdit(Model $record): bool
    {
        $user = static::currentUser();

        if (! $user || ! $user->hasPanelPermission('spaces.update')) {
            return false;
        }

        return $user->isAdmin() || $record->spaceUsers()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();
    }

    public static function canDelete(Model $record): bool
    {
        $user = static::currentUser();

        if (! $user || ! $user->hasPanelPermission('spaces.delete')) {
            return false;
        }

        return $user->isAdmin() || $record->spaceUsers()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();
    }

    public static function canForceDelete(Model $record): bool
    {
        return static::canDelete($record);
    }

    public static function canRestore(Model $record): bool
    {
        return static::canDelete($record);
    }

    public static function canDeleteAny(): bool
    {
        return static::currentUser()?->hasPanelPermission('spaces.delete') ?? false;
    }

    public static function canForceDeleteAny(): bool
    {
        return static::canDeleteAny();
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
