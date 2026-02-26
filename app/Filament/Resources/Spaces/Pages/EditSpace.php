<?php

namespace App\Filament\Resources\Spaces\Pages;

use App\Filament\Resources\Spaces\SpaceResource;
use App\Models\Space;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EditSpace extends EditRecord
{
    protected static string $resource = SpaceResource::class;

    protected array $metaData = [];

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('بازگشت')
                ->url($this->previousUrl ?? static::getResource()::getUrl('index'))
                ->color('gray'),
            Action::make('save')
                ->label('ذخیره')
                ->action('save')
                ->color('primary'),
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return 'ویرایش مرکز';
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $meta = $this->getRecord()->spaceMetas()->pluck('value', 'key')->all();

        foreach (Space::META_KEYS as $key) {
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
        $this->record->setMetaValues($this->moveMetaFilesToSpaceDirectory($this->metaData));
    }

    protected function extractMetaData(array $data): array
    {
        $meta = [];

        foreach (Space::META_KEYS as $key) {
            if (array_key_exists($key, $data)) {
                $meta[$key] = $data[$key];
                unset($data[$key]);
            }
        }

        return [$data, $meta];
    }

    private function moveMetaFilesToSpaceDirectory(array $meta): array
    {
        $disk = Storage::disk('public');
        $targetDirectory = "spaces/{$this->record->id}";

        foreach (['logo', 'featured_image'] as $singleFileKey) {
            $value = $meta[$singleFileKey] ?? null;

            if (! is_string($value) || blank($value)) {
                continue;
            }

            $meta[$singleFileKey] = $this->moveFilePath($disk, $value, $targetDirectory) ?? $value;
        }

        $images = $meta['images'] ?? null;

        if (is_array($images)) {
            $meta['images'] = collect($images)
                ->map(fn ($path): mixed => is_string($path) ? ($this->moveFilePath($disk, $path, $targetDirectory) ?? $path) : $path)
                ->values()
                ->all();
        }

        return $meta;
    }

    private function moveFilePath($disk, string $path, string $targetDirectory): ?string
    {
        if (blank($path) || Str::startsWith($path, ['http://', 'https://', '/'])) {
            return null;
        }

        if (Str::startsWith($path, "{$targetDirectory}/")) {
            return $path;
        }

        $sourceDisk = $disk->exists($path)
            ? $disk
            : (Storage::disk('local')->exists($path) ? Storage::disk('local') : null);

        if (! $sourceDisk) {
            return null;
        }

        $filename = pathinfo($path, PATHINFO_BASENAME);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $targetPath = "{$targetDirectory}/{$filename}";
        $counter = 1;

        while ($disk->exists($targetPath)) {
            $targetPath = "{$targetDirectory}/{$name}-{$counter}" . ($extension ? ".{$extension}" : '');
            $counter++;
        }

        $disk->makeDirectory($targetDirectory);
        $disk->put($targetPath, $sourceDisk->get($path));

        if ($sourceDisk->exists($path)) {
            $sourceDisk->delete($path);
        }

        return $targetPath;
    }
}
