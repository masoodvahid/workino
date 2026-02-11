<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum City: string implements HasLabel
{
    case Isfahan = 'isfahan';
    case Tehran = 'tehran';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Isfahan => 'اصفهان',
            self::Tehran => 'تهران',
        };
    }
}
