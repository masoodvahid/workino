<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('mobile')
                    ->label('شماره تماس')
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->label('رمز عبور')
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
                Select::make('status')
                    ->options(UserStatus::class)
                    ->label('وضعیت')
                    ->default('active')
                    ->required(),
                Textarea::make('note')
                    ->label('یادداشت')
                    ->columnSpanFull(),
            ]);
    }
}
