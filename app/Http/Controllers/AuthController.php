<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Models\User;
use App\Services\KavenegarSmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(public KavenegarSmsService $sms) {}

    public function login(): View
    {
        return view('auth.login');
    }

    public function sendOtp(SendOtpRequest $request): RedirectResponse
    {
        $mobile = $request->validated()['mobile'];

        $user = User::query()->firstOrCreate(
            ['mobile' => $mobile],
            [
                'username' => $mobile,
                'email' => "{$mobile}@workino.local",
                'password' => Hash::make(Str::random(32)),
                'status' => UserStatus::Active,
            ]
        );

        $code = (string) random_int(100000, 999999);

        $sent = $this->sms->sendOtp($mobile, $code);

        if (! $sent) {
            return back()
                ->withErrors(['mobile' => 'ارسال پیامک با خطا مواجه شد.'])
                ->withInput(['mobile' => $mobile]);
        }

        Cache::put($this->otpCacheKey($mobile), [
            'code' => $code,
            'user_id' => $user->id,
        ], now()->addMinutes(5));

        $request->session()->put('otp_mobile', $mobile);

        return back()
            ->with('otp_sent', true)
            ->withInput(['mobile' => $mobile]);
    }

    public function verifyOtp(VerifyOtpRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $payload = Cache::get($this->otpCacheKey($data['mobile']));

        if (! is_array($payload) || ($payload['code'] ?? null) !== $data['code']) {
            return back()
                ->withErrors(['code' => 'کد تایید صحیح نیست.'])
                ->with('otp_sent', true)
                ->withInput(['mobile' => $data['mobile']]);
        }

        $user = User::query()->find($payload['user_id']);

        if (! $user) {
            return back()
                ->withErrors(['mobile' => 'کاربر یافت نشد.'])
                ->with('otp_sent', true)
                ->withInput(['mobile' => $data['mobile']]);
        }

        Cache::forget($this->otpCacheKey($data['mobile']));
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('profile.index');
    }

    private function otpCacheKey(string $mobile): string
    {
        return "otp:{$mobile}";
    }
}
