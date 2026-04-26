<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;

class AdminWelcomeBanner extends Widget
{
    protected string $view = 'filament.widgets.admin-welcome-banner';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -10;

    public function render(): View
    {
        $hour = (int) now()->format('H');

        $greeting = match (true) {
            $hour < 12 => 'صبح بخیر',
            $hour < 17 => 'ظهر بخیر',
            $hour < 20 => 'عصر بخیر',
            default => 'شب بخیر',
        };

        return view($this->view, [
            'greeting' => $greeting,
            'name' => auth()->user()?->name ?? 'کاربر',
            'jalaliDate' => $this->jalaliDate(),
            'gregorianTime' => now()->format('H:i'),
        ]);
    }

    private function jalaliDate(): string
    {
        $now = now();

        [$jy, $jm, $jd] = $this->gregorianToJalali(
            (int) $now->format('Y'),
            (int) $now->format('m'),
            (int) $now->format('d'),
        );

        $months = [
            1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد', 4 => 'تیر',
            5 => 'مرداد', 6 => 'شهریور', 7 => 'مهر', 8 => 'آبان',
            9 => 'آذر', 10 => 'دی', 11 => 'بهمن', 12 => 'اسفند',
        ];

        $weekDays = [
            'Saturday' => 'شنبه', 'Sunday' => 'یکشنبه', 'Monday' => 'دوشنبه',
            'Tuesday' => 'سه‌شنبه', 'Wednesday' => 'چهارشنبه',
            'Thursday' => 'پنجشنبه', 'Friday' => 'جمعه',
        ];

        $weekDay = $weekDays[$now->format('l')] ?? '';

        return sprintf('%s، %d %s %d', $weekDay, $jd, $months[$jm], $jy);
    }

    /**
     * @return array{0:int,1:int,2:int}
     */
    private function gregorianToJalali(int $gy, int $gm, int $gd): array
    {
        $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];

        $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
        $days = 355666 + (365 * $gy) + ((int) (($gy2 + 3) / 4))
            - ((int) (($gy2 + 99) / 100)) + ((int) (($gy2 + 399) / 400))
            + $gd + $g_d_m[$gm - 1];

        $jy = -1595 + (33 * ((int) ($days / 12053)));
        $days %= 12053;

        $jy += 4 * ((int) ($days / 1461));
        $days %= 1461;

        if ($days > 365) {
            $jy += (int) (($days - 1) / 365);
            $days = ($days - 1) % 365;
        }

        if ($days < 186) {
            $jm = 1 + (int) ($days / 31);
            $jd = 1 + ($days % 31);
        } else {
            $jm = 7 + (int) (($days - 186) / 30);
            $jd = 1 + (($days - 186) % 30);
        }

        return [$jy, $jm, $jd];
    }
}
