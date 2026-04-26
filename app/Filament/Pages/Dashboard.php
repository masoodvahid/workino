<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminClockWidget;
use App\Models\User;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends \Filament\Pages\Dashboard
{
    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->hasPanelPermission('dashboard.view');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AdminClockWidget::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }

    public function getHeading(): string | Htmlable | null
    {
        return 'WORKINO';
    }
}
