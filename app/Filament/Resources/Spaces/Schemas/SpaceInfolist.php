<?php

namespace App\Filament\Resources\Spaces\Schemas;

use App\Enums\City;
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
                TextEntry::make('city')
                    ->label('شهر')
                    ->state(fn (Space $record): string => City::tryFrom((string) $record->metaValue('city'))?->getLabel() ?? '-'),             
                TextEntry::make('status')
                    ->label('وضعیت')
                    ->badge(),               
                TextEntry::make('slug')
                    ->label('url')
                    ->url(fn (Space $record): string => route('spaces.show', $record->slug))
                    ->openUrlInNewTab(),
                TextEntry::make('order')
                    ->label('ترتیب نمایش'),
                TextEntry::make('subspaces_count')
                    ->label('تعداد زیرمجموعه ها')
                    ->state(fn (Space $record): int => $record->subSpaces()->count()),
                TextEntry::make('location_neshan')
                    ->label('لوکیشن در نشان')
                    ->state(fn (Space $record): mixed => $record->metaValue('location_neshan'))
                    ->formatStateUsing(fn ($state): string => filled($state) ? 'مسیریابی' : '-')
                    ->url(fn (Space $record): ?string => $record->metaValue('location_neshan'))
                    ->openUrlInNewTab()
                    ->default('-'),
                TextEntry::make('social_buttons')
                    ->label('شبکه‌های اجتماعی')
                    ->state(function (Space $record): string {
                        $social = $record->metaValue('social');

                        if (! is_array($social) || empty($social)) {
                            return '-';
                        }

                        $buttons = collect($social)
                            ->map(function (mixed $item): string {
                                if (! is_array($item)) {
                                    return '';
                                }

                                $title = e((string) ($item['title'] ?? 'لینک'));
                                $url = e((string) ($item['url'] ?? ''));

                                if (blank($url)) {
                                    return '';
                                }

                                return "<a href=\"{$url}\" target=\"_blank\" rel=\"noopener noreferrer\" style=\"display:inline-block;margin:0 0 6px 6px;padding:6px 10px;border:1px solid #d1d5db;border-radius:8px;text-decoration:none;\">{$title}</a>";
                            })
                            ->filter()
                            ->implode(' ');

                        return filled($buttons) ? $buttons : '-';
                    })
                    ->html(),
                TextEntry::make('phones_buttons')
                    ->label('تماس ')
                    ->state(function (Space $record): string {
                        $phones = $record->metaValue('phones');

                        if (! is_array($phones) || empty($phones)) {
                            return '-';
                        }

                        $buttons = collect($phones)
                            ->map(function (mixed $item): string {
                                if (! is_array($item)) {
                                    return '';
                                }

                                $phone = preg_replace('/\s+/', '', (string) ($item['phone_number'] ?? ''));

                                if (blank($phone)) {
                                    return '';
                                }

                                $display = e($phone);
                                $tel = e("tel:{$phone}");

                                return "<a href=\"{$tel}\" style=\"display:inline-block;margin:0 0 6px 6px;padding:6px 10px;border:1px solid #d1d5db;border-radius:8px;text-decoration:none;\">{$display}</a>";
                            })
                            ->filter()
                            ->implode(' ');

                        return filled($buttons) ? $buttons : '-';
                    })
                    ->html(),
                ImageEntry::make('logo')
                    ->label('نشان (Logo)')
                    ->state(function (Space $record): array {
                        $val = $record->metaValue('logo');

                        return filled($val) ? [$val] : [];
                    })
                    ->disk('public'),
                ImageEntry::make('featured_image')
                    ->label('تصویر اصلی')
                    ->state(function (Space $record): array {
                        $val = $record->metaValue('featured_image');

                        return filled($val) ? [$val] : [];
                    })
                    ->disk('public'),
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
