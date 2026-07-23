<div class="card bg-base-100 shadow">
    <div class="card-body">
        <h2 class="card-title mb-4">وضعیت سفارش</h2>

        @if($isCancelled)
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>این سفارش در وضعیت "{{ $order->status_label }}" می‌باشد.</span>
            </div>
        @else
            <ul class="steps steps-vertical lg:steps-horizontal w-full">
                <li class="step step-primary">ثبت سفارش</li>
                <li class="step {{ $currentStep >= 1 ? 'step-primary' : '' }}">پرداخت</li>
                <li class="step {{ $currentStep >= 2 ? 'step-primary' : '' }}">تایید پرداخت</li>
                <li class="step {{ $currentStep >= 3 ? 'step-primary' : '' }}">پردازش</li>
                <li class="step {{ $currentStep >= 4 ? 'step-primary' : '' }}">ارسال</li>
                <li class="step {{ $currentStep >= 5 ? 'step-primary' : '' }}">تحویل</li>
            </ul>

            {{-- Status Details --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                @if($order->paid_at)
                    <div class="flex items-center gap-2 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-base-content/60">تاریخ پرداخت:</span>
                        <span>{{ verta($order->paid_at)->format('Y/m/d H:i') }}</span>
                    </div>
                @endif

                @if($order->shipped_at)
                    <div class="flex items-center gap-2 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-info" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        <span class="text-base-content/60">تاریخ ارسال:</span>
                        <span>{{ verta($order->shipped_at)->format('Y/m/d H:i') }}</span>
                    </div>
                @endif

                @if($order->shipping_tracking_code)
                    <div class="flex items-center gap-2 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span class="text-base-content/60">کد رهگیری:</span>
                        <span class="font-bold">{{ $order->shipping_tracking_code }}</span>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
