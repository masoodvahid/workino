<?php

namespace App\Filament\Resources\Spaces\Schemas;

use App\Enums\City;
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
            ]);
    }
}
