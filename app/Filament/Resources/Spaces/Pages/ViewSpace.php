<?php

namespace App\Filament\Resources\Spaces\Pages;

use App\Filament\Resources\Spaces\SpaceResource;
use App\Filament\Resources\SubSpaces\SubSpaceResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSpace extends ViewRecord
{
    protected static string $resource = SpaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create_subspace')
                ->label('افزودن زیر مجموعه')
                ->url(fn (): string => SubSpaceResource::getUrl('create', [
                    'space_id' => $this->record->getKey(),
                    'return_url' => static::getResource()::getUrl('view', ['record' => $this->record]),
                ])),
            EditAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return (string) ($this->record->title ?? 'مرکز');
    }
}
