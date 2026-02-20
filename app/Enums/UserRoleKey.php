<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserRoleKey: string implements HasLabel
{
    case Admin = 'admin';
    case SpaceUser = 'space_user';
    case User = 'user';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Admin => 'مدیر کل',
            self::SpaceUser => 'کاربر فضای کاری',
            self::User => 'کاربر مشتری',
        };
    }
}
