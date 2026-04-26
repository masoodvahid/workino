<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Carbon\CarbonImmutable;
use Filament\Widgets\ChartWidget;

class MonthlyRevenueChart extends ChartWidget
{
    protected ?string $heading = 'درآمد ماهانه';

    protected ?string $description = 'مجموع مبلغ رزروها در ۶ ماه اخیر';

    protected int|string|array $columnSpan = ['md' => 2, 'xl' => 1];

    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $months = 6;
        $start = CarbonImmutable::now()->startOfMonth()->subMonths($months - 1);

        $totalsByMonth = Booking::query()
            ->where('created_at', '>=', $start)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, SUM(total_price) as total")
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $labels = [];
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = CarbonImmutable::now()->startOfMonth()->subMonths($i);
            $key = $month->format('Y-m');
            $labels[] = $month->format('M Y');
            $data[] = (float) ($totalsByMonth[$key] ?? 0);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'درآمد (تومان)',
                    'data' => $data,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.55)',
                    'borderColor' => '#d97706',
                    'borderWidth' => 1,
                    'borderRadius' => 8,
                ],
            ],
        ];
    }
}
