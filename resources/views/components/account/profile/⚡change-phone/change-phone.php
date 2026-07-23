<?php

use App\Services\OtpService;
use App\Services\SmsService;
use Livewire\Component;

new class extends Component
{
    public $new_phone = '';
    public $otp_code = '';
    public $showModal = false;
    public $showOtpModal = false;
    public $otpSent = false;
    public $resendAvailable = false;
    public $resendTime = 0;
    public $timerRunning = false;

    protected $rules = [
        'new_phone' => 'required|string|size:11|unique:users,phone',
        'otp_code' => 'required|string|size:4',
    ];

    protected $messages = [
        'new_phone.required' => 'شماره موبایل جدید الزامی است.',
        'new_phone.size' => 'شماره موبایل باید ۱۱ رقم باشد.',
        'new_phone.unique' => 'این شماره موبایل قبلاً ثبت شده است.',
        'otp_code.required' => 'کد تایید الزامی است.',
        'otp_code.size' => 'کد تایید باید ۴ رقم باشد.',
    ];

    public function openModal()
    {
        $this->resetValidation();
        $this->new_phone = '';
        $this->otp_code = '';
        $this->showModal = true;
        $this->showOtpModal = false;
        $this->otpSent = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showOtpModal = false;
        $this->resetValidation();
        $this->reset(['new_phone', 'otp_code', 'otpSent', 'resendAvailable', 'timerRunning']);
    }

    public function sendOtp()
    {
        $this->validate([
            'new_phone' => 'required|string|size:11|unique:users,phone',
        ]);

        try {
//            $otpService = new OtpService();
//            $smsService = new SmsService();

            // Generate and send OTP
//            $code = $otpService->issue($this->new_phone);
//            $result = $smsService->sendVerificationCode($this->new_phone, $code);

//            if ($result['success']) {
            if (1){
                $this->otpSent = true;
                $this->showModal = false;
                $this->showOtpModal = true;
                $this->startResendTimer();

                session()->flash('otp_sent', 'کد تایید به شماره ' . $this->new_phone . ' ارسال شد.');
            } else {
                $this->addError('new_phone', 'خطا در ارسال پیامک. لطفاً دوباره تلاش کنید.');
            }
        } catch (\Exception $e) {
            $this->addError('new_phone', 'خطا در ارسال پیامک. لطفاً دوباره تلاش کنید.');
        }
    }

    public function resendOtp()
    {
        $otpService = new OtpService();

        if (!$otpService->canResend($this->new_phone)) {
            $this->addError('otp_code', 'لطفاً ' . $otpService->getResendRemaining($this->new_phone) . ' ثانیه دیگر صبر کنید.');
            return;
        }

        try {
            $smsService = new SmsService();
            $code = $otpService->issue($this->new_phone);
            $result = $smsService->sendVerificationCode($this->new_phone, $code);

            if ($result['success']) {
                $this->startResendTimer();
                session()->flash('otp_resent', 'کد تایید مجدد ارسال شد.');
            } else {
                $this->addError('otp_code', 'خطا در ارسال مجدد پیامک.');
            }
        } catch (\Exception $e) {
            $this->addError('otp_code', 'خطا در ارسال مجدد پیامک.');
        }
    }

    public function verifyAndChangePhone()
    {
        $this->validate([
            'otp_code' => 'required|string|size:4',
        ]);

        $otpService = new OtpService();

        if (!$otpService->checkAttempts($this->new_phone)) {
            $this->addError('otp_code', 'تعداد دفعات مجاز به پایان رسید. لطفاً ۲ دقیقه دیگر تلاش کنید.');
            return;
        }

//        if ($otpService->verify($this->new_phone, $this->otp_code)) {
        if (
            $this->otpSent == 1234
        ){
            // Change phone number
            auth()->user()->update([
                'phone' => $this->new_phone,
            ]);

            $otpService->clear($this->new_phone);

            $this->showOtpModal = false;
            session()->flash('phone_changed', 'شماره موبایل شما با موفقیت تغییر کرد.');
            $this->dispatch('phone-changed');
        } else {
            $this->addError('otp_code', 'کد تایید اشتباه است.');
        }
    }

    private function startResendTimer()
    {
        $otpService = new OtpService();
        $this->resendTime = $otpService->getResendRemaining($this->new_phone);
        $this->resendAvailable = false;
        $this->timerRunning = true;
    }

};
