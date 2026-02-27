<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum InteractableType: string implements HasLabel
{
    case Space = 'space';
    case Subspace = 'subspace';
    case Content = 'content';
    case Comment = 'comment';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Space => 'مرکز',
            self::Subspace => 'زیرمجموعه',
            self::Content => 'محتوا',
            self::Comment => 'نظر',
        };
    }
}
