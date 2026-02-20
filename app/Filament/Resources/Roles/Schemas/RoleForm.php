<?php

namespace App\Filament\Resources\Roles\Schemas;

use App\Enums\UserRoleKey;
use App\Models\Role;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                Select::make('key')
                    ->label('کلید نقش')
                    ->options(UserRoleKey::class)
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('title')
                    ->label('عنوان نقش')
                    ->required(),
                Placeholder::make('admin_hint')
                    ->label('توضیحات')
                    ->content('نقش مدیر کل به همه منوها و عملیات دسترسی کامل دارد.')
                    ->visible(fn (Get $get): bool => $get('key') === UserRoleKey::Admin->value),
                Placeholder::make('user_hint')
                    ->label('توضیحات')
                    ->content('نقش کاربر مشتری اجازه ورود به پنل مدیریت را ندارد و فقط فرانت سایت را می بیند.')
                    ->visible(fn (Get $get): bool => $get('key') === UserRoleKey::User->value),
                CheckboxList::make('permissions')
                    ->label('دسترسی منو و عملیات برای کاربر فضای کاری')
                    ->options(Role::SPACE_USER_PERMISSION_OPTIONS)
                    ->helperText('این دسترسی ها فقط برای نقش space_user اعمال می شود.')
                    ->visible(fn (Get $get): bool => $get('key') === UserRoleKey::SpaceUser->value)
                    ->columnSpanFull()
                    ->columns(2),
            ]);
    }
}
