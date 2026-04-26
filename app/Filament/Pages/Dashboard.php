<?php

namespace App\Filament\Pages;

use App\Models\User;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends \Filament\Pages\Dashboard
{
    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->hasPanelPermission('dashboard.view');
    }

    public function getHeading(): string | Htmlable | null
    {
        return null;
    }
}
