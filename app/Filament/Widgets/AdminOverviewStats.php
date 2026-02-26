<?php

namespace App\Filament\Widgets;

use App\Models\Space;
use App\Models\User;
use Carbon\Carbon;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class AdminOverviewStats extends StatsOverviewWidget
{
    protected ?string $heading = 'نمای کلی';

    protected ?string $description = 'خلاصه کاربران، فضاها و تعداد بازدید';

    protected function getStats(): array
    {
        $totalUsers = User::query()->count();
        $totalSpaces = Space::query()->count();

        $usersLast7Days = User::query()
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $spacesLast7Days = Space::query()
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $viewsByDay = $this->getDailyViewCounts(7);
        $totalViews = array_sum($viewsByDay);

        $usersTrend = $this->getDailyModelCounts(User::class, 7);
        $spacesTrend = $this->getDailyModelCounts(Space::class, 7);

        return [
            Stat::make('کاربران', number_format($totalUsers))
                ->description("+{$usersLast7Days} در ۷ روز اخیر")
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->chart($usersTrend)
                ->color('primary'),

            Stat::make('فضاها', number_format($totalSpaces))
                ->description("+{$spacesLast7Days} در ۷ روز اخیر")
                ->descriptionIcon(Heroicon::OutlinedBuildingOffice2)
                ->chart($spacesTrend)
                ->color('success'),

            Stat::make('تعداد بازدید', number_format((int) $totalViews))
                ->description('بر اساس `view_count` در جدول‌های متا')
                ->descriptionIcon(Heroicon::OutlinedEye)
                ->chart($viewsByDay)
                ->color('warning'),
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
    private function getDailyViewCounts(int $days): array
    {
        $startDate = now()->startOfDay()->subDays($days - 1);

        $bucket = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $bucket[now()->subDays($i)->toDateString()] = 0;
        }

        foreach (['space_meta' => 'space_id', 'subspace_meta' => 'subspace_id'] as $table => $_) {
            $rows = DB::table($table)
                ->where('key', 'view_count')
                ->where('updated_at', '>=', $startDate)
                ->get(['value', 'updated_at']);

            foreach ($rows as $row) {
                $day = Carbon::parse($row->updated_at)->toDateString();

                if (! array_key_exists($day, $bucket)) {
                    continue;
                }

                $bucket[$day] += $this->extractNumericViewValue($row->value);
            }
        }

        return array_map(static fn (int|float $value): float => (float) $value, array_values($bucket));
    }

    private function extractNumericViewValue(mixed $value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            }
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        if (is_array($value)) {
            foreach (['view_count', 'views', 'count', 'value'] as $key) {
                if (isset($value[$key]) && is_numeric($value[$key])) {
                    return (float) $value[$key];
                }
            }

            foreach ($value as $item) {
                if (is_numeric($item)) {
                    return (float) $item;
                }
            }
        }

        return 0.0;
    }
}
