<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

    public function getTitle(): string
    {
        return 'جزئیات نقش';
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('title')
                ->label('عنوان'),
            TextEntry::make('key')
                ->label('کلید')
                ->badge(),
            TextEntry::make('permissions')
                ->label('دسترسی ها')
                ->formatStateUsing(fn ($state): string => blank($state) ? '-' : implode(' ، ', $state)),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
