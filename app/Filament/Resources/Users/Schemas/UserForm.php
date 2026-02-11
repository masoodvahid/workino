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
            ->inlineLabel()
            ->components([
                TextInput::make('name')
                    ->label('نام و نام خانوادگی / نام شرکت')
                    ->required(),
                Select::make('type')
                    ->label('ماهیت')
                    ->options([
                        'woman' => 'خانم',
                        'man' => 'آقا',
                        'company' => 'حقوقی',
                    ])
                    ->required(),
                TextInput::make('mobile')
                    ->label('شماره همراه')
                    ->belowLabel('جهت ارسال رمز عبور')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->password()
                    ->label('رمز عبور')
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
                TextInput::make('email')
                    ->email()
                    ->label('ایمیل')
                    ->belowLabel('جهت ارسال صورتحساب')
                    ->required()
                    ->unique(ignoreRecord: true),

                Select::make('status')
                    ->options(UserStatus::class)
                    ->label('وضعیت')
                    ->default('active')
                    ->required(),
                Textarea::make('note')
                    ->label('یادداشت')
                    ->inlineLabel(false)
                    ->columnSpanFull(),
            ]);
    }
}
