<?php

namespace App\Filament\Widgets;

use App\Models\Space;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PopularSpaces extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        $topSpaceIds = $this->getTopSpaceIds(5);

        return $table
            ->heading('فضاهای محبوب')
            ->description('فضاها بر اساس تعداد بازدید')
            ->query(
                fn (): Builder => Space::query()
                    ->with(['spaceMetas'])
                    ->withCount(['bookings'])
                    ->whereIn('id', array_keys($topSpaceIds) ?: [0])
            )
            ->modifyQueryUsing(function (Builder $query) use ($topSpaceIds): Builder {
                if (empty($topSpaceIds)) {
                    return $query;
                }

                $orderedIds = implode(',', array_keys($topSpaceIds));

                return $query->orderByRaw("FIELD(id, {$orderedIds})");
            })
            ->paginated(false)
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان')
                    ->weight('semibold')
                    ->searchable(),

                TextColumn::make('city')
                    ->label('شهر')
                    ->state(fn (Space $record): string => (string) ($record->metaValue('city') ?? '—')),

                TextColumn::make('bookings_count')
                    ->label('تعداد رزرو')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('views')
                    ->label('بازدید')
                    ->state(fn (Space $record): string => number_format(
                        (int) ($topSpaceIds[$record->id] ?? 0)
                    ))
                    ->badge()
                    ->color('info'),
            ]);
    }

    /**
     * @return array<int, int> spaceId => totalViews
     */
    private function getTopSpaceIds(int $limit): array
    {
        $rows = DB::table('space_meta')
            ->where('key', 'view_count')
            ->whereNotNull('space_id')
            ->get(['space_id', 'value']);

        $totals = [];

        foreach ($rows as $row) {
            $value = $this->extractNumericViewValue($row->value);

            if ($value <= 0) {
                continue;
            }

            $totals[(int) $row->space_id] = ($totals[(int) $row->space_id] ?? 0) + (int) $value;
        }

        arsort($totals);

        return array_slice($totals, 0, $limit, true);
    }

    private function extractNumericViewValue(mixed $value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            }
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        if (is_array($value)) {
            foreach (['view_count', 'views', 'count', 'value'] as $key) {
                if (isset($value[$key]) && is_numeric($value[$key])) {
                    return (float) $value[$key];
                }
            }

            foreach ($value as $item) {
                if (is_numeric($item)) {
                    return (float) $item;
                }
            }
        }

        return 0.0;
    }
}
