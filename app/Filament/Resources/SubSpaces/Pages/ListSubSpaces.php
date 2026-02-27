<?php

namespace App\Filament\Resources\SubSpaces\Pages;

use App\Filament\Resources\SubSpaces\SubSpaceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubSpaces extends ListRecords
{
    protected static string $resource = SubSpaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
