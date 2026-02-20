<?php

namespace App\Filament\Resources\Spaces\Schemas;

use App\Models\Space;
use Filament\Infolists\Components\ImageEntry;
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
                TextEntry::make('subspaces_count')
                    ->label('تعداد زیرمجموعه ها')
                    ->state(fn (Space $record): int => $record->subSpaces()->count()),
                TextEntry::make('subspaces_list')
                    ->label('زیرمجموعه ها')
                    ->state(function (Space $record): string {
                        if ($record->subSpaces->isEmpty()) {
                            return '-';
                        }

                        return $record->subSpaces
                            ->map(fn ($subSpace): string => "{$subSpace->title} ({$subSpace->type})")
                            ->implode(' | ');
                    })
                    ->columnSpanFull(),
                ImageEntry::make('logo')
                    ->label('نشان (Logo)')
                    ->state(function (Space $record): array {
                        $val = $record->metaValue('logo');

                        return filled($val) ? [$val] : [];
                    })
                    ->disk('public')
                    ->columnSpanFull(),
                ImageEntry::make('featured_image')
                    ->label('تصویر اصلی')
                    ->state(function (Space $record): array {
                        $val = $record->metaValue('featured_image');

                        return filled($val) ? [$val] : [];
                    })
                    ->disk('public')
                    ->columnSpanFull(),
                ImageEntry::make('images')
                    ->label('سایر تصاویر')
                    ->state(function (Space $record): array {
                        $val = $record->metaValue('images');

                        return is_array($val) ? $val : [];
                    })
                    ->disk('public')
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
