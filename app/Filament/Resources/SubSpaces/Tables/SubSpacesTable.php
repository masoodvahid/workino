<?php

namespace App\Filament\Resources\SubSpaces\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class SubSpacesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('space.title')
                    ->label('مرکز')
                    ->searchable(),
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('اسلاگ')
                    ->searchable(),
                TextColumn::make('prices_count')
                    ->label('تعداد قیمت')
                    ->state(fn ($record): int => $record->prices->count()),
                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
