<?php

namespace App\Filament\Pages\Settings;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action as FormAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SmsSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'تنظیمات سامانه';

    protected static ?string $navigationLabel = 'تنظیمات پنل پیامک';

    protected static ?string $title = 'تنظیمات پنل پیامک';

    protected string $view = 'filament.pages.settings.sms-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getFormData());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('sms_api')
                    ->label('Api Key')
                    ->required(),
                TextInput::make('sms_number')
                    ->label('شماره اختصاصی ارسال پیامک')
                    ->numeric()
                    ->required(),
                Checkbox::make('sms_admin_notice')
                    ->label('ارسال پیامک ورود به سامانه برای مدیران'),
                Actions::make([
                    FormAction::make('save')
                        ->label('ذخیره تنظیمات')
                        ->action(fn () => $this->save())
                        ->color('primary'),
                    FormAction::make('delete')
                        ->label('حذف تنظیمات')
                        ->requiresConfirmation()
                        ->action(fn () => $this->deleteSettings())
                        ->color('danger'),
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->upsertSetting('sms_api', $data['sms_api'] ?? null, 'SMS');
        $this->upsertSetting('sms_number', $data['sms_number'] ?? null, 'SMS');
        $this->upsertSetting('sms_admin_notice', (bool) ($data['sms_admin_notice'] ?? false), 'sms');

        Notification::make()
            ->title('تنظیمات پیامک ذخیره شد')
            ->success()
            ->send();
    }

    public function deleteSettings(): void
    {
        Setting::query()
            ->whereIn('key', ['sms_api', 'sms_number', 'sms_admin_notice'])
            ->delete();

        $this->form->fill([
            'sms_api' => null,
            'sms_number' => null,
            'sms_admin_notice' => false,
        ]);

        Notification::make()
            ->title('تنظیمات پیامک حذف شد')
            ->success()
            ->send();
    }

    private function getFormData(): array
    {
        return [
            'sms_api' => $this->getSettingValue('sms_api'),
            'sms_number' => $this->getSettingValue('sms_number'),
            'sms_admin_notice' => (bool) $this->getSettingValue('sms_admin_notice'),
        ];
    }

    private function getSettingValue(string $key): mixed
    {
        return Setting::query()
            ->where('key', $key)
            ->first()
            ?->value;
    }

    private function upsertSetting(string $key, mixed $value, string $group): void
    {
        Setting::query()->updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'status' => true,
            ],
        );
    }
}
