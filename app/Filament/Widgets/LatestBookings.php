<?php

namespace App\Filament\Widgets;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestBookings extends TableWidget
{
    protected int|string|array $columnSpan = ['md' => 1, 'xl' => 1];

    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->heading('آخرین رزروها')
            ->description('جدیدترین رزروهای ثبت‌شده')
            ->query(
                fn (): Builder => Booking::query()
                    ->with(['user', 'subSpace'])
                    ->latest()
                    ->limit(5)
            )
            ->paginated(false)
            ->columns([
                TextColumn::make('user.name')
                    ->label('کاربر')
                    ->weight('semibold')
                    ->placeholder('—'),

                TextColumn::make('subSpace.title')
                    ->label('فضا')
                    ->limit(20)
                    ->placeholder('—'),

                TextColumn::make('total_price')
                    ->label('مبلغ')
                    ->formatStateUsing(fn ($state): string => number_format((int) $state).' تومان'),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(fn ($state): ?string => BookingStatus::tryFrom((string) $state)?->getLabel() ?? (string) $state)
                    ->color(fn ($state): string|array|null => BookingStatus::tryFrom((string) $state)?->getColor() ?? 'gray'),

                TextColumn::make('created_at')
                    ->label('تاریخ')
                    ->since()
                    ->tooltip(fn ($record): string => $record->created_at?->format('Y-m-d H:i') ?? '—'),
            ]);
    }
}
