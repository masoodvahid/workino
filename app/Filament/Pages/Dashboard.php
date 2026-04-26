<?php

namespace App\Filament\Pages;

use App\Models\User;

class Dashboard extends \Filament\Pages\Dashboard
{
    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->hasPanelPermission('dashboard.view');
    }
}
