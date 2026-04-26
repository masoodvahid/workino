<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminOverviewStats;
use App\Filament\Widgets\AdminTrendsChart;
use App\Filament\Widgets\AdminWelcomeBanner;
use App\Filament\Widgets\LatestBookings;
use App\Filament\Widgets\LatestUsers;
use App\Filament\Widgets\MonthlyRevenueChart;
use App\Filament\Widgets\PopularSpaces;
use App\Models\User;

class Dashboard extends \Filament\Pages\Dashboard
{
    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->hasPanelPermission('dashboard.view');
    }

    public function getColumns(): int|string|array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 2,
        ];
    }

    public function getWidgets(): array
    {
        return [
            AdminWelcomeBanner::class,
            AdminOverviewStats::class,
            AdminTrendsChart::class,
            MonthlyRevenueChart::class,
            LatestUsers::class,
            LatestBookings::class,
            PopularSpaces::class,
        ];
    }
}
