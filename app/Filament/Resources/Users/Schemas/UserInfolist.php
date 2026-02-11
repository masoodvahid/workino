<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Throwable;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                TextEntry::make('name')
                    ->label('نام')
                    ->formatStateUsing(function ($state, $record) {
                        $prefix = match ($record->type) {
                            'man' => 'آقای',
                            'woman' => 'خانم',
                            'company' => 'شرکت',
                            default => '',
                        };

                        return $prefix
                            ? "{$prefix} {$state}"
                            : $state;
                    })
                    ->size('lg')
                    ->weight('bold'),

                TextEntry::make('mobile')
                    ->label('شماره همراه'),

                TextEntry::make('email')
                    ->label('ایمیل')
                    ->copyable(),

                TextEntry::make('status')
                    ->label('وضعیت')
                    ->badge(),

                TextEntry::make('reg_number')
                    ->label('شماره ثبت')
                    ->state(fn (User $record): ?string => $record->metaValue('reg_number'))
                    ->default('-')
                    ->visible(fn (User $record): bool => $record->type === 'company'),

                TextEntry::make('national_id')
                    ->label(fn (User $record): string => $record->type === 'company' ? 'شناسه ملی' : 'کد ملی')
                    ->state(fn (User $record): ?string => $record->metaValue('national_id'))
                    ->default('-')
                    ->visible(fn (User $record): bool => in_array($record->type, ['man', 'woman', 'company'], true)),
                TextEntry::make('birth_day')
                    ->label('تاریخ تولد')
                    ->state(fn (User $record): ?string => $record->metaValue('birth_day'))
                    ->formatStateUsing(function ($state): string {
                        if (! filled($state)) {
                            return '-';
                        }

                        try {
                            return verta($state)->format('Y/m/d');
                        } catch (Throwable) {
                            return $state;
                        }
                    })
                    ->visible(fn (User $record): bool => in_array($record->type, ['man', 'woman'], true)),
                TextEntry::make('education')
                    ->label('مقطع تحصیلی')
                    ->state(fn (User $record): ?string => $record->metaValue('education'))
                    ->formatStateUsing(fn ($state): string => \App\Enums\UserEducation::tryFrom($state)?->getLabel() ?? $state ?? '-')
                    ->visible(fn (User $record): bool => in_array($record->type, ['man', 'woman'], true)),
                TextEntry::make('major')
                    ->label('رشته تحصیلی')
                    ->state(fn (User $record): ?string => $record->metaValue('major'))
                    ->default('-')
                    ->visible(fn (User $record): bool => in_array($record->type, ['man', 'woman'], true)),
                TextEntry::make('university')
                    ->label('آخرین محل تحصیل')
                    ->state(fn (User $record): ?string => $record->metaValue('university'))
                    ->default('-')
                    ->visible(fn (User $record): bool => in_array($record->type, ['man', 'woman'], true)),

                TextEntry::make('city')
                    ->label('شهر')
                    ->state(fn (User $record): ?string => $record->metaValue('city'))
                    ->formatStateUsing(fn ($state): string => \App\Enums\City::tryFrom($state)?->getLabel() ?? $state ?? '-'),
                TextEntry::make('address')
                    ->label('آدرس')
                    ->state(fn (User $record): ?string => $record->metaValue('address'))
                    ->default('-')
                    ->columnSpanFull(),
                TextEntry::make('postal_code')
                    ->label('کد پستی')
                    ->state(fn (User $record): ?string => $record->metaValue('postal_code'))
                    ->default('-'),

                TextEntry::make('note')
                    ->label('یادداشت')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('deleted_at')
                    ->label('حذف شده در')
                    ->formatStateUsing(fn ($state): string => $state ? verta($state)->format('Y/m/d H:i') : '-')
                    ->visible(fn (User $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->label('ایجاد شده در')
                    ->formatStateUsing(fn ($state): string => $state ? verta($state)->format('Y/m/d H:i') : '-')
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('آخرین بروزرسانی')
                    ->formatStateUsing(fn ($state): string => $state ? verta($state)->format('Y/m/d H:i') : '-')
                    ->placeholder('-'),
            ]);
    }
}
