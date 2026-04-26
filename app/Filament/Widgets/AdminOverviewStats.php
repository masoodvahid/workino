<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminOverviewStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getColumns(): int
    {
        return 5;
    }

    protected function getStats(): array
    {
        $total = User::query()->count();
        $thisMonth = User::query()->where('created_at', '>=', now()->startOfMonth())->count();
        $thisWeek = User::query()->where('created_at', '>=', now()->startOfWeek())->count();
        $yesterday = User::query()
            ->whereBetween('created_at', [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()])
            ->count();
        $today = User::query()->where('created_at', '>=', now()->startOfDay())->count();

        return [
            Stat::make('کل کاربران', number_format($total))
                ->description('تعداد کل کاربران ثبت‌شده')
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->color('primary'),

            Stat::make('این ماه', number_format($thisMonth))
                ->description('کاربران ثبت‌نام‌شده در ماه جاری')
                ->descriptionIcon(Heroicon::OutlinedCalendarDays)
                ->color('info'),

            Stat::make('این هفته', number_format($thisWeek))
                ->description('کاربران ثبت‌نام‌شده در هفته جاری')
                ->descriptionIcon(Heroicon::OutlinedCalendar)
                ->color('warning'),

            Stat::make('دیروز', number_format($yesterday))
                ->description('کاربران ثبت‌نام‌شده دیروز')
                ->descriptionIcon(Heroicon::OutlinedClock)
                ->color('success'),

            Stat::make('امروز', number_format($today))
                ->description('کاربران ثبت‌نام‌شده امروز')
                ->descriptionIcon(Heroicon::OutlinedSparkles)
                ->color('danger'),
        ];
    }
}
