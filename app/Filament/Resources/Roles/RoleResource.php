<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\Pages\ViewRole;
use App\Filament\Resources\Roles\Schemas\RoleForm;
use App\Filament\Resources\Roles\Tables\RolesTable;
use App\Models\Role;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?string $navigationLabel = 'نقش ها';

    protected static ?string $modelLabel = 'نقش';

    protected static ?string $pluralModelLabel = 'نقش ها';

    protected static string|UnitEnum|null $navigationGroup = 'مدیریت دسترسی';

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'view' => ViewRole::route('/{record}'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return static::currentUser()?->isAdmin() ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canView(Model $record): bool
    {
        return static::currentUser()?->isAdmin() ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return static::currentUser()?->isAdmin() ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    private static function currentUser(): ?User
    {
        $user = auth()->user();

        return $user instanceof User ? $user : null;
    }
}
