<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KavenegarSmsService
{
    public function sendOtp(string $mobile, string $code): bool
    {
        $apiKey = config('services.kavenegar.api_key');
        $template = config('services.kavenegar.otp_template');

        if (! $apiKey || ! $template) {
            return false;
        }

        $url = "https://api.kavenegar.com/v1/{$apiKey}/verify/lookup.json";

        $response = Http::get($url, [
            'receptor' => $mobile,
            'token' => $code,
            'template' => $template,
        ]);

        if (! $response->ok()) {
            return false;
        }

        return (int) ($response->json('return.status') ?? 0) === 200;
    }
}
