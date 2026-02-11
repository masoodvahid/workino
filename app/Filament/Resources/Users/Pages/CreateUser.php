<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected array $metaData = [];

    public function getTitle(): string
    {
        return 'ایجاد کاربر';
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

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()->label('ذخیره و جدید');
    }

    protected function extractMetaData(array $data): array
    {
        $meta = [];

        foreach (User::META_KEYS as $key) {
            if (array_key_exists($key, $data)) {
                $meta[$key] = $data[$key];
                unset($data[$key]);
            }
        }

        return [$data, $meta];
    }
}
