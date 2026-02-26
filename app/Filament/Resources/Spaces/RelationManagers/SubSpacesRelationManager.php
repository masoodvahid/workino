<?php

namespace App\Filament\Resources\Spaces\RelationManagers;

use App\Enums\Status;
use App\Models\SubSpace;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SubSpacesRelationManager extends RelationManager
{
    protected static string $relationship = 'subSpaces';

    protected static ?string $title = 'زیر مجموعه ها';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components($this->getSubSpaceFormSchema());
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with('subSpaceMetas'))
            ->recordTitleAttribute('title')
            ->recordAction('edit')
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('اسلاگ')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('type')
                    ->label('نوع فضا')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'room' => 'اتاق',
                        'seat' => 'صندلی',
                        'meeting_room' => 'اتاق جلسات',
                        'conference_room' => 'اتاق کنفرانس',
                        'coffeeshop' => 'کافی شاپ',
                        default => $state,
                    })
                    ->badge(),
                TextColumn::make('capacity')
                    ->label('ظرفیت')
                    ->placeholder('-')
                    ->numeric()
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('وضعیت')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('آخرین بروزرسانی')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                    ->label('افزودن زیر مجموعه')
                    ->schema(fn (Schema $schema): Schema => $schema
                        ->columns(3)
                        ->components($this->getSubSpaceFormSchema()))
                    ->action(function (array $data): void {
                        [$attributes, $meta] = $this->extractSubSpaceMeta($data);
                        $attributes['space_id'] = $this->getOwnerRecord()->getKey();

                        $subSpace = SubSpace::create($attributes);
                        $subSpace->setMetaValues($meta);
                    }),
            ])
            ->recordActions([
                EditAction::make('edit')
                    ->schema(fn (Schema $schema): Schema => $schema
                        ->columns(3)
                        ->components($this->getSubSpaceFormSchema()))
                    ->fillForm(function (SubSpace $record): array {
                        return array_merge(
                            $record->attributesToArray(),
                            collect(SubSpace::META_KEYS)
                                ->mapWithKeys(fn (string $key): array => [$key => $record->metaValue($key)])
                                ->all(),
                        );
                    })
                    ->using(function (SubSpace $record, array $data): SubSpace {
                        [$attributes, $meta] = $this->extractSubSpaceMeta($data);

                        $record->update($attributes);
                        $record->setMetaValues($meta);

                        return $record->refresh();
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
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
