<div>
    {{-- Card --}}
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h2 class="card-title text-lg mb-1">تغییر شماره موبایل</h2>

            <div class="space-y-4">
                <div class="flex items-center gap-3 text-sm bg-base-200 rounded-lg p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/60 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <div>
                        <span class="text-base-content/60">شماره فعلی:</span>
                        <span class="font-bold mr-2">{{ auth()->user()->phone }}</span>
                    </div>
                </div>

                <p class="text-sm text-base-content/60 leading-relaxed">
                    برای تغییر شماره موبایل خود، روی دکمه زیر کلیک کنید.
                    یک کد تایید به شماره جدید ارسال خواهد شد.
                </p>

                <button wire:click="openModal" class="btn btn-secondary w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    تغییر شماره موبایل
                </button>
            </div>
        </div>
    </div>

    {{-- Phone Input Modal --}}
    <!-- مودال اول: تغییر شماره موبایل -->
    <dialog class="modal" @if($showModal) open @endif>
        <div class="modal-box">

            <!-- دکمه بستن -->
            <form method="dialog">
                <button type="button" wire:click="closeModal"
                        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <br>

            {{-- Modal Header --}}
            <div class="mb-8 text-center">
                <div class="mx-auto w-16 h-16 rounded-full bg-secondary/10 flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold">تغییر شماره موبایل</h3>
                <p class="text-sm text-base-content/60  mt-2">
                    شماره موبایل جدید خود را وارد کنید
                </p>
            </div>

            {{-- Phone Input Form --}}
            <form wire:submit="sendOtp">
                <div class="form-control mb-6">
                    <label class="label justify-center">
                        <span class="label-text font-medium text-base">شماره موبایل جدید</span>
                    </label>

                    <div class="flex justify-center">
                        <label class="input input-bordered flex items-center gap-3 w-full max-w-xs @error('new_phone') input-error @enderror">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/40 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <input type="text"
                                   wire:model="new_phone"
                                   class="grow text-center text-lg font-mono tracking-wider"
                                   placeholder="۰۹۱۲۳۴۵۶۷۸۹"
                                   maxlength="11"
                                   dir="ltr" />
                        </label>
                    </div>

                    @error('new_phone')
                    <div class="flex justify-center mt-1">
                        <span class="text-error text-sm">{{ $message }}</span>
                    </div>
                    @enderror

                    <div class="flex justify-center mt-1">
                        <span class="text-xs text-base-content/50">شماره موبایل باید ۱۱ رقم و با ۰۹ شروع شود</span>
                    </div>
                </div>
                {{-- Warning --}}
                <div class="alert alert-warning mb-6 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <span>پس از تغییر شماره، از شماره جدید برای ورود استفاده کنید.</span>
                </div>

                {{-- Modal Actions --}}
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="btn btn-primary flex-1 p-1" wire:loading.attr="disabled">
                        <span wire:loading.remove>ارسال کد تایید</span>
                        <span wire:loading class="loading loading-spinner"></span>
                    </button>
                    <button type="button" wire:click="closeModal" class="btn btn-ghost flex-1 p-1">
                        انصراف
                    </button>
                </div>
            </form>
        </div>

        <!-- Backdrop -->
        <form method="dialog" class="modal-backdrop">
            <button type="button" wire:click="closeModal">close</button>
        </form>
    </dialog>

    <!-- مودال دوم: تایید OTP -->
    <dialog class="modal" @if($showOtpModal) open @endif>
        <div class="modal-box">

            <!-- دکمه بستن -->
            <form method="dialog">
                <button type="button" wire:click="closeModal"
                        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <br>

            {{-- Modal Header --}}
            <div class="mb-8 text-center">
                <div class="mx-auto w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold ">کد تایید</h3>
                <p class="text-sm text-base-content/60 mt-2">
                    کد ۴ رقمی ارسال شده به شماره
                    <span class="font-bold text-base-content" dir="ltr">{{ $new_phone }}</span>
                    را وارد کنید
                </p>
            </div>

            {{-- OTP Form --}}
            <form wire:submit="verifyAndChangePhone">
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-medium">کد تایید</span>
                    </label>
                    <input type="text"
                           wire:model="otp_code"
                           class="input input-bordered text-center text-2xl font-bold tracking-widest @error('otp_code') input-error @enderror"
                           placeholder="----"
                           maxlength="4"
                           dir="ltr" />
                    @error('otp_code')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                {{-- Timer --}}
                <div x-data="{
                timer: {{ $resendTime }},
                resendAvailable: @entangle('resendAvailable'),
                timerRunning: @entangle('timerRunning'),
                init() {
                    if (this.timer > 0) {
                        this.startTimer();
                    } else {
                        this.resendAvailable = true;
                        this.timerRunning = false;
                    }
                },
                startTimer() {
                    this.timerRunning = true;
                    this.resendAvailable = false;
                    const interval = setInterval(() => {
                        if (this.timer > 0) {
                            this.timer--;
                        } else {
                            this.resendAvailable = true;
                            this.timerRunning = false;
                            clearInterval(interval);
                        }
                    }, 1000);
                }
            }" class="mb-6">
                    <div class="text-center">
                        <button type="button"
                                wire:click="resendOtp"
                                x-bind:disabled="!resendAvailable"
                                x-bind:class="resendAvailable ? 'link text-primary' : 'text-base-content/40 cursor-not-allowed'"
                                class="text-sm">
                            ارسال مجدد کد
                        </button>
                        <span x-show="timerRunning" class="text-sm text-base-content/60 block mt-1">
                        <span x-text="timer"></span> ثانیه دیگر
                    </span>
                    </div>
                </div>

                {{-- Modal Actions --}}
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="btn btn-primary flex-1 p-1" wire:loading.attr="disabled">
                        <span wire:loading.remove>تایید و تغییر شماره</span>
                        <span wire:loading class="loading loading-spinner"></span>
                    </button>
                    <button type="button" wire:click="closeModal" class="btn btn-ghost flex-1 p-1">
                        انصراف
                    </button>
                </div>
            </form>
        </div>

        <!-- Backdrop -->
        <form method="dialog" class="modal-backdrop">
            <button type="button" wire:click="closeModal">close</button>
        </form>
    </dialog>

    {{-- Success Toast --}}
    @if(session('phone_changed'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             class="fixed bottom-4 left-4 right-4 sm:left-auto sm:right-4 z-50">
            <div class="alert alert-success shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('phone_changed') }}</span>
                <button @click="show = false" class="btn btn-ghost btn-sm btn-circle">✕</button>
            </div>
        </div>
    @endif
</div>
