<?php

namespace App\Filament\Resources\Spaces\Pages;

use App\Filament\Resources\Spaces\SpaceResource;
use App\Filament\Resources\Spaces\Widgets\LatestSpaceBookings;
use App\Filament\Resources\Spaces\Widgets\LatestSpacePayments;
use App\Filament\Resources\SubSpaces\SubSpaceResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

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
            Action::make('front_view')
                ->label('مشاهده')
                ->url(fn (): string => route('spaces.show', $this->record->slug))
                ->openUrlInNewTab(),
            EditAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return (string) ($this->record->title ?? 'مرکز');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->hasInfolist()
                    ? $this->getInfolistContentComponent()
                    : $this->getFormContentComponent(),
                ...$this->getWidgetsSchemaComponents([
                    LatestSpaceBookings::make([
                        'spaceId' => $this->record->getKey(),
                    ]),
                    LatestSpacePayments::make([
                        'spaceId' => $this->record->getKey(),
                    ]),
                ]),
                $this->getRelationManagersContentComponent(),
            ]);
    }
}
