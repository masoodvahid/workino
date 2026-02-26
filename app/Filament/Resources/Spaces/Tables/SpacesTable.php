<?php

namespace App\Filament\Resources\Spaces\Tables;

use App\Enums\City;
use App\Models\Space;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SpacesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('اسلاگ')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('order')
                    ->label('ترتیب')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('city')
                    ->label('شهر')
                    ->state(fn (Space $record): string => City::tryFrom((string) $record->metaValue('city'))?->getLabel() ?? '-')
                    ->toggleable(),
                TextColumn::make('sub_spaces_count')
                    ->label('تعداد زیرمجموعه')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('users_count')
                    ->label('تعداد کاربران')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('view_count')
                    ->label('تعداد بازدید')
                    ->state(function (Space $record): int {
                        $value = $record->metaValue('view_count');

                        if (is_numeric($value)) {
                            return (int) $value;
                        }

                        if (is_array($value)) {
                            foreach (['view_count', 'views', 'count', 'value'] as $key) {
                                if (isset($value[$key]) && is_numeric($value[$key])) {
                                    return (int) $value[$key];
                                }
                            }
                        }

                        return 0;
                    })
                    ->toggleable(),
                TextColumn::make('deleted_at')
                    ->label('حذف شده در')
                    ->formatStateUsing(fn ($state): string => $state ? verta($state)->format('Y/m/d H:i') : '-')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('ایجاد شده در')
                    ->formatStateUsing(fn ($state): string => $state ? verta($state)->format('Y/m/d H:i') : '-')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('آخرین بروزرسانی')
                    ->formatStateUsing(fn ($state): string => $state ? verta($state)->format('Y/m/d H:i') : '-')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('city')
                    ->label('شهر')
                    ->options(City::class)
                    ->query(function (Builder $query, array $data): Builder {
                        $city = $data['value'] ?? null;

                        if (blank($city)) {
                            return $query;
                        }

                        return $query->whereHas('spaceMetas', fn (Builder $metaQuery): Builder => $metaQuery
                            ->where('key', 'city')
                            ->where('value', $city));
                    }),
                TrashedFilter::make(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
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
