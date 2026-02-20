<?php

namespace App\Filament\Resources\Roles\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id')
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable(),
                TextColumn::make('key')
                    ->label('کلید')
                    ->badge(),
                TextColumn::make('users_count')
                    ->label('تعداد کاربران')
                    ->counts('users'),
                TextColumn::make('updated_at')
                    ->label('آخرین بروزرسانی')
                    ->formatStateUsing(fn ($state): string => $state ? verta($state)->format('Y/m/d H:i') : '-')
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}
