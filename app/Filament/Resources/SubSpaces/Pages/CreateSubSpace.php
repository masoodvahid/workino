<?php

namespace App\Filament\Resources\SubSpaces\Pages;

use App\Enums\BookingStatus;
use App\Enums\BookingUnit;
use App\Filament\Resources\SubSpaces\SubSpaceResource;
use App\Models\SubSpace;
use Filament\Resources\Pages\CreateRecord;

class CreateSubSpace extends CreateRecord
{
    protected static string $resource = SubSpaceResource::class;

    protected array $metaData = [];

    protected array $pricesData = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        [$data, $meta] = $this->extractMetaData($data);
        [$data, $prices] = $this->extractPrices($data);

        $this->metaData = $meta;
        $this->pricesData = $prices;

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->setMetaValues($this->metaData);

        foreach ($this->pricesData as $price) {
            $this->record->prices()->create($price);
        }
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
                return [
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
            })
            ->all();

        unset($data['prices']);

        return [$data, $prices];
    }
}
