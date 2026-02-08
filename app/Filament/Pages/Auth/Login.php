<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use App\Services\KavenegarSmsService;
use Filament\Actions\Action as FormAction;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public $login_type = 'password';

    public $otp_sent = false;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('mobile')
                    ->label('شماره موبایل')
                    ->required()
                    ->autocomplete()
                    ->autofocus()
                    ->extraInputAttributes(['tabindex' => 1]),

                TextInput::make('password')
                    ->label('رمز عبور')
                    ->password()
                    ->revealable()
                    ->autocomplete('current-password')
                    ->required(fn (Get $get) => $get('login_type') === 'password')
                    ->visible(fn (Get $get) => $get('login_type') === 'password')
                    ->extraInputAttributes(['tabindex' => 2]),

                Actions::make([
                    FormAction::make('send_otp')
                        ->label(fn () => $this->otp_sent ? 'ارسال مجدد کد' : 'ارسال کد تایید')
                        ->action(fn (Get $get) => $this->sendOtp($get('mobile')))
                        ->color('primary')
                        ->visible(fn (Get $get) => $get('login_type') === 'otp'),
                ]),

                TextInput::make('otp_code')
                    ->label('کد تایید')
                    ->numeric()
                    ->length(6)
                    ->required(fn (Get $get) => $get('login_type') === 'otp')
                    ->visible(fn (Get $get) => $get('login_type') === 'otp' && $this->otp_sent)
                    ->extraInputAttributes(['tabindex' => 2]),

                Radio::make('login_type')
                    ->label('روش ورود')
                    ->options([
                        'password' => 'رمز عبور',
                        'otp' => 'رمز یکبار مصرف (OTP)',
                    ])
                    ->default('password')
                    ->inline()
                    ->live(),
            ])
            ->statePath('data');
    }

    public function sendOtp($mobile)
    {
        if (! $mobile) {
            Notification::make()
                ->title('لطفا شماره موبایل را وارد کنید')
                ->danger()
                ->send();

            return;
        }

        $user = User::where('mobile', $mobile)->first();
        if (! $user) {
            Notification::make()
                ->title('کاربری با این شماره موبایل یافت نشد')
                ->danger()
                ->send();

            return;
        }

        // Rate limiting
        $key = 'otp_rate_'.$mobile;
        if (Cache::has($key)) {
            Notification::make()
                ->title('لطفا کمی صبر کنید')
                ->warning()
                ->send();

            return;
        }

        $code = (string) random_int(100000, 999999);
        Cache::put("otp_login_{$mobile}", [
            'code' => $code,
            'user_id' => $user->id,
        ], now()->addMinutes(5));
        Cache::put($key, true, now()->addMinutes(1)); // 1 minute cooldown

        // Send SMS
        try {
            app(KavenegarSmsService::class)->sendOtp($mobile, $code);

            $this->otp_sent = true;

            Notification::make()
                ->title('کد تایید ارسال شد')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('خطا در ارسال پیامک')
                ->danger()
                ->send();
        }
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'mobile' => $data['mobile'],
            'password' => $data['password'],
        ];
    }

    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        if ($data['login_type'] === 'otp') {
            $mobile = $data['mobile'];
            $code = $data['otp_code'] ?? null;

            if (! $this->otp_sent) {
                $this->addError('data.otp_code', 'لطفا ابتدا کد تایید دریافت کنید.');

                return null;
            }

            $cached = Cache::get("otp_login_{$mobile}");

            if (! $cached || $cached['code'] !== $code) {
                $this->throwFailureValidationException();
            }

            $user = User::find($cached['user_id']);

            if (! $user) {
                $this->throwFailureValidationException();
            }

            Filament::auth()->login($user, $data['remember'] ?? false);
            Cache::forget("otp_login_{$mobile}");

            session()->regenerate();

            return app(LoginResponse::class);
        }

        return parent::authenticate();
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.mobile' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }
}
