<?php

use App\Services\OtpService;
use App\Services\SmsService;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\User;

new class extends Component {
    public bool $open = false;
    public string $step = 'phone';
    public string $phone = '';
    public string $otp = '';
    public ?int $resendAt = null; // timestamp برای تایمر دقیق

    protected OtpService $otpService;

    public function boot(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    #[On('open-auth')]
    public function openModal()
    {
        $this->reset(['step', 'phone', 'otp', 'resendAt']);
        $this->resetValidation();
        $this->open = true;
    }

    public function closeModal()
    {
        $this->open = false;
    }

    /**
     * ارسال OTP با تمام محدودیت‌ها و کش
     */
    public function sendOtp()
    {
        $this->validate([
            'phone' => ['required', 'digits:11', 'regex:/^09[0-9]{9}$/'],
        ]);

        // RateLimiter برای جلوگیری از اسپم (۵ بار در ۱۰ دقیقه)
        $sendKey = 'otp-send:' . $this->phone;
        if (RateLimiter::tooManyAttempts($sendKey, 5)) {
            $this->addError('phone', 'تعداد درخواست‌ها زیاد است، ۱۰ دقیقه صبر کنید');
            return;
        }
        RateLimiter::hit($sendKey, 600); // ۵ بار در ۱۰ دقیقه

        // بررسی کول‌داون ۶۰ ثانیه از کش
        $cached = cache()->get('otp:' . $this->phone);
        if ($cached && isset($cached['resend_at']) && now()->lt($cached['resend_at'])) {
            $remaining = $cached['resend_at']->diffInSeconds(now());
            $this->addError('phone', "لطفاً {$remaining} ثانیه صبر کنید");
            return;
        }

        // صدور OTP جدید
        $this->issueOtp();

        $this->step = 'otp';
        $this->resendAt = now()->addSeconds(60)->timestamp;
    }

    /**
     * صدور OTP و ذخیره در کش با هش
     */
    private function issueOtp(): void
    {
//        $code = random_int(1000, 9999);
        $code = 1234;
        cache()->put(
            'otp:' . $this->phone,
            [
                'code' => Hash::make((string)$code),
                'resend_at' => now()->addSeconds(60),
                'created_at' => now(),
            ],
            now()->addMinutes(2)
        );

        // ارسال پیامک
        try {
            app(SmsService::class)->sendSms($this->phone, "کد ورود: {$code}");
        } catch (\Exception $e) {
            // لاگ خطا اما به کاربر نشان نده
            logger()->error('SMS sending failed: ' . $e->getMessage());
        }
    }

    /**
     * تأیید OTP با محدودیت تعداد تلاش
     */
    public function verifyOtp()
    {
        $this->validate([
            'otp' => ['required', 'digits:4'],
        ], [
            'otp.required' => 'کد را وارد کنید',
            'otp.digits' => 'کد باید ۴ رقم باشد',
        ]);

        // محدودیت تعداد تلاش برای وارد کردن OTP (۵ بار در ۲ دقیقه)
        $checkKey = 'otp-check:' . $this->phone;
        if (RateLimiter::tooManyAttempts($checkKey, 5)) {
            $this->addError('otp', 'تعداد تلاش بیش از حد مجاز است، ۲ دقیقه صبر کنید');
            return;
        }
        RateLimiter::hit($checkKey, 120);

        $cached = cache()->get('otp:' . $this->phone);

        // بررسی وجود OTP و صحت آن با هش
        if (!$cached || !isset($cached['code'])) {
            $this->addError('otp', 'کد منقضی شده است، دوباره درخواست کنید');
            return;
        }

        if (!Hash::check((string)$this->otp, $cached['code'])) {
            $this->addError('otp', 'کد وارد شده صحیح نیست');
            return;
        }

        // پاک کردن تمام محدودیت‌ها و کش
        $this->otpService->clear($this->phone);

        // پیدا کردن یا ایجاد کاربر
        $user = User::firstOrCreate(
            ['phone' => $this->phone]
        );

        Auth::login($user, remember: true);
        session()->regenerate();

        $this->open = false;
        $this->redirect(request()->header('Referer', '/'), navigate: true);
    }

    /**
     * ارسال مجدد OTP با رعایت کول‌داون
     */
    public function resendOtp()
    {
        $cached = cache()->get('otp:' . $this->phone);

        // اگر OTP وجود نداشت، برگرد به مرحله شماره
        if (!$cached) {
            $this->step = 'phone';
            $this->reset(['otp', 'resendAt']);
            $this->addError('phone', 'کد منقضی شده، دوباره درخواست کنید');
            return;
        }

        // بررسی کول‌داون ۶۰ ثانیه
        if (isset($cached['resend_at']) && now()->lt($cached['resend_at'])) {
            $remaining = $cached['resend_at']->diffInSeconds(now());
            $this->addError('otp', "لطفاً {$remaining} ثانیه صبر کنید");
            return;
        }

        // صدور OTP جدید با کد جدید
        $this->issueOtp();
        $this->resendAt = now()->addSeconds(60)->timestamp;
        $this->reset(['otp']);
        $this->resetValidation();

        // نمایش پیام موفقیت
        session()->flash('message', 'کد جدید ارسال شد');
    }

    /**
     * بازگشت به مرحله شماره
     */
    public function backToPhone()
    {
        $this->step = 'phone';
        $this->otp = '';
        $this->resendAt = null;
        $this->resetValidation();
    }
};
?>
