<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
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
