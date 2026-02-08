<?php

namespace App\Filament\Resources\UserMetas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserMetaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('uid')
                    ->relationship('user', 'mobile')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('کاربر'),
                TextInput::make('key')
                    ->required()
                    ->label('کلید'),
                TextInput::make('title')
                    ->default(null)
                    ->label('عنوان'),
                Textarea::make('value')
                    ->default(null)
                    ->columnSpanFull()
                    ->label('مقدار'),
                Toggle::make('is_encrypted')
                    ->required()
                    ->label('رمزنگاری شده'),
                Select::make('status')
                    ->options([
                        '1' => 'تایید شده',
                        '0' => 'رد شده',
                    ])
                    ->placeholder('بررسی نشده')
                    ->label('وضعیت'),
            ]);
    }
}
