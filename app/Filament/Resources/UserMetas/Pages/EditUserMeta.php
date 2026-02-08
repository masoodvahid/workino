<?php

namespace App\Filament\Resources\UserMetas\Pages;

use App\Filament\Resources\UserMetas\UserMetaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditUserMeta extends EditRecord
{
    protected static string $resource = UserMetaResource::class;

    public function getTitle(): string
    {
        return 'ویرایش فیلد پروفایل کاربری';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
