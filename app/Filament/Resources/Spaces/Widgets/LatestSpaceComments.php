<?php

namespace App\Filament\Resources\Spaces\Widgets;

use App\Enums\InteractableType;
use App\Models\Comment;
use App\Models\SubSpace;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestSpaceComments extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    public int $spaceId;

    public function table(Table $table): Table
    {
        return $table
            ->heading('نظرات')
            ->query(
                Comment::query()
                    ->where(function ($query): void {
                        $query
                            ->where(function ($query): void {
                                $query
                                    ->where('type', InteractableType::Space)
                                    ->where('parent_id', $this->spaceId);
                            })
                            ->orWhere(function ($query): void {
                                $query
                                    ->where('type', InteractableType::Subspace)
                                    ->whereIn('parent_id', SubSpace::query()->where('space_id', $this->spaceId)->select('id'));
                            });
                    })
                    ->with(['user'])
                    ->latest()
            )
            ->defaultPaginationPageOption(10)
            ->recordAction('view')
            ->paginated(true)
            ->columns([
                TextColumn::make('user.name')
                    ->label('کاربر')
                    ->formatStateUsing(fn (?string $state, Comment $record): string => $state ?: ($record->user?->mobile ?? '-'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('type')
                    ->label('نوع')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state?->getLabel() ?? '-')
                    ->toggleable(),
                TextColumn::make('target')
                    ->label('مقصد')
                    ->state(fn (Comment $record): string => self::targetLabel($record))
                    ->toggleable(),
                TextColumn::make('rating')
                    ->label('امتیاز')
                    ->state(fn (Comment $record): string => $record->rating . ' / 5')
                    ->toggleable(),
                TextColumn::make('content')
                    ->label('متن نظر')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state?->getLabel() ?? '-')
                    ->color(fn ($state): string|array|null => $state?->getColor() ?? 'gray')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('ثبت')
                    ->since()
                    ->toggleable(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalHeading('جزئیات نظر')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextInput::make('user_display')
                                    ->label('کاربر'),
                                TextInput::make('type_display')
                                    ->label('نوع'),
                                TextInput::make('target_display')
                                    ->label('مقصد'),
                                TextInput::make('status_display')
                                    ->label('وضعیت'),
                                TextInput::make('rating')
                                    ->label('امتیاز'),
                                TextInput::make('created_at')
                                    ->label('ایجاد شده در')
                                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? verta($state)->format('Y/m/d H:i') : '-'),
                                TextInput::make('updated_at')
                                    ->label('آخرین بروزرسانی')
                                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? verta($state)->format('Y/m/d H:i') : '-'),
                                TextInput::make('reply_to')
                                    ->label('در پاسخ به'),
                                Textarea::make('content')
                                    ->label('متن نظر')
                                    ->rows(6)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->fillForm(fn (Comment $record): array => [
                        'user_display' => filled($record->user?->name) ? "{$record->user?->name} ({$record->user?->mobile})" : ($record->user?->mobile ?? '-'),
                        'type_display' => $record->type?->getLabel() ?? '-',
                        'target_display' => self::targetLabel($record),
                        'status_display' => $record->status?->getLabel() ?? '-',
                        'rating' => $record->rating . ' / 5',
                        'created_at' => $record->created_at,
                        'updated_at' => $record->updated_at,
                        'reply_to' => $record->reply_to ? '#' . $record->reply_to : '-',
                        'content' => $record->content,
                    ]),
            ]);
    }

    private static function targetLabel(Comment $record): string
    {
        return match ($record->type) {
            InteractableType::Space => 'مرکز',
            InteractableType::Subspace => SubSpace::query()->whereKey($record->parent_id)->value('title') ?? ('زیرمجموعه #' . $record->parent_id),
            InteractableType::Content => 'محتوا #' . $record->parent_id,
            InteractableType::Comment => 'نظر #' . $record->parent_id,
            default => '-',
        };
    }
}
