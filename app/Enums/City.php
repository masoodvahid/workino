<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum City: string implements HasLabel
{
    case Tehran = 'tehran';
    case Isfahan = 'isfahan';
    case Shiraz = 'shiraz';
    case Mashhad = 'mashhad';
    case Shahrekord = 'shahrekord';
    case Kermanshah = 'kermanshah';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Tehran => 'تهران',
            self::Isfahan => 'اصفهان',
            self::Shiraz => 'شیراز',
            self::Mashhad => 'مشهد',
            self::Shahrekord => 'شهرکرد',
            self::Kermanshah => 'کرمانشاه',
        };
    }
}
