<?php

namespace App\Filament\Resources\Prices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PricesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subSpace.space.title')
                    ->label('مرکز')
                    ->searchable(),
                TextColumn::make('subSpace.title')
                    ->label('زیرمجموعه')
                    ->searchable(),
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable(),
                TextColumn::make('unit')
                    ->label('واحد')
                    ->badge(),
                TextColumn::make('base_price')
                    ->label('قیمت پایه')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('special_price')
                    ->label('قیمت ویژه')
                    ->numeric()
                    ->placeholder('-'),
                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('آخرین بروزرسانی')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
