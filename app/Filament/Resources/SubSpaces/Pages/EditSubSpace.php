<?php

namespace App\Filament\Resources\SubSpaces\Pages;

use App\Enums\BookingStatus;
use App\Filament\Resources\Spaces\SpaceResource;
use App\Filament\Resources\SubSpaces\SubSpaceResource;
use App\Models\SubSpace;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditSubSpace extends EditRecord
{
    protected static string $resource = SubSpaceResource::class;

    protected array $metaData = [];

    protected array $pricesData = [];

    protected function getHeaderActions(): array
    {
        return [
            Action::make('return_to_space')
                ->label('بازگشت به صفحه قبل')
                ->color('gray')
                ->url(fn (): string => SpaceResource::getUrl('view', ['record' => $this->record->space_id])),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'ویرایش ' . ($this->record->title ?? 'زیرمجموعه');
    }

    public function getBreadcrumbs(): array
    {
        $spaceTitle = (string) ($this->record->space?->title ?? 'مرکز');

        return [
            SpaceResource::getUrl('view', ['record' => $this->record->space_id]) => $spaceTitle,
            static::getResource()::getUrl('edit', ['record' => $this->record]) => $this->getTitle(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $meta = $this->getRecord()->subSpaceMetas()->pluck('value', 'key')->all();

        foreach (SubSpace::META_KEYS as $key) {
            if (array_key_exists($key, $meta)) {
                $data[$key] = $meta[$key];
            }
        }

        $data['prices'] = $this->getRecord()->prices
            ->map(fn ($price): array => [
                'id' => $price->id,
                'title' => $price->title,
                'description' => $price->description,
                'unit' => $price->unit?->value ?? (string) $price->unit,
                'base_price' => $price->base_price,
                'special_price' => $price->special_price,
                'start' => $price->start?->format('Y-m-d'),
                'end' => $price->end?->format('Y-m-d'),
                'priority' => $price->priority,
                'status' => $price->status?->value ?? (string) $price->status,
            ])
            ->values()
            ->all();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        [$data, $meta] = $this->extractMetaData($data);
        [$data, $prices] = $this->extractPrices($data);

        $this->metaData = $meta;
        $this->pricesData = $prices;

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->setMetaValues($this->metaData);
        $this->syncPrices($this->record, $this->pricesData);
    }

    protected function getRedirectUrl(): string
    {
        return request()->query('return_url')
            ?: static::getResource()::getUrl('edit', ['record' => $this->record]);
    }

    private function extractMetaData(array $data): array
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

    private function extractPrices(array $data): array
    {
        $prices = collect($data['prices'] ?? [])
            ->filter(fn ($item): bool => is_array($item) && filled($item['title'] ?? null) && filled($item['unit'] ?? null))
            ->values()
            ->map(function (array $item, int $index): array {
                $payload = [
                    'title' => $item['title'],
                    'description' => $item['description'] ?? null,
                    'unit' => $item['unit'],
                    'base_price' => (int) ($item['base_price'] ?? 0),
                    'special_price' => filled($item['special_price'] ?? null) ? (int) $item['special_price'] : null,
                    'start' => $item['start'] ?? null,
                    'end' => $item['end'] ?? null,
                    'priority' => $index + 1,
                    'status' => $item['status'] ?? BookingStatus::Active->value,
                    // TODO: restore unit_rules handling when special per-unit rules UI is reintroduced.
                    'unit_rules' => null,
                ];

                if (filled($item['id'] ?? null)) {
                    $payload['id'] = (int) $item['id'];
                }

                return $payload;
            })
            ->all();

        unset($data['prices']);

        return [$data, $prices];
    }

    private function syncPrices(SubSpace $subSpace, array $prices): void
    {
        $existingIds = collect($prices)
            ->pluck('id')
            ->filter()
            ->map(fn ($id): int => (int) $id)
            ->values()
            ->all();

        if (empty($existingIds)) {
            $subSpace->prices()->delete();
        } else {
            $subSpace->prices()->whereNotIn('id', $existingIds)->delete();
        }

        foreach ($prices as $price) {
            $id = $price['id'] ?? null;
            unset($price['id']);

            if ($id) {
                $subSpace->prices()->whereKey($id)->update($price);
                continue;
            }

            $subSpace->prices()->create($price);
        }
    }
}
