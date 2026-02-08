<?php

namespace App\Filament\Resources\UserMetas\Pages;

use App\Filament\Resources\UserMetas\UserMetaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserMeta extends CreateRecord
{
    protected static string $resource = UserMetaResource::class;

    public function getTitle(): string
    {
        return 'ایجاد فیلد پروفایل کاربری';
    }
}
