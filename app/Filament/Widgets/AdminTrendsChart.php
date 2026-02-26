<?php

namespace App\Filament\Widgets;

use App\Models\Space;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AdminTrendsChart extends ChartWidget
{
    protected ?string $heading = 'روند ۷ روز اخیر';

    protected ?string $description = 'کاربران، فضاها و تعداد بازدید';

    protected string|int|array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $days = 7;
        $labels = $this->getDayLabels($days);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'کاربران',
                    'data' => $this->getDailyModelCounts(User::class, $days),
                    'borderColor' => '#0ea5e9',
                    'backgroundColor' => 'rgba(14, 165, 233, 0.16)',
                    'tension' => 0.35,
                    'fill' => false,
                ],
                [
                    'label' => 'فضاها',
                    'data' => $this->getDailyModelCounts(Space::class, $days),
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.16)',
                    'tension' => 0.35,
                    'fill' => false,
                ],
                [
                    'label' => 'تعداد بازدید',
                    'data' => $this->getDailyViewCounts($days),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.16)',
                    'tension' => 0.35,
                    'fill' => false,
                ],
            ],
        ];
    }

    /**
     * @return array<int, string>
     */
    private function getDayLabels(int $days): array
    {
        $labels = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $labels[] = now()->subDays($i)->format('M d');
        }

        return $labels;
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

        foreach (['space_meta', 'subspace_meta'] as $table) {
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
