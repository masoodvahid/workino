<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Models\Booking;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class UserBookingsTable extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    public int $userId;

    public function table(Table $table): Table
    {
        return $table
            ->heading('رزروهای کاربر')
            ->query(
                Booking::query()
                    ->withTrashed()
                    ->where('user_id', $this->userId)
                    ->with(['subSpace.space', 'price', 'payments'])
                    ->latest()
            )
            ->defaultPaginationPageOption(10)
            ->recordAction('view')
            ->paginated(true)
            ->columns([
                TextColumn::make('subSpace.space.title')
                    ->label('مرکز')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('subSpace.title')
                    ->label('زیرمجموعه')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('price.title')
                    ->label('تعرفه')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('quantity')
                    ->label('تعداد')
                    ->toggleable(),
                TextColumn::make('total_price')
                    ->label('مبلغ کل')
                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? number_format((int) $state) . ' ریال' : '-')
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'approve' => 'تایید',
                        'reject' => 'رد',
                        default => 'در انتظار',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'approve' => 'success',
                        'reject' => 'danger',
                        default => 'warning',
                    })
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('start')
                    ->label('شروع')
                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? verta($state)->format('Y/m/d') : '-')
                    ->toggleable(),
                TextColumn::make('end')
                    ->label('پایان')
                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? verta($state)->format('Y/m/d') : '-')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('ثبت')
                    ->since()
                    ->toggleable(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalHeading('جزئیات رزرو')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextInput::make('space_display')
                                    ->label('مرکز'),
                                TextInput::make('subspace_display')
                                    ->label('زیرمجموعه'),
                                TextInput::make('price_display')
                                    ->label('تعرفه'),
                                TextInput::make('status_display')
                                    ->label('وضعیت'),
                                TextInput::make('quantity')
                                    ->label('تعداد'),
                                TextInput::make('unit_price')
                                    ->label('قیمت واحد (ریال)')
                                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? number_format((int) $state) : '-'),
                                TextInput::make('total_price')
                                    ->label('مبلغ کل (ریال)')
                                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? number_format((int) $state) : '-'),
                                TextInput::make('deleted_at')
                                    ->label('حذف شده در')
                                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? verta($state)->format('Y/m/d H:i') : '-'),
                                TextInput::make('start')
                                    ->label('تاریخ شروع')
                                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? verta($state)->format('Y/m/d') : '-'),
                                TextInput::make('end')
                                    ->label('تاریخ پایان')
                                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? verta($state)->format('Y/m/d') : '-'),
                                TextInput::make('created_at')
                                    ->label('ایجاد شده در')
                                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? verta($state)->format('Y/m/d H:i') : '-'),
                                TextInput::make('updated_at')
                                    ->label('آخرین بروزرسانی')
                                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? verta($state)->format('Y/m/d H:i') : '-'),
                                Textarea::make('note')
                                    ->label('یادداشت')
                                    ->rows(4)
                                    ->columnSpanFull(),
                                Repeater::make('payments')
                                    ->label('پرداخت‌های مرتبط')
                                    ->schema([
                                        Grid::make(5)
                                            ->schema([
                                                TextInput::make('id')
                                                    ->label('#'),
                                                TextInput::make('gateway')
                                                    ->label('درگاه'),
                                                TextInput::make('status')
                                                    ->label('وضعیت'),
                                                TextInput::make('api_cal_counter')
                                                    ->label('API Calls'),
                                                TextInput::make('created_at')
                                                    ->label('ثبت'),
                                                TextInput::make('acc_id')
                                                    ->label('Account ID'),
                                                Textarea::make('acc_details_display')
                                                    ->label('Account Details')
                                                    ->rows(3)
                                                    ->columnSpan(2),
                                                Textarea::make('note')
                                                    ->label('یادداشت')
                                                    ->rows(3)
                                                    ->columnSpan(2),
                                            ]),
                                    ])
                                    ->columnSpanFull()
                                    ->addable(false)
                                    ->deletable(false)
                                    ->reorderable(false)
                                    ->defaultItems(0),
                            ]),
                    ])
                    ->fillForm(fn (Booking $record): array => [
                        'space_display' => $record->subSpace?->space?->title ?? '-',
                        'subspace_display' => $record->subSpace?->title ?? '-',
                        'price_display' => $record->price?->title ?? '-',
                        'status_display' => match ($record->status) {
                            'approve' => 'تایید',
                            'reject' => 'رد',
                            default => 'در انتظار',
                        },
                        'quantity' => $record->quantity,
                        'unit_price' => $record->unit_price,
                        'total_price' => $record->total_price,
                        'deleted_at' => $record->deleted_at,
                        'start' => $record->start,
                        'end' => $record->end,
                        'created_at' => $record->created_at,
                        'updated_at' => $record->updated_at,
                        'note' => $record->note,
                        'payments' => $record->payments
                            ->map(fn ($payment): array => [
                                'id' => $payment->id,
                                'gateway' => $payment->gateway,
                                'status' => $payment->status,
                                'api_cal_counter' => $payment->api_cal_counter,
                                'created_at' => filled($payment->created_at) ? verta($payment->created_at)->format('Y/m/d H:i') : '-',
                                'acc_id' => $payment->acc_id,
                                'acc_details_display' => filled($payment->acc_details) ? json_encode($payment->acc_details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '-',
                                'note' => $payment->note,
                            ])
                            ->all(),
                    ]),
            ]);
    }
}
