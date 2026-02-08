<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserStatus: string implements HasColor, HasLabel
{
    case Active = 'active';
    case Ban = 'ban';
    case Deactive = 'deactive';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Active => 'فعال',
            self::Ban => 'مسدود',
            self::Deactive => 'غیرفعال',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Active => 'success',
            self::Ban => 'danger',
            self::Deactive => 'gray',
        };
    }
}
