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
            ->components([
                TextEntry::make('mobile')
                    ->label('شماره تماس'),
                TextEntry::make('note')
                    ->label('یادداشت')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('status')
                    ->label('وضعیت')
                    ->badge(),
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
