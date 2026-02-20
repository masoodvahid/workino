<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\City;
use App\Enums\UserEducation;
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

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام')
                    ->searchable(),
                TextColumn::make('mobile')
                    ->label('شماره همراه')
                    ->searchable(),
                TextColumn::make('role.title')
                    ->label('نقش دسترسی')
                    ->badge()
                    ->default('-'),
                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->searchable(),
                TextColumn::make('deleted_at')
                    ->label('حذف شده در')
                    ->formatStateUsing(fn ($state): string => $state ? verta($state)->format('Y/m/d H:i') : '-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('ایجاد شده در')
                    ->formatStateUsing(fn ($state): string => $state ? verta($state)->format('Y/m/d H:i') : '-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('آخرین بروزرسانی')
                    ->formatStateUsing(fn ($state): string => $state ? verta($state)->format('Y/m/d H:i') : '-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('ماهیت')
                    ->options([
                        'man' => 'آقا',
                        'woman' => 'خانم',
                        'company' => 'حقوقی',
                    ]),
                SelectFilter::make('city')
                    ->label('شهر')
                    ->options(City::class)
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        return filled($value)
                            ? $query->whereHas('userMetas', fn (Builder $query): Builder => $query
                                ->where('key', 'city')
                                ->where('value', $value))
                            : $query;
                    }),
                SelectFilter::make('education')
                    ->label('مدرک تحصیلی')
                    ->options(UserEducation::class)
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        return filled($value)
                            ? $query->whereHas('userMetas', fn (Builder $query): Builder => $query
                                ->where('key', 'education')
                                ->where('value', $value))
                            : $query;
                    }),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
