<?php

namespace App\Filament\Resources\SubSpaces\Schemas;

use App\Enums\BookingStatus;
use App\Enums\BookingUnit;
use App\Enums\Status;
use App\Models\SubSpace;
use App\Support\NumberNormalizer;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Validation\ValidationRule;
use Hekmatinasser\Verta\Verta;
use Throwable;

class SubSpaceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(5)
            ->components([
                Hidden::make('space_id')
                    ->default(fn (): ?int => request()->integer('space_id') ?: null)
                    ->required(),
                TextInput::make('title')
                    ->label('عنوان فضای داخلی ')
                    ->required()
                    ,
                TextInput::make('slug')
                    ->label('آدرس صفحه')
                    ->required()
                    ->rules(['regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/']),
                Select::make('type')
                    ->label('نوع فضا')
                    ->options([
                        'room' => 'اتاق',
                        'seat' => 'صندلی',
                        'meeting_room' => 'اتاق جلسات',
                        'conference_room' => 'اتاق کنفرانس',
                        'coffeeshop' => 'کافی شاپ',
                    ])
                    ->default('seat')
                    ->required(),
                TextInput::make('capacity')
                    ->label('ظرفیت')
                    ->numeric()
                    ->minValue(1)
                    ->nullable(),
                Select::make('status')
                    ->label('وضعیت انتشار')
                    ->options(Status::class)
                    ->default(Status::Active->value)
                    ->required(),
                FileUpload::make('feature_image')
                    ->label('تصویر شاخص')
                    ->columnSpan(2)
                    ->image()
                    ->directory('subspaces')
                    ->visibility('public')
                    ->maxSize(1024),
                FileUpload::make('images')
                    ->label('سایر تصاویر')
                    ->columnSpan(3)
                    ->image()
                    ->multiple()
                    ->directory('subspaces')
                    ->visibility('public')
                    ->maxSize(1024),
                Textarea::make('abstract')
                    ->label('توضیح کوتاه')
                    ->rows(2)
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->label('توضیحات کامل')                    
                    ->columnSpanFull(),
                Section::make('قیمت‌گذاری')
                    ->schema([
                        Repeater::make('prices')
                            ->hiddenLabel('پلن‌های قیمت')
                            ->defaultItems(1)
                            ->minItems(1)
                            ->addActionLabel('افزودن قیمت جدید')
                            ->itemLabel(fn (array $state): string => (string) ($state['title'] ?? 'قیمت جدید'))
                            ->afterStateUpdated(function (?array $state, Set $set): void {
                                $normalized = self::normalizePricesState($state ?? []);

                                if ($normalized !== ($state ?? [])) {
                                    $set('prices', $normalized);
                                }
                            })
                            ->schema([
                                Hidden::make('id'),
                                Grid::make(7)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('عنوان تعرفه')
                                            ->columnSpan(2)
                                            ->required(),
                                        Textarea::make('description')
                                            ->label('توضیحات تعرفه')
                                            ->columnSpan(5),
                                        Select::make('unit')
                                            ->label('واحد')
                                            ->options(BookingUnit::class)
                                            ->default(BookingUnit::Hour->value)
                                            ->required()
                                            ->live(),
                                        Select::make('status')
                                            ->label('وضعیت تعرفه')
                                            ->options(BookingStatus::class)
                                            ->default(BookingStatus::Active->value)
                                            ->required(),
                                        TextInput::make('base_price')
                                            ->label('قیمت پایه (ریال)')
                                            ->rules(['integer'])
                                            ->stripCharacters(',')
                                            ->live()
                                            ->afterStateUpdated(fn (?string $state, Set $set): mixed => $set('base_price', self::formatIntegerInput($state)))
                                            ->afterStateHydrated(function (TextInput $component, mixed $state): void {
                                                $component->state(self::formatIntegerInput(is_scalar($state) ? (string) $state : null));
                                            })
                                            ->dehydrateStateUsing(fn (?string $state): ?string => self::normalizeIntegerInput($state))
                                            ->required(),
                                        TextInput::make('special_price')
                                            ->label('قیمت ویژه (ریال)')
                                            ->rules(['nullable', 'integer'])
                                            ->stripCharacters(',')
                                            ->live()
                                            ->afterStateUpdated(fn (?string $state, Set $set): mixed => $set('special_price', self::formatIntegerInput($state)))
                                            ->afterStateHydrated(function (TextInput $component, mixed $state): void {
                                                $component->state(self::formatIntegerInput(is_scalar($state) ? (string) $state : null));
                                            })
                                            ->dehydrateStateUsing(fn (?string $state): ?string => self::normalizeIntegerInput($state))
                                            ->nullable(),
                                        TextInput::make('priority')
                                            ->label('اولویت')
                                            ->numeric()
                                            ->disabled()
                                            ->nullable(),
                                        TextInput::make('start')
                                            ->label('تاریخ شروع')
                                            ->placeholder('1404/01/01')
                                            ->rules([
                                                'nullable',
                                                self::jalaliDateRule(mustBeTodayOrLater: true),
                                            ])
                                            ->afterStateUpdated(fn (?string $state, Set $set): mixed => $set('start', NumberNormalizer::normalize($state)))
                                            ->afterStateHydrated(function (TextInput $component, ?string $state): void {
                                                $component->state(self::toJalaliDate($state));
                                            })
                                            ->dehydrateStateUsing(fn (?string $state): ?string => self::toGregorianDate($state))
                                            ->nullable(),
                                        TextInput::make('end')
                                            ->label('تاریخ پایان')
                                            ->placeholder('1404/01/01')
                                            ->rules([
                                                'nullable',
                                                self::jalaliDateRule(),
                                            ])
                                            ->afterStateUpdated(fn (?string $state, Set $set): mixed => $set('end', NumberNormalizer::normalize($state)))
                                            ->afterStateHydrated(function (TextInput $component, ?string $state): void {
                                                $component->state(self::toJalaliDate($state));
                                            })
                                            ->dehydrateStateUsing(fn (?string $state): ?string => self::toGregorianDate($state))
                                            ->nullable(),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                
                
                Section::make('روزهای فعالیت')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                self::getDayTimeGroup('saturday', 'شنبه'),
                                self::getDayTimeGroup('sunday', 'یکشنبه'),
                                self::getDayTimeGroup('monday', 'دوشنبه'),
                                self::getDayTimeGroup('tuesday', 'سه شنبه'),
                                self::getDayTimeGroup('wednesday', 'چهارشنبه'),
                                self::getDayTimeGroup('thursday', 'پنج شنبه'),
                                self::getDayTimeGroup('friday', 'جمعه'),
                            ]),
                    ]),
                Repeater::make('off_dates')
                    ->label('روزهای غیر قابل رزرو')
                    ->defaultItems(0)
                    ->addActionLabel('افزودن تاریخ')
                    ->simple(
                        TextInput::make('date')
                            ->label('تاریخ (جلالی)')
                            ->placeholder('1404/01/01')
                            ->rules(['required', self::jalaliDateRule()])
                            ->afterStateUpdated(fn (?string $state, Set $set): mixed => $set('date', NumberNormalizer::normalize($state)))
                            ->afterStateHydrated(function (TextInput $component, ?string $state): void {
                                $component->state(self::toJalaliDate($state));
                            })
                            ->dehydrateStateUsing(fn (?string $state): ?string => self::toGregorianDate($state))
                            ->required()
                    )
                    ->grid(5)
                    ->addActionAlignment(Alignment::Start)
                    ->columnSpanFull(),                
            ]);
    }

    private static function normalizePricesState(array $prices): array
    {
        return collect($prices)
            ->values()
            ->map(function (mixed $item, int $index): mixed {
                if (! is_array($item)) {
                    return $item;
                }

                $item['priority'] = $index + 1;

                return $item;
            })
            ->all();
    }

    private static function getDayTimeGroup(string $dayKey, string $dayLabel): Grid
    {
        return Grid::make(3)
            ->schema([
                Checkbox::make("working_time.{$dayKey}.enabled")
                    ->label($dayLabel),
                TimePicker::make("working_time.{$dayKey}.start")
                    ->hiddenLabel('شروع')
                    ->seconds(false),
                TimePicker::make("working_time.{$dayKey}.end")
                    ->hiddenLabel('پایان')
                    ->seconds(false),
            ])
            ->columnSpan(1);
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

    private static function normalizeIntegerInput(?string $value): ?string
    {
        $value = NumberNormalizer::normalize($value);

        if (blank($value)) {
            return null;
        }

        $digits = preg_replace('/[^0-9]/', '', $value);

        return filled($digits) ? $digits : null;
    }

    private static function formatIntegerInput(?string $value): ?string
    {
        $digits = self::normalizeIntegerInput($value);

        if (blank($digits)) {
            return null;
        }

        return number_format((int) $digits);
    }

    private static function jalaliDateRule(bool $mustBeTodayOrLater = false): ValidationRule
    {
        return new class($mustBeTodayOrLater) implements ValidationRule
        {
            public function __construct(
                private readonly bool $mustBeTodayOrLater = false,
            ) {}

            public function validate(string $attribute, mixed $value, \Closure $fail): void
            {
                if (blank($value)) {
                    return;
                }

                $gregorianDate = SubSpaceForm::toGregorianDate(is_string($value) ? $value : null);

                if (blank($gregorianDate)) {
                    $fail(str_contains($attribute, 'start') ? 'فرمت تاریخ شروع معتبر نیست.' : 'فرمت تاریخ پایان معتبر نیست.');

                    return;
                }

                if ($this->mustBeTodayOrLater && strtotime($gregorianDate) < now()->startOfDay()->timestamp) {
                    $fail('تاریخ شروع باید از امروز یا بعد از آن باشد.');
                }
            }
        };
    }
}
