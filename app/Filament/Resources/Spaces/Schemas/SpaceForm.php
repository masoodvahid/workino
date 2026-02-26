<?php

namespace App\Filament\Resources\Spaces\Schemas;

use App\Enums\City;
use App\Enums\Status;
use App\Enums\UserRoleKey;
use App\Models\Space;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class SpaceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                TextInput::make('title')
                    ->label('عنوان')
                    ->required(),
                TextInput::make('slug')
                    ->label('اسلاگ')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->rules(['regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/']),
                TextInput::make('order')
                    ->label('ترتیب')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(99)
                    ->default(1),
                Select::make('city')
                    ->label('شهر')
                    ->options(City::class)
                    ->searchable()
                    ->required(),
                Select::make('status')
                    ->label('وضعیت')
                    ->options(Status::class)
                    ->default('active')
                    ->required(),
                Textarea::make('note')
                    ->label('یادداشت')
                    ->columnSpanFull(),
                TextInput::make('location_neshan')
                    ->label('لوکیشن در نشان')
                    ->url()
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
                    ->columnSpanFull(),
                TextInput::make('abstract')
                    ->label('معرفی اولیه')
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->label('متن معرفی کامل')
                    ->extraInputAttributes(['style' => 'min-height: 16rem;'])
                    ->columnSpanFull(),
                Repeater::make('spaceUsers')
                    ->label('اعضای مرکز')
                    ->relationship('spaceUsers')
                    ->defaultItems(0)
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
                    ->columnSpanFull(),
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
}
