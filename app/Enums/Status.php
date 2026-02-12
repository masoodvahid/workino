<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Status: string implements HasColor, HasLabel
{
    case Active = 'active';
    case Ban = 'ban';
    case Deactive = 'deactive';
    case Pending = 'pending';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Ban => 'مسدود',
            self::Active => 'فعال',
            self::Deactive => 'غیرفعال',
            self::Pending => 'در انتظار بررسی',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Ban => 'danger',
            self::Active => 'success',
            self::Deactive => 'gray',
            self::Pending => 'warning',
        };
    }
}
