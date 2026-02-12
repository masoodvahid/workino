<?php

namespace App\Filament\Resources\Spaces\Pages;

use App\Filament\Resources\Spaces\SpaceResource;
use App\Models\Space;
use Filament\Resources\Pages\CreateRecord;

class CreateSpace extends CreateRecord
{
    protected static string $resource = SpaceResource::class;

    protected array $metaData = [];

    public function getTitle(): string
    {
        return 'افزودن مرکز';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        [$data, $meta] = $this->extractMetaData($data);
        $this->metaData = $meta;

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->setMetaValues($this->metaData);
    }

    protected function extractMetaData(array $data): array
    {
        $meta = [];

        foreach (Space::META_KEYS as $key) {
            if (array_key_exists($key, $data)) {
                $meta[$key] = $data[$key];
                unset($data[$key]);
            }
        }

        return [$data, $meta];
    }
}
