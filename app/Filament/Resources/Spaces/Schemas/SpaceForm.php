<?php

namespace App\Filament\Resources\Spaces\Schemas;

use App\Enums\Status;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

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
                Select::make('status')
                    ->label('وضعیت')
                    ->options(Status::class)
                    ->default('active')
                    ->required(),
                Textarea::make('note')
                    ->label('یادداشت')
                    ->columnSpanFull(),
                FileUpload::make('logo')
                    ->label('نشان (Logo)')
                    ->image()
                    ->directory('spaces')
                    ->visibility('public'),
                FileUpload::make('featured_image')
                    ->label('تصویر اصلی')
                    ->image()
                    ->directory('spaces')
                    ->visibility('public'),
                FileUpload::make('images')
                    ->label('سایر تصاویر')
                    ->image()
                    ->multiple()
                    ->directory('spaces')
                    ->visibility('public'),
                TextInput::make('abstract')
                    ->label('معرفی اولیه')
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->label('متن معرفی کامل')
                    ->columnSpanFull(),
            ]);
    }
}
