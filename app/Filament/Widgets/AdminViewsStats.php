<?php

namespace App\Filament\Widgets;

use Carbon\CarbonInterface;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class AdminViewsStats extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected function getColumns(): int
    {
        return 5;
    }

    protected function getStats(): array
    {
        $total = $this->sumViews();
        $thisMonth = $this->sumViews(now()->startOfMonth());
        $thisWeek = $this->sumViews(now()->startOfWeek());
        $yesterday = $this->sumViews(now()->subDay()->startOfDay(), now()->subDay()->endOfDay());
        $today = $this->sumViews(now()->startOfDay());

        return [
            Stat::make('کل بازدیدها', number_format($total))
                ->description('مجموع بازدید همه فضاها')
                ->descriptionIcon(Heroicon::OutlinedEye)
                ->color('primary'),

            Stat::make('این ماه', number_format($thisMonth))
                ->description('بازدیدهای ماه جاری')
                ->descriptionIcon(Heroicon::OutlinedCalendarDays)
                ->color('info'),

            Stat::make('این هفته', number_format($thisWeek))
                ->description('بازدیدهای هفته جاری')
                ->descriptionIcon(Heroicon::OutlinedCalendar)
                ->color('warning'),

            Stat::make('دیروز', number_format($yesterday))
                ->description('بازدیدهای دیروز')
                ->descriptionIcon(Heroicon::OutlinedClock)
                ->color('success'),

            Stat::make('امروز', number_format($today))
                ->description('بازدیدهای امروز')
                ->descriptionIcon(Heroicon::OutlinedSparkles)
                ->color('danger'),
        ];
    }

    private function sumViews(?CarbonInterface $from = null, ?CarbonInterface $to = null): int
    {
        $total = 0;

        foreach (['space_meta', 'subspace_meta'] as $table) {
            $query = DB::table($table)->where('key', 'view_count');

            if ($from) {
                $query->where('updated_at', '>=', $from);
            }

            if ($to) {
                $query->where('updated_at', '<=', $to);
            }

            foreach ($query->get(['value']) as $row) {
                $total += (int) $this->extractNumericViewValue($row->value);
            }
        }

        return $total;
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
