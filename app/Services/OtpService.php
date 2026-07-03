<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class OtpService
{
    private string $prefix = 'otp:';
    private int $expiryMinutes = 2;
    private int $resendCooldown = 60; // ثانیه
    private int $maxAttempts = 5; // تعداد تلاش برای وارد کردن OTP

    /**
     * صدور OTP جدید
     */
    public function issue(string $phone): string
    {
        $code = random_int(1000, 9999);

        Cache::put(
            $this->getKey($phone),
            [
                'code' => Hash::make((string) $code),
                'resend_at' => now()->addSeconds($this->resendCooldown),
                'created_at' => now(),
                'attempts' => 0,
            ],
            now()->addMinutes($this->expiryMinutes)
        );

        return (string) $code;
    }

    /**
     * بررسی آیا OTP معتبر است
     */
    public function verify(string $phone, string $otp): bool
    {
        $data = Cache::get($this->getKey($phone));

        if (!$data || !isset($data['code'])) {
            return false;
        }

        return Hash::check($otp, $data['code']);
    }

    /**
     * دریافت اطلاعات OTP
     */
    public function get(string $phone): ?array
    {
        return Cache::get($this->getKey($phone));
    }

    /**
     * آیا امکان ارسال مجدد وجود دارد؟
     */
    public function canResend(string $phone): bool
    {
        $data = $this->get($phone);

        if (!$data) {
            return true; // اگر OTP وجود نداشت، می‌توان ارسال کرد
        }

        if (!isset($data['resend_at'])) {
            return true;
        }

        return now()->gte($data['resend_at']);
    }

    /**
     * زمان باقی‌مانده تا ارسال مجدد (بر حسب ثانیه)
     */
    public function getResendRemaining(string $phone): int
    {
        $data = $this->get($phone);

        if (!$data || !isset($data['resend_at'])) {
            return 0;
        }

        $remaining = $data['resend_at']->diffInSeconds(now(), false);

        return max(0, (int) $remaining);
    }

    /**
     * پاک کردن تمام داده‌های OTP و محدودیت‌ها
     */
    public function clear(string $phone): void
    {
        Cache::forget($this->getKey($phone));
        RateLimiter::clear('otp-send:' . $phone);
        RateLimiter::clear('otp-check:' . $phone);
    }

    /**
     * بررسی محدودیت تعداد تلاش برای وارد کردن OTP
     */
    public function checkAttempts(string $phone): bool
    {
        $key = 'otp-check:' . $phone;

        if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
            return false;
        }

        RateLimiter::hit($key, 120); // ۲ دقیقه
        return true;
    }

    /**
     * تولید کلید کش
     */
    private function getKey(string $phone): string
    {
        return $this->prefix . $phone;
    }
}
