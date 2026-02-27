<?php

namespace App\Filament\Resources\Spaces\Schemas;

use App\Enums\City;
use App\Enums\Status;
use App\Enums\UserRoleKey;
use App\Models\Space;
use App\Support\NumberNormalizer;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Builder;
use Hekmatinasser\Verta\Verta;
use Throwable;

class SpaceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema           
            ->columns(5)
            ->components([
                TextInput::make('title')
                    ->label('عنوان')
                    ->required(),
                TextInput::make('slug')
                    ->label('URL')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->rules(['regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/']),
                TextInput::make('order')
                    ->label('ترتیب')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(99)
                    ->default(1),
                Select::make('status')
                    ->label('وضعیت')
                    ->options(Status::class)
                    ->default('active')
                    ->required(),
                Select::make('city')
                    ->label('شهر')
                    ->options(City::class)
                    ->searchable()
                    ->required(),
                TextInput::make('address')
                    ->label('آدرس')                    
                    ->columnSpan(2),
                TextInput::make('postal_code')
                    ->label('کد پستی'),
                TextInput::make('location_neshan')
                    ->label('لوکیشن در نشان')
                    ->url()
                    ->columnSpan(2)
                    ->placeholder('https://nshn.ir/...'),
                FileUpload::make('logo')
                    ->label('نشان (Logo)')
                    ->image()
                    ->acceptedFileTypes([
                        'image/svg+xml',
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                        'image/avif',
                    ])
                    ->maxSize(1024)
                    ->automaticallyResizeImagesMode('contain')
                    ->automaticallyResizeImagesToWidth('1920')
                    ->automaticallyResizeImagesToHeight('1080')
                    ->automaticallyUpscaleImagesWhenResizing(false)
                    ->disk('public')
                    ->directory(fn (?Space $record): string => filled($record?->id) ? "spaces/{$record->id}" : 'spaces/temp')
                    ->visibility('public'),
                FileUpload::make('featured_image')
                    ->label('تصویر اصلی')
                    ->image()
                    ->columnSpan(2)
                    ->acceptedFileTypes([
                        'image/svg+xml',
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                        'image/avif',
                    ])
                    ->maxSize(1024)
                    ->automaticallyResizeImagesMode('contain')
                    ->automaticallyResizeImagesToWidth('1920')
                    ->automaticallyResizeImagesToHeight('1080')
                    ->automaticallyUpscaleImagesWhenResizing(false)
                    ->disk('public')
                    ->directory(fn (?Space $record): string => filled($record?->id) ? "spaces/{$record->id}" : 'spaces/temp')
                    ->visibility('public'),
                FileUpload::make('images')
                    ->label('سایر تصاویر')
                    ->image()
                    ->columnSpan(2)
                    ->multiple()
                    ->acceptedFileTypes([
                        'image/svg+xml',
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                        'image/avif',
                    ])
                    ->maxSize(1024)
                    ->automaticallyResizeImagesMode('contain')
                    ->automaticallyResizeImagesToWidth('1920')
                    ->automaticallyResizeImagesToHeight('1080')
                    ->automaticallyUpscaleImagesWhenResizing(false)
                    ->disk('public')
                    ->directory(fn (?Space $record): string => filled($record?->id) ? "spaces/{$record->id}" : 'spaces/temp')
                    ->visibility('public'),
                TextInput::make('abstract')
                    ->label('معرفی اولیه')
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->label('متن معرفی کامل')
                    ->extraInputAttributes(['style' => 'min-height: 16rem;'])
                    ->columnSpanFull(),
                Repeater::make('phones')
                    ->label('شماره تماس')
                    ->defaultItems(0)                    
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('عنوان')
                            ->required(),
                        TextInput::make('phone_number')
                            ->label('شماره تماس')
                            ->tel()
                            ->required(),
                    ])
                    ->grid(4)
                    ->addActionAlignment(Alignment::Start)
                    ->columnSpanFull(),
                Repeater::make('social')
                    ->label('شبکه‌های اجتماعی')
                    ->defaultItems(0)
                    ->columns(3)                   
                    ->schema([
                        TextInput::make('title')
                            ->label('عنوان')
                            ->required(),
                        TextInput::make('url')
                            ->label('آدرس')
                            ->url()
                            ->required(),
                        Select::make('icon')
                            ->label('آیکن')
                            ->options(self::socialIconOptions())
                            ->searchable()
                            ->native(false)
                            ->placeholder('انتخاب آیکن'),
                    ])
                    ->addActionAlignment(Alignment::Start)
                    ->grid(2)
                    ->collapsible()
                    ->columnSpanFull(),
                Repeater::make('off_dates')
                    ->label('روزهای تعطیل')
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
                    ->grid(7)
                    ->addActionAlignment(Alignment::Start)
                    ->columnSpanFull(),
                Repeater::make('spaceUsers')
                    ->label('کاربران مرکز')
                    ->relationship('spaceUsers')
                    ->defaultItems(0)
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->label('کاربر')
                            ->relationship('user', 'name', fn (Builder $query): Builder => $query
                                ->whereHas('role', fn (Builder $query): Builder => $query->where('key', UserRoleKey::SpaceUser->value)))
                            ->getOptionLabelFromRecordUsing(fn ($record): string => filled($record->name) ? "{$record->name} ({$record->mobile})" : $record->mobile)
                            ->searchable(['name', 'mobile'])
                            ->preload()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->required(),
                        Select::make('status')
                            ->label('وضعیت')
                            ->options(Status::class)
                            ->default('active')
                            ->required(),
                    ])
                    ->grid(2)
                    ->addActionAlignment(Alignment::Start)
                    ->collapsible()
                    ->columnSpanFull(),               
                Textarea::make('note')
                    ->label('یادداشت مدیریت  ')
                    ->columnSpanFull()               
            ]);
    }

    private static function socialIconOptions(): array
    {
        return [
            'bi bi-telegram' => 'Telegram',
            'bi bi-instagram' => 'Instagram',
            'bi bi-whatsapp' => 'WhatsApp',
            'bi bi-linkedin' => 'LinkedIn',
            'bi bi-twitter-x' => 'X (Twitter)',
            'bi bi-youtube' => 'YouTube',
            'bi bi-github' => 'GitHub',
            'bi bi-facebook' => 'Facebook',
            'bi bi-send-fill' => 'Eitaa',
            'bi bi-globe2' => 'Website',
        ];
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

    private static function jalaliDateRule(): ValidationRule
    {
        return new class implements ValidationRule
        {
            public function validate(string $attribute, mixed $value, \Closure $fail): void
            {
                if (blank($value)) {
                    return;
                }

                if (blank(SpaceForm::toGregorianDate(is_string($value) ? $value : null))) {
                    $fail('فرمت تاریخ معتبر نیست.');
                }
            }
        };
    }
}
