<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class AdminClockWidget extends Widget
{
    protected static bool $isDiscovered = false;

    protected string $view = 'filament.widgets.admin-clock-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -100;

    protected function getViewData(): array
    {
        return [
            'todayLabel' => verta(now())->format('l j F Y'),
        ];
    }
}
