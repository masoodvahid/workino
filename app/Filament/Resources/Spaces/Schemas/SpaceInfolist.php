<?php

namespace App\Filament\Resources\Spaces\Schemas;

use App\Models\Space;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SpaceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                TextEntry::make('title')
                    ->label('عنوان')
                    ->size('lg')
                    ->weight('bold'),
                TextEntry::make('slug')
                    ->label('اسلاگ'),
                TextEntry::make('status')
                    ->label('وضعیت')
                    ->badge(),
                TextEntry::make('order')
                    ->label('ترتیب'),
                TextEntry::make('logo')
                    ->label('نشان (Logo)')
                    ->state(fn (Space $record): mixed => $record->metaValue('logo'))
                    ->default('-')
                    ->columnSpanFull(),
                TextEntry::make('featured_image')
                    ->label('تصویر اصلی')
                    ->state(fn (Space $record): mixed => $record->metaValue('featured_image'))
                    ->default('-')
                    ->columnSpanFull(),
                TextEntry::make('images')
                    ->label('سایر تصاویر')
                    ->state(fn (Space $record): mixed => $record->metaValue('images'))
                    ->formatStateUsing(fn ($state): string => is_array($state) ? implode('، ', $state) : ($state ?? '-'))
                    ->columnSpanFull(),
                TextEntry::make('abstract')
                    ->label('معرفی اولیه')
                    ->state(fn (Space $record): mixed => $record->metaValue('abstract'))
                    ->default('-')
                    ->columnSpanFull(),
                TextEntry::make('content')
                    ->label('متن معرفی کامل')
                    ->state(fn (Space $record): mixed => $record->metaValue('content'))
                    ->default('-')
                    ->columnSpanFull(),
                TextEntry::make('note')
                    ->label('یادداشت')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->label('حذف شده در')
                    ->formatStateUsing(fn ($state): string => $state ? verta($state)->format('Y/m/d H:i') : '-')
                    ->visible(fn (Space $record): bool => $record->trashed()),
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
