<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    public function getTitle(): string
    {
        return 'نقش ها';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
