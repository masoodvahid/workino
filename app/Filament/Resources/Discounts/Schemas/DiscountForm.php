<?php

namespace App\Filament\Resources\Discounts\Schemas;

use App\Enums\BookingStatus;
use App\Enums\DiscountType;
use App\Models\Space;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class DiscountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('space_id')
                    ->label('مرکز')
                    ->relationship('space', 'title', fn (Builder $query): Builder => self::scopeSpacesForCurrentUser($query))
                    ->searchable(['title', 'slug'])
                    ->preload()
                    ->required(),
                TextInput::make('code')
                    ->label('کد')
                    ->required()
                    ->maxLength(256),
                TextInput::make('title')
                    ->label('عنوان')
                    ->required()
                    ->maxLength(512),
                Select::make('type')
                    ->label('نوع')
                    ->options(DiscountType::class)
                    ->required(),
                TextInput::make('limits')
                    ->label('محدودیت تعداد استفاده')
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
                KeyValue::make('applied_to')
                    ->label('اعمال روی')
                    ->columnSpanFull(),
            ]);
    }

    private static function scopeSpacesForCurrentUser(Builder $query): Builder
    {
        $user = auth()->user();

        if (! $user instanceof User || $user->isAdmin()) {
            return $query;
        }

        if (! $user->isSpaceUser()) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereHas('spaceUsers', fn (Builder $query): Builder => $query
            ->where('user_id', $user->id)
            ->where('status', 'active'));
    }
}
