<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Users\Widgets\UserBookingsTable;
use App\Filament\Resources\Users\Widgets\UserPaymentsTable;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'مشاهده کاربر';
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->hasInfolist()
                    ? $this->getInfolistContentComponent()
                    : $this->getFormContentComponent(),
                ...$this->getWidgetsSchemaComponents([
                    UserBookingsTable::make([
                        'userId' => $this->record->getKey(),
                    ]),
                    UserPaymentsTable::make([
                        'userId' => $this->record->getKey(),
                    ]),
                ]),
            ]);
    }
}
