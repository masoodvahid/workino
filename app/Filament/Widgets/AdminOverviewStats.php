<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Space;
use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminOverviewStats extends StatsOverviewWidget
{
    protected ?string $heading = 'نمای کلی';

    protected ?string $description = 'خلاصه کاربران، فضاها، رزروها و درآمد';

    protected static ?int $sort = -5;

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $totalUsers = User::query()->count();
        $totalSpaces = Space::query()->count();
        $totalBookings = Booking::query()->count();
        $totalRevenue = (int) Booking::query()->sum('total_price');

        $usersLast7Days = User::query()->where('created_at', '>=', now()->subDays(7))->count();
        $spacesLast7Days = Space::query()->where('created_at', '>=', now()->subDays(7))->count();
        $bookingsLast7Days = Booking::query()->where('created_at', '>=', now()->subDays(7))->count();
        $revenueLast7Days = (int) Booking::query()
            ->where('created_at', '>=', now()->subDays(7))
            ->sum('total_price');

        $usersTrend = $this->getDailyModelCounts(User::class, 7);
        $spacesTrend = $this->getDailyModelCounts(Space::class, 7);
        $bookingsTrend = $this->getDailyModelCounts(Booking::class, 7);
        $revenueTrend = $this->getDailyRevenueTotals(7);

        return [
            Stat::make('کاربران', number_format($totalUsers))
                ->description("+{$usersLast7Days} در ۷ روز اخیر")
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->chart($usersTrend)
                ->color('info'),

            Stat::make('فضاها', number_format($totalSpaces))
                ->description("+{$spacesLast7Days} در ۷ روز اخیر")
                ->descriptionIcon(Heroicon::OutlinedBuildingOffice2)
                ->chart($spacesTrend)
                ->color('success'),

            Stat::make('رزروها', number_format($totalBookings))
                ->description("+{$bookingsLast7Days} در ۷ روز اخیر")
                ->descriptionIcon(Heroicon::OutlinedCalendarDays)
                ->chart($bookingsTrend)
                ->color('warning'),

            Stat::make('درآمد', number_format($totalRevenue).' تومان')
                ->description('+'.number_format($revenueLast7Days).' در ۷ روز اخیر')
                ->descriptionIcon(Heroicon::OutlinedCurrencyDollar)
                ->chart($revenueTrend)
                ->color('danger'),
        ];
    }

    /**
     * @return array<int, float>
     */
    private function getDailyModelCounts(string $modelClass, int $days): array
    {
        $startDate = now()->startOfDay()->subDays($days - 1);

        $countsByDate = $modelClass::query()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $series = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();
            $series[] = (float) ($countsByDate[$day] ?? 0);
        }

        return $series;
    }

    /**
     * @return array<int, float>
     */
    private function getDailyRevenueTotals(int $days): array
    {
        $startDate = now()->startOfDay()->subDays($days - 1);

        $totalsByDate = Booking::query()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as day, SUM(total_price) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $series = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();
            $series[] = (float) ($totalsByDate[$day] ?? 0);
        }

        return $series;
    }
}
