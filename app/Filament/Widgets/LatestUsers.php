<?php

namespace App\Filament\Widgets;

use App\Enums\UserStatus;
use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestUsers extends TableWidget
{
    protected int|string|array $columnSpan = ['md' => 1, 'xl' => 1];

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->heading('آخرین کاربران')
            ->description('جدیدترین کاربران ثبت‌نام‌شده')
            ->query(
                fn (): Builder => User::query()->latest()->limit(5)
            )
            ->paginated(false)
            ->columns([
                TextColumn::make('name')
                    ->label('نام')
                    ->weight('semibold')
                    ->searchable(),

                TextColumn::make('mobile')
                    ->label('موبایل')
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(fn ($state): ?string => $state instanceof UserStatus ? $state->getLabel() : (string) $state)
                    ->color(fn ($state): string|array|null => $state instanceof UserStatus ? $state->getColor() : 'gray'),

                TextColumn::make('created_at')
                    ->label('تاریخ ثبت‌نام')
                    ->since()
                    ->tooltip(fn ($record): string => $record->created_at?->format('Y-m-d H:i') ?? '—'),
            ]);
    }
}
