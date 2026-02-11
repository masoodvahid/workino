<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserEducation: string implements HasLabel
{
    case Diploma = 'diploma';
    case Associate = 'associate';
    case Bachelor = 'bachelor';
    case Master = 'master';
    case Phd = 'phd';
    case Other = 'other';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Diploma => 'دیپلم',
            self::Associate => 'کاردانی',
            self::Bachelor => 'کارشناسی',
            self::Master => 'کارشناسی ارشد',
            self::Phd => 'دکتری',
            self::Other => 'سایر',
        };
    }
}
