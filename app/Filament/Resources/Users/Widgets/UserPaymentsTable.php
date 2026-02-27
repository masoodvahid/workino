<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Models\Payment;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class UserPaymentsTable extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    public int $userId;

    public function table(Table $table): Table
    {
        return $table
            ->heading('پرداخت‌های کاربر')
            ->query(
                Payment::query()
                    ->withTrashed()
                    ->where('user_id', $this->userId)
                    ->with(['space', 'subSpace', 'booking'])
                    ->latest()
            )
            ->defaultPaginationPageOption(10)
            ->recordAction('view')
            ->paginated(true)
            ->columns([
                TextColumn::make('space.title')
                    ->label('مرکز')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('subSpace.title')
                    ->label('زیرمجموعه')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('booking.id')
                    ->label('رزرو')
                    ->toggleable(),
                TextColumn::make('gateway')
                    ->label('درگاه')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'success' => 'success',
                        'failed' => 'danger',
                        'canceled' => 'gray',
                        default => 'warning',
                    })
                    ->toggleable(),
                TextColumn::make('api_cal_counter')
                    ->label('API Calls')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('ثبت')
                    ->since()
                    ->toggleable(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalHeading('جزئیات پرداخت')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextInput::make('id')
                                    ->label('شناسه'),
                                TextInput::make('space_display')
                                    ->label('مرکز'),
                                TextInput::make('subspace_display')
                                    ->label('زیرمجموعه'),
                                TextInput::make('booking_display')
                                    ->label('رزرو'),
                                TextInput::make('gateway')
                                    ->label('درگاه'),
                                TextInput::make('status')
                                    ->label('وضعیت'),
                                TextInput::make('acc_id')
                                    ->label('Account ID'),
                                TextInput::make('api_cal_counter')
                                    ->label('API Calls'),
                                TextInput::make('created_at')
                                    ->label('ایجاد شده در')
                                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? verta($state)->format('Y/m/d H:i') : '-'),
                                TextInput::make('updated_at')
                                    ->label('آخرین بروزرسانی')
                                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? verta($state)->format('Y/m/d H:i') : '-'),
                                Textarea::make('acc_details_display')
                                    ->label('Account Details')
                                    ->rows(4)
                                    ->columnSpan(2),
                                Textarea::make('note')
                                    ->label('یادداشت')
                                    ->rows(4)
                                    ->columnSpan(2),
                            ]),
                    ])
                    ->fillForm(fn (Payment $record): array => [
                        'id' => $record->id,
                        'space_display' => $record->space?->title ?? '-',
                        'subspace_display' => $record->subSpace?->title ?? '-',
                        'booking_display' => $record->booking?->id ? '#' . $record->booking->id : '-',
                        'gateway' => $record->gateway,
                        'status' => $record->status,
                        'acc_id' => $record->acc_id,
                        'api_cal_counter' => $record->api_cal_counter,
                        'created_at' => $record->created_at,
                        'updated_at' => $record->updated_at,
                        'acc_details_display' => filled($record->acc_details) ? json_encode($record->acc_details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '-',
                        'note' => $record->note,
                    ]),
            ]);
    }
}
