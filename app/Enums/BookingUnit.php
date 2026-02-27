<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BookingUnit: string implements HasLabel
{
    case Hour = 'hour';
    case Day = 'day';
    case Week = 'week';
    case Month = 'month';
    case Year = 'year';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Hour => 'ساعتی',
            self::Day => 'روزانه',
            self::Week => 'هفتگی',
            self::Month => 'ماهانه',
            self::Year => 'سالانه',
        };
    }
}
