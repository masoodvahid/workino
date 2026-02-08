<?php

namespace App\Filament\Resources\UserMetas;

use App\Filament\Resources\UserMetas\Pages\CreateUserMeta;
use App\Filament\Resources\UserMetas\Pages\EditUserMeta;
use App\Filament\Resources\UserMetas\Pages\ListUserMetas;
use App\Filament\Resources\UserMetas\Schemas\UserMetaForm;
use App\Filament\Resources\UserMetas\Tables\UserMetasTable;
use App\Models\UserMeta;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class UserMetaResource extends Resource
{
    protected static ?string $model = UserMeta::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'فیلدهای پروفایل کاربری';

    protected static ?string $modelLabel = 'فیلد پروفایل کاربری';

    protected static ?string $pluralModelLabel = 'فیلدهای پروفایل کاربری';

    protected static string|UnitEnum|null $navigationGroup = 'تنظیمات سامانه';

    public static function form(Schema $schema): Schema
    {
        return UserMetaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserMetasTable::configure($table);
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
            'index' => ListUserMetas::route('/'),
            'create' => CreateUserMeta::route('/create'),
            'edit' => EditUserMeta::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
