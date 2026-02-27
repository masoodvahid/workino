<?php

namespace App\Filament\Resources\Spaces\RelationManagers;

use App\Filament\Resources\SubSpaces\SubSpaceResource;
use App\Models\SubSpace;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SubSpacesRelationManager extends RelationManager
{
    protected static string $relationship = 'subSpaces';

    protected static ?string $title = 'زیر مجموعه ها';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['prices']))
            ->recordTitleAttribute('title')
            ->recordAction(null)
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('اسلاگ')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('type')
                    ->label('نوع فضا')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'room' => 'اتاق',
                        'seat' => 'صندلی',
                        'meeting_room' => 'اتاق جلسات',
                        'conference_room' => 'اتاق کنفرانس',
                        'coffeeshop' => 'کافی شاپ',
                        default => $state,
                    })
                    ->badge(),
                TextColumn::make('capacity')
                    ->label('ظرفیت')
                    ->placeholder('-')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('prices_count')
                    ->label('تعداد قیمت')
                    ->state(fn (SubSpace $record): int => $record->prices->count()),
                BadgeColumn::make('status')
                    ->label('وضعیت')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('آخرین بروزرسانی')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                    ->label('افزودن زیر مجموعه')
                    ->url(function (): string {
                        $space = $this->getOwnerRecord();

                        return SubSpaceResource::getUrl('create', [
                            'space_id' => $space->getKey(),
                            'return_url' => route('filament.modiriat.resources.spaces.view', ['record' => $space->getKey()]),
                        ]);
                    }),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('ویرایش')
                    ->icon('heroicon-o-pencil-square')
                    ->url(function (SubSpace $record): string {
                        $space = $this->getOwnerRecord();

                        return SubSpaceResource::getUrl('edit', [
                            'record' => $record,
                            'return_url' => route('filament.modiriat.resources.spaces.view', ['record' => $space->getKey()]),
                        ]);
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
