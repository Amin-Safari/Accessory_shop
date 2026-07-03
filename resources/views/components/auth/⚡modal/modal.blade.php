<div>
    <dialog class="modal" @if($open) open @endif>
        <div class="modal-box">

            <form method="dialog">
                <button type="button" wire:click="closeModal"
                        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <br>

            {{-- پیام موفقیت --}}
            @if(session()->has('message'))
                <div class="alert alert-success alert-sm mb-4">
                    {{ session('message') }}
                </div>
            @endif

            @if($step === 'phone')
                <form wire:submit="sendOtp" class="space-y-4">
                    <p class="font-bold text-2xl">شماره خود را وارد کنید</p>
                    <br>
                    <br>

                    <label class="input validator w-full input-xl">
                        <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                            <g fill="none">
                                <path d="M7.25 11.5C6.83579 11.5 6.5 11.8358 6.5 12.25C6.5 12.6642 6.83579 13 7.25 13H8.75C9.16421 13 9.5 12.6642 9.5 12.25C9.5 11.8358 9.16421 11.5 8.75 11.5H7.25Z" fill="currentColor"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6 1C4.61929 1 3.5 2.11929 3.5 3.5V12.5C3.5 13.8807 4.61929 15 6 15H10C11.3807 15 12.5 13.8807 12.5 12.5V3.5C12.5 2.11929 11.3807 1 10 1H6ZM10 2.5H9.5V3C9.5 3.27614 9.27614 3.5 9 3.5H7C6.72386 3.5 6.5 3.27614 6.5 3V2.5H6C5.44771 2.5 5 2.94772 5 3.5V12.5C5 13.0523 5.44772 13.5 6 13.5H10C10.5523 13.5 11 13.0523 11 12.5V3.5C11 2.94772 10.5523 2.5 10 2.5Z" fill="currentColor"></path>
                            </g>
                        </svg>
                        <input
                            type="tel"
                            wire:model="phone"
                            class="tabular-nums"
                            required
                            placeholder="09xxxxxxxxx"
                            pattern="[0-9]*"
                            minlength="11"
                            maxlength="11"
                            title="باید 11 رقم باشد"
                        />
                    </label>
                    @error('phone')
                    <p class="text-error text-sm">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="btn btn-primary w-full" wire:loading.attr="disabled" wire:target="sendOtp">
                        <span wire:loading.remove wire:target="sendOtp">ارسال کد</span>
                        <span wire:loading wire:target="sendOtp" class="loading loading-spinner"></span>
                    </button>
                </form>
            @endif

            @if($step === 'otp')
                <form wire:submit="verifyOtp" class="space-y-4">
                    <p class="font-bold text-xl">کد تایید را وارد کنید</p>
                    <p class="text-sm opacity-70">کد به شماره {{ $phone }} پیامک شد</p>

                    <label class="text-center">
                        <input
                            type="text"
                            wire:model="otp"
                            autocomplete="one-time-code"
                            inputmode="numeric"
                            maxlength="4"
                            pattern="[0-9]{4}"
                            required
                            class="input input-bordered w-full text-center tracking-[0.5em] text-xl"
                        />
                    </label>
                    @error('otp')
                    <p class="text-error text-sm">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="btn btn-primary w-full mt-5" wire:loading.attr="disabled" wire:target="verifyOtp">
                        <span wire:loading.remove wire:target="verifyOtp">تایید و ورود</span>
                        <span wire:loading wire:target="verifyOtp" class="loading loading-spinner"></span>
                    </button>

                    <div class="flex justify-between text-sm">
                        <button type="button" wire:click="backToPhone" class="link">تغییر شماره</button>

                        {{-- تایمر هوشمند با Alpine.js --}}
                        <div x-data="otpTimer({{ $resendAt ?? 0 }})">
                            <template x-if="seconds > 0">
                                <span class="text-gray-500" x-text="seconds + ' ثانیه'"></span>
                            </template>
                            <template x-if="seconds === 0">
                                <button type="button" wire:click="resendOtp" class="link">ارسال مجدد کد</button>
                            </template>
                        </div>
                    </div>
                </form>
            @endif

        </div>

        <form method="dialog" class="modal-backdrop">
            <button type="button" wire:click="closeModal">close</button>
        </form>
    </dialog>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('otpTimer', (targetTimestamp) => ({
            seconds: 0,
            interval: null,

            init() {
                this.updateSeconds();
                this.interval = setInterval(() => {
                    this.updateSeconds();
                }, 1000);
            },

            updateSeconds() {
                const now = Math.floor(Date.now() / 1000);
                this.seconds = Math.max(0, targetTimestamp - now);

                if (this.seconds === 0 && this.interval) {
                    clearInterval(this.interval);
                    this.interval = null;
                }
            },

            destroy() {
                if (this.interval) {
                    clearInterval(this.interval);
                }
            }
        }));
    });
</script>
