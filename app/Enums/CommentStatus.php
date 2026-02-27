<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CommentStatus: string implements HasColor, HasLabel
{
    case Pending = 'pending';
    case Approve = 'approve';
    case Reject = 'reject';
    case Spam = 'spam';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => 'در انتظار',
            self::Approve => 'تایید',
            self::Reject => 'رد',
            self::Spam => 'اسپم',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Approve => 'success',
            self::Reject => 'danger',
            self::Spam => 'gray',
        };
    }
}
