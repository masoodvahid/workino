<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Schemas\UserInfolist;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'کاربران';

    protected static ?string $modelLabel = 'کاربر';

    protected static ?string $pluralModelLabel = 'کاربران';

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->with('userMetas')
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function canViewAny(): bool
    {
        return auth()->user() instanceof User && auth()->user()->isAdmin();
    }

    public static function canCreate(): bool
    {
        return auth()->user() instanceof User && auth()->user()->isAdmin();
    }

    public static function canView(Model $record): bool
    {
        return auth()->user() instanceof User && auth()->user()->isAdmin();
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user() instanceof User && auth()->user()->isAdmin();
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user() instanceof User && auth()->user()->isAdmin();
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user() instanceof User && auth()->user()->isAdmin();
    }

    public static function canForceDelete(Model $record): bool
    {
        return auth()->user() instanceof User && auth()->user()->isAdmin();
    }

    public static function canForceDeleteAny(): bool
    {
        return auth()->user() instanceof User && auth()->user()->isAdmin();
    }

    public static function canRestore(Model $record): bool
    {
        return auth()->user() instanceof User && auth()->user()->isAdmin();
    }

    public static function canRestoreAny(): bool
    {
        return auth()->user() instanceof User && auth()->user()->isAdmin();
    }
}
