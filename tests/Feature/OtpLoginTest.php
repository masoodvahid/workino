<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OtpLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_mobile_otp(): void
    {
        $mobile = '09123456789';

        config([
            'services.kavenegar.api_key' => 'test-key',
            'services.kavenegar.otp_template' => 'otp',
        ]);

        Http::fake([
            'api.kavenegar.com/*' => Http::response([
                'return' => [
                    'status' => 200,
                    'message' => 'تایید شد',
                ],
            ], 200),
        ]);

        $this->post(route('auth.otp.send'), [
            'mobile' => $mobile,
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'mobile' => $mobile,
        ]);

        $payload = Cache::get("otp:{$mobile}");

        $this->post(route('auth.otp.verify'), [
            'mobile' => $mobile,
            'code' => $payload['code'] ?? null,
        ])->assertRedirect(route('profile.index'));

        $this->assertAuthenticated();

        $user = User::query()->where('mobile', $mobile)->first();

        $this->assertAuthenticatedAs($user);
    }
}
