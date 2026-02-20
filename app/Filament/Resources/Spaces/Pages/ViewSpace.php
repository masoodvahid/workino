<?php

namespace App\Filament\Resources\Spaces\Pages;

use App\Enums\Status;
use App\Filament\Resources\Spaces\SpaceResource;
use App\Models\SubSpace;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

use function Symfony\Component\Translation\t;

class ViewSpace extends ViewRecord
{
    protected static string $resource = SpaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create_subspace')
                ->label('افزودن زیر مجموعه')
                ->schema(fn (Schema $schema) => $schema->columns(3)->components($this->getSubSpaceFormSchema()))
                ->action(function (array $data): void {
                    [$data, $meta] = $this->extractSubSpaceMeta($data);
                    $data['space_id'] = $this->record->id;

                    $subSpace = SubSpace::create($data);
                    $subSpace->setMetaValues($meta);
                }),
            EditAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'مشاهده مرکز';
    }

    private function getSubSpaceFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label('عنوان فضای داخلی مجموعه')
                ->required()
                ->columnSpanFull(),
            TextInput::make('slug')
                ->label('آدرس صفحه')
                ->required()
                ->unique(table: 'subspaces', column: 'slug')
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
                ->default('active')
                ->required(),
            FileUpload::make('feature_image')
                ->label('تصویر شاخص')
                ->image()
                ->directory('subspaces')
                ->visibility('public')
                ->maxSize(1024),
            FileUpload::make('images')
                ->label('سایر تصاویر')
                ->image()
                ->multiple()
                ->directory('subspaces')
                ->visibility('public')
                ->maxSize(1024),
            Section::make('روزهای فعالیت')
                ->columnSpanFull()
                ->schema([
                    Grid::make(3)
                        ->schema([
                            $this->getDayTimeGroup('saturday', 'شنبه'),
                            $this->getDayTimeGroup('sunday', 'یکشنبه'),
                            $this->getDayTimeGroup('monday', 'دوشنبه'),
                            $this->getDayTimeGroup('tuesday', 'سه شنبه'),
                            $this->getDayTimeGroup('wednesday', 'چهارشنبه'),
                            $this->getDayTimeGroup('thursday', 'پنج شنبه'),
                            $this->getDayTimeGroup('friday', 'جمعه'),
                        ]),
                ]),
            Textarea::make('abstract')
                ->label('توضیح کوتاه')
                ->columnSpanFull(),
            RichEditor::make('content')
                ->label('توضیحات کامل')
                ->columnSpanFull(),
        ];
    }

    private function extractSubSpaceMeta(array $data): array
    {
        $meta = [];

        foreach (SubSpace::META_KEYS as $key) {
            if (array_key_exists($key, $data)) {
                $meta[$key] = $data[$key];
                unset($data[$key]);
            }
        }

        return [$data, $meta];
    }

    private function getDayTimeGroup(string $dayKey, string $dayLabel): Grid
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
}
