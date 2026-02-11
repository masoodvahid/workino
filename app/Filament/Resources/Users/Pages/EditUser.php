<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected array $metaData = [];

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'ویرایش کاربر';
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $meta = $this->getRecord()->userMetas()->pluck('value', 'key')->all();

        foreach (User::META_KEYS as $key) {
            if (array_key_exists($key, $meta)) {
                $data[$key] = $meta[$key];
            }
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        [$data, $meta] = $this->extractMetaData($data);
        $this->metaData = $meta;

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->setMetaValues($this->metaData);
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
