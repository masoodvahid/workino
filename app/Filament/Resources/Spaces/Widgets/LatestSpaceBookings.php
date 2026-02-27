<?php

namespace App\Filament\Resources\Spaces\Widgets;

use App\Filament\Resources\SubSpaces\SubSpaceResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Booking;
use App\Models\Price;
use App\Models\SubSpace;
use App\Models\User;
use App\Support\NumberNormalizer;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\HtmlString;

class LatestSpaceBookings extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    public int $spaceId;

    public function table(Table $table): Table
    {
        return $table
            ->heading('آخرین رزروها')           
            ->headerActions([
                Action::make('create_booking')
                    ->label('ثبت رزرو دستی')
                    ->icon('heroicon-o-plus')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                Select::make('user_id')
                                    ->label('کاربر')
                                    ->searchable()
                                    ->getSearchResultsUsing(fn (?string $search): array => User::query()
                                        ->when(
                                            filled($search),
                                            fn ($query) => $query->where(function ($query) use ($search): void {
                                                $query
                                                    ->where('name', 'like', "%{$search}%")
                                                    ->orWhere('mobile', 'like', "%{$search}%");
                                            }),
                                        )
                                        ->limit(50)
                                        ->get()
                                        ->mapWithKeys(fn (User $user): array => [$user->id => self::userLabel($user)])
                                        ->all())
                                    ->getOptionLabelUsing(fn ($value): ?string => filled($value) ? self::userLabel(User::find($value)) : null)
                                    ->required(),
                                Select::make('subspace_id')
                                    ->label('زیرمجموعه')
                                    ->options(fn (): array => SubSpace::query()
                                        ->where('space_id', $this->spaceId)
                                        ->orderBy('title')
                                        ->pluck('title', 'id')
                                        ->all())
                                    ->live()
                                    ->afterStateUpdated(function (Set $set): void {
                                        $set('price_id', null);
                                        $set('unit_price', null);
                                        $set('total_price', null);
                                    })
                                    ->required(),
                                Select::make('price_id')
                                    ->label('تعرفه')
                                    ->options(fn (Get $get): array => Price::query()
                                        ->where('subspace_id', $get('subspace_id'))
                                        ->where('status', 'active')
                                        ->orderBy('priority')
                                        ->get()
                                        ->mapWithKeys(fn (Price $price): array => [$price->id => self::priceLabel($price)])
                                        ->all())
                                    ->live()
                                    ->afterStateUpdated(function (?string $state, Set $set, Get $get): void {
                                        $price = filled($state) ? Price::query()->find($state) : null;
                                        $unitPrice = self::resolveUnitPrice($price);

                                        $set('unit_price', self::formatIntegerInput($unitPrice));
                                        $set('total_price', self::formatIntegerInput(self::calculateTotalPrice($get('quantity'), $unitPrice)));
                                    })
                                    ->required(),
                                TextInput::make('quantity')
                                    ->label('تعداد')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->live()
                                    ->afterStateUpdated(function (?string $state, Set $set, Get $get): void {
                                        $set('total_price', self::formatIntegerInput(self::calculateTotalPrice($state, $get('unit_price'))));
                                    })
                                    ->required(),
                                TextInput::make('unit_price')
                                    ->label('قیمت واحد (ریال)')
                                    ->stripCharacters(',')
                                    ->live()
                                    ->afterStateUpdated(function (?string $state, Set $set, Get $get): void {
                                        $formatted = self::formatIntegerInput($state);

                                        $set('unit_price', $formatted);
                                        $set('total_price', self::formatIntegerInput(self::calculateTotalPrice($get('quantity'), $formatted)));
                                    })
                                    ->rules(['required', 'integer'])
                                    ->required(),
                                TextInput::make('total_price')
                                    ->label('مبلغ کل (ریال)')
                                    ->readOnly()
                                    ->dehydrated()
                                    ->stripCharacters(',')
                                    ->rules(['required', 'integer'])
                                    ->required(),
                                DatePicker::make('start')
                                    ->label('تاریخ شروع')
                                    ->native(false)
                                    ->required(),
                                DatePicker::make('end')
                                    ->label('تاریخ پایان')
                                    ->native(false)
                                    ->required(),
                                Select::make('status')
                                    ->label('وضعیت')
                                    ->options([
                                        'pending' => 'در انتظار',
                                        'approve' => 'تایید',
                                        'reject' => 'رد',
                                    ])
                                    ->default('pending')
                                    ->required(),
                                Textarea::make('note')
                                    ->label('یادداشت')
                                    ->rows(3)
                                    ->columnSpanFull()
                                    ->nullable(),
                            ]),
                    ])                    
                    ->action(function (array $data): void {
                        Booking::query()->create([
                            'user_id' => (int) $data['user_id'],
                            'subspace_id' => (int) $data['subspace_id'],
                            'price_id' => (int) $data['price_id'],
                            'quantity' => max(1, (int) ($data['quantity'] ?? 1)),
                            'unit_price' => (int) self::normalizeIntegerInput($data['unit_price'] ?? null),
                            'total_price' => (int) self::calculateTotalPrice($data['quantity'] ?? 1, $data['unit_price'] ?? null),
                            'start' => $data['start'],
                            'end' => $data['end'],
                            'status' => $data['status'] ?? 'pending',
                            'note' => $data['note'] ?? null,
                        ]);
                    })
                    ->successNotificationTitle('رزرو با موفقیت ثبت شد'),
            ])
            ->query(
                Booking::query()
                    ->withTrashed()
                    ->whereHas('subSpace', fn ($query) => $query->where('space_id', $this->spaceId))
                    ->with(['user', 'subSpace', 'price', 'payments'])
                    ->latest()
            )
            ->defaultPaginationPageOption(20)
            ->recordAction('view')
            ->paginated(true)
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('user.name')
                    ->label('کاربر')
                    ->formatStateUsing(fn (?string $state, Booking $record): string => $state ?: ($record->user?->mobile ?? '-'))
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
                    ->alignEnd()
                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? number_format((int) $state) . ' ریال' : '-')
                    ->toggleable(),
                TextColumn::make('start')
                    ->label('شروع')
                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? verta($state)->format('Y/m/d') : '-')
                    ->toggleable(),
                TextColumn::make('end')
                    ->label('پایان')
                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? verta($state)->format('Y/m/d') : '-')
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
                TextColumn::make('deleted_at')
                    ->label('حذف شده')
                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? verta($state)->format('Y/m/d H:i') : '-')
                    ->badge()
                    ->color(fn (mixed $state): string => filled($state) ? 'danger' : 'gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('ثبت')
                    ->since()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'pending' => 'در انتظار',
                        'approve' => 'تایید',
                        'reject' => 'رد',
                    ]),
                SelectFilter::make('subspace_id')
                    ->label('زیرمجموعه')
                    ->options(fn (): array => SubSpace::query()
                        ->where('space_id', $this->spaceId)
                        ->orderBy('title')
                        ->pluck('title', 'id')
                        ->all()),
                TrashedFilter::make()
                    ->label('حذف شده'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalHeading('جزئیات رزرو')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextInput::make('id')
                                    ->label('شناسه'),
                                Placeholder::make('user_link')
                                    ->label('کاربر')
                                    ->content(fn (Get $get): HtmlString => self::bookingDetailsUserLink($get)),
                                Placeholder::make('subspace_link')
                                    ->label('زیرمجموعه')
                                    ->content(fn (Get $get): HtmlString => self::bookingDetailsSubspaceLink($get)),
                                TextInput::make('price_display')
                                    ->label('تعرفه'),
                                TextInput::make('quantity')
                                    ->label('تعداد'),
                                TextInput::make('unit_price')
                                    ->label('قیمت واحد (ریال)')
                                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? number_format((int) $state) : '-'),
                                TextInput::make('total_price')
                                    ->label('مبلغ کل (ریال)')
                                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? number_format((int) $state) : '-'),
                                TextInput::make('status_display')
                                    ->label('وضعیت'),
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
                    ->extraModalFooterActions([
                        Action::make('approve_booking')
                            ->label('تایید')
                            ->color('success')
                            ->visible(fn (Booking $record): bool => ! $record->trashed())
                            ->requiresConfirmation()
                            ->action(function (Booking $record): void {
                                $record->update(['status' => 'approve']);
                            })
                            ->successNotificationTitle('رزرو تایید شد'),
                        Action::make('reject_booking')
                            ->label('رد درخواست')
                            ->color('danger')
                            ->visible(fn (Booking $record): bool => ! $record->trashed())
                            ->requiresConfirmation()
                            ->action(function (Booking $record): void {
                                $record->update(['status' => 'reject']);
                            })
                            ->successNotificationTitle('رزرو رد شد'),
                        Action::make('delete_booking')
                            ->label('حذف درخواست')
                            ->color('danger')
                            ->visible(fn (Booking $record): bool => ! $record->trashed())
                            ->requiresConfirmation()
                            ->action(function (Booking $record): void {
                                $record->delete();
                            })
                            ->successNotificationTitle('رزرو حذف شد'),
                        Action::make('restore_booking')
                            ->label('بازگردانی')
                            ->color('gray')
                            ->visible(fn (Booking $record): bool => $record->trashed())
                            ->requiresConfirmation()
                            ->action(function (Booking $record): void {
                                $record->restore();
                            })
                            ->successNotificationTitle('رزرو بازیابی شد'),
                    ])
                    ->fillForm(function (Booking $record): array {
                        return [
                            'id' => $record->id,
                            'user_id' => $record->user_id,
                            'user_display' => self::userLabel($record->user) ?? '-',
                            'subspace_id' => $record->subspace_id,
                            'subspace_display' => $record->subSpace?->title ?? '-',
                            'price_display' => $record->price?->title ?? '-',
                            'quantity' => $record->quantity,
                            'unit_price' => $record->unit_price,
                            'total_price' => $record->total_price,
                            'status_display' => match ($record->status) {
                                'approve' => 'تایید',
                                'reject' => 'رد',
                                default => 'در انتظار',
                            },
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
                        ];
                    })
            ]);
    }

    private static function bookingDetailsUserLink(Get $get): HtmlString
    {
        $label = e((string) ($get('user_display') ?: '-'));
        $userId = $get('user_id');

        if (blank($userId)) {
            return new HtmlString($label);
        }

        $url = e(UserResource::getUrl('view', ['record' => $userId]));

        return new HtmlString("<a href=\"{$url}\" class=\"text-primary-600 underline\" target=\"_blank\">{$label}</a>");
    }

    private static function bookingDetailsSubspaceLink(Get $get): HtmlString
    {
        $label = e((string) ($get('subspace_display') ?: '-'));
        $subspaceId = $get('subspace_id');

        if (blank($subspaceId)) {
            return new HtmlString($label);
        }

        $url = e(SubSpaceResource::getUrl('edit', ['record' => $subspaceId]));

        return new HtmlString("<a href=\"{$url}\" class=\"text-primary-600 underline\" target=\"_blank\">{$label}</a>");
    }

    private static function userLabel(?User $user): ?string
    {
        if (! $user) {
            return null;
        }

        return filled($user->name) ? "{$user->name} ({$user->mobile})" : $user->mobile;
    }

    private static function priceLabel(Price $price): string
    {
        $amount = self::resolveUnitPrice($price);

        if (! filled($amount)) {
            return $price->title;
        }

        return "{$price->title} - " . number_format($amount) . ' ریال';
    }

    private static function resolveUnitPrice(?Price $price): ?int
    {
        if (! $price) {
            return null;
        }

        return $price->special_price ?: $price->base_price;
    }

    private static function normalizeIntegerInput(mixed $value): ?string
    {
        $value = NumberNormalizer::normalize(is_scalar($value) ? (string) $value : null);

        if (blank($value)) {
            return null;
        }

        $digits = preg_replace('/[^0-9]/', '', $value);

        return filled($digits) ? $digits : null;
    }

    private static function formatIntegerInput(mixed $value): ?string
    {
        $digits = self::normalizeIntegerInput($value);

        if (blank($digits)) {
            return null;
        }

        return number_format((int) $digits);
    }

    private static function calculateTotalPrice(mixed $quantity, mixed $unitPrice): ?int
    {
        $normalizedUnitPrice = self::normalizeIntegerInput($unitPrice);

        if (blank($normalizedUnitPrice)) {
            return null;
        }

        $normalizedQuantity = max(1, (int) self::normalizeIntegerInput($quantity));

        return $normalizedQuantity * (int) $normalizedUnitPrice;
    }
}
