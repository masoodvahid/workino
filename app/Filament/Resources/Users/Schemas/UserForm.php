<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\City;
use App\Enums\UserEducation;
use App\Enums\UserStatus;
use App\Support\NumberNormalizer;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Hekmatinasser\Verta\Verta;
use Throwable;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                Select::make('type')
                    ->label('ماهیت')
                    ->options([
                        'man' => 'آقا',
                        'woman' => 'خانم',
                        'company' => 'حقوقی',
                    ])
                    ->live()
                    ->required(),
                TextInput::make('name')
                    ->label(fn (Get $get): string => $get('type') === 'company' ? 'نام شرکت' : 'نام و نام خانوادگی')
                    ->required(),
                TextInput::make('mobile')
                    ->label('شماره همراه')
                    ->belowLabel('جهت ارسال رمز عبور')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->afterStateUpdated(fn (?string $state, Set $set) => $set('mobile', NumberNormalizer::normalize($state)))
                    ->dehydrateStateUsing(fn (?string $state): ?string => NumberNormalizer::normalize($state)),
                TextInput::make('password')
                    ->password()
                    ->label('رمز عبور')
                    ->suffixAction(
                        Action::make('generate_password')
                            ->label('ساخت رمز عبور')
                            ->view(Action::BUTTON_VIEW)

                            ->action(fn (Set $set) => $set('password', self::generatePassword()))
                    )
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
                TextInput::make('email')
                    ->email()
                    ->label('ایمیل')
                    ->belowLabel('جهت ارسال صورتحساب')
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('reg_number')
                    ->label('شماره ثبت')
                    ->numeric()
                    ->rules(['digits_between:1,10'])
                    ->visible(fn (Get $get): bool => $get('type') === 'company')
                    ->afterStateUpdated(fn (?string $state, Set $set) => $set('reg_number', NumberNormalizer::normalize($state)))
                    ->dehydrateStateUsing(fn (?string $state): ?string => NumberNormalizer::normalize($state)),

                TextInput::make('national_id')
                    ->label(fn (Get $get): string => $get('type') === 'company' ? 'شناسه ملی' : 'کد ملی')
                    ->numeric()
                    ->rules(fn (Get $get): array => $get('type') === 'company' ? ['digits_between:10,12'] : ['digits:10'])
                    ->visible(fn (Get $get): bool => in_array($get('type'), ['man', 'woman', 'company'], true))
                    ->afterStateUpdated(fn (?string $state, Set $set) => $set('national_id', NumberNormalizer::normalize($state)))
                    ->dehydrateStateUsing(fn (?string $state): ?string => NumberNormalizer::normalize($state)),
                TextInput::make('birth_day')
                    ->label('تاریخ تولد')
                    ->placeholder('1402/01/01')
                    ->rules(['regex:/^\d{4}\/\d{2}\/\d{2}$/'])
                    ->visible(fn (Get $get): bool => in_array($get('type'), ['man', 'woman'], true))
                    ->afterStateUpdated(fn (?string $state, Set $set) => $set('birth_day', NumberNormalizer::normalize($state)))
                    ->afterStateHydrated(function (TextInput $component, ?string $state): void {
                        $component->state(self::toJalaliDate($state));
                    })
                    ->dehydrateStateUsing(fn (?string $state): ?string => self::toGregorianDate($state)),
                TextInput::make('major')
                    ->label('رشته تحصیلی')
                    ->maxLength(512)
                    ->visible(fn (Get $get): bool => in_array($get('type'), ['man', 'woman'], true)),
                Select::make('education')
                    ->label('آخرین مدرک تحصیلی')
                    ->options(UserEducation::class)
                    ->visible(fn (Get $get): bool => in_array($get('type'), ['man', 'woman'], true)),

                TextInput::make('university')
                    ->label('آخرین محل تحصیل')
                    ->maxLength(512)
                    ->visible(fn (Get $get): bool => in_array($get('type'), ['man', 'woman'], true)),

                Select::make('city')
                    ->label('شهر')
                    ->options(City::class)
                    ->searchable(),

                TextInput::make('postal_code')
                    ->label('کد پستی')
                    ->numeric()
                    ->rules(['digits:10'])
                    ->afterStateUpdated(fn (?string $state, Set $set) => $set('postal_code', NumberNormalizer::normalize($state)))
                    ->dehydrateStateUsing(fn (?string $state): ?string => NumberNormalizer::normalize($state)),

                Select::make('status')
                    ->options(UserStatus::class)
                    ->label('وضعیت')
                    ->default('active')
                    ->required(),
                TextInput::make('address')
                    ->label('آدرس')
                    ->inlineLabel(false)
                    ->maxLength(1024)
                    ->columnSpanFull(),
                Textarea::make('note')
                    ->label('یادداشت')
                    ->inlineLabel(false)
                    ->columnSpanFull(),
            ]);
    }

    private static function generatePassword(): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $length = 10;
        $password = '';

        for ($index = 0; $index < $length; $index++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $password;
    }

    private static function toGregorianDate(?string $value): ?string
    {
        $value = NumberNormalizer::normalize($value);

        if (blank($value)) {
            return null;
        }

        try {
            return Verta::parse($value)->toCarbon()->format('Y-m-d');
        } catch (Throwable) {
            return null;
        }
    }

    private static function toJalaliDate(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        try {
            return verta($value)->format('Y/m/d');
        } catch (Throwable) {
            return $value;
        }
    }
}
