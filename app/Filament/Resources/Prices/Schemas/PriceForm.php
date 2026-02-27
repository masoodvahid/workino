<?php

namespace App\Filament\Resources\Prices\Schemas;

use App\Enums\BookingStatus;
use App\Enums\BookingUnit;
use App\Models\SubSpace;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class PriceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('subspace_id')
                    ->label('زیرمجموعه')
                    ->relationship('subSpace', 'title', fn (Builder $query): Builder => self::scopeSubSpacesForCurrentUser($query))
                    ->getOptionLabelFromRecordUsing(fn (SubSpace $record): string => "{$record->title} ({$record->space?->title})")
                    ->searchable(['title', 'slug'])
                    ->preload()
                    ->required(),
                TextInput::make('title')
                    ->label('عنوان')
                    ->required()
                    ->maxLength(512),
                Select::make('unit')
                    ->label('واحد')
                    ->options(BookingUnit::class)
                    ->required(),
                TextInput::make('base_price')
                    ->label('قیمت پایه')
                    ->numeric()
                    ->required(),
                TextInput::make('special_price')
                    ->label('قیمت ویژه')
                    ->numeric(),
                TextInput::make('priority')
                    ->label('اولویت')
                    ->numeric(),
                DatePicker::make('start')
                    ->label('تاریخ شروع'),
                DatePicker::make('end')
                    ->label('تاریخ پایان'),
                Select::make('status')
                    ->label('وضعیت')
                    ->options(BookingStatus::class)
                    ->default(BookingStatus::Active->value)
                    ->required(),
                Textarea::make('description')
                    ->label('توضیحات')
                    ->columnSpanFull(),
                KeyValue::make('unit_rules')
                    ->label('قوانین واحد')
                    ->columnSpanFull(),
            ]);
    }

    private static function scopeSubSpacesForCurrentUser(Builder $query): Builder
    {
        $user = auth()->user();

        if (! $user instanceof User || $user->isAdmin()) {
            return $query;
        }

        if (! $user->isSpaceUser()) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereHas('space.spaceUsers', fn (Builder $query): Builder => $query
            ->where('user_id', $user->id)
            ->where('status', 'active'));
    }
}
