<div class="card bg-base-100 shadow-2xl">
    <div class="card-body text-center">

        @if($status === 'success')
            <!-- Success Result -->
            <div class="animate__animated animate__fadeInUp">
                <div class="mb-6">
                    <div class="w-28 h-28 bg-success/20 rounded-full flex items-center justify-center mx-auto mb-4
                                animate__animated animate__bounceIn">
                        <span class="text-7xl">✅</span>
                    </div>
                    <h2 class="card-title text-4xl font-bold text-success justify-center mb-2">
                        پرداخت موفقیت‌آمیز بود!
                    </h2>
                    <p class="text-lg opacity-70">
                        سفارش شما با موفقیت ثبت شد و به زودی پردازش خواهد شد
                    </p>
                </div>

                <!-- Order Details -->
                <div class="bg-base-200 rounded-xl p-6 mb-6 text-right max-w-lg mx-auto">
                    <h3 class="font-bold text-2xl mb-4 text-center">📋 جزئیات سفارش</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between border-b border-base-300 pb-2">
                            <span class="opacity-70">شماره سفارش:</span>
                            <span class="font-bold font-mono text-lg" dir="ltr">{{ $order->order_number }}</span>
                        </div>

                        <div class="flex justify-between border-b border-base-300 pb-2">
                            <span class="opacity-70">کد رهگیری:</span>
                            <span class="font-bold font-mono text-lg text-success" dir="ltr">{{ $order->tracking_code }}</span>
                        </div>

                        <div class="flex justify-between border-b border-base-300 pb-2">
                            <span class="opacity-70">کد پیگیری پرداخت:</span>
                            <span class="font-bold font-mono" dir="ltr">{{ $order->reference_id }}</span>
                        </div>

                        <div class="flex justify-between border-b border-base-300 pb-2">
                            <span class="opacity-70">تاریخ:</span>
                            <span>{{ $order->created_at->format('Y/m/d - H:i') }}</span>
                        </div>

                        <div class="flex justify-between border-b border-base-300 pb-2">
                            <span class="opacity-70">روش ارسال:</span>
                            <span>{{ $order->shipping_method }}</span>
                        </div>

                        <div class="flex justify-between pt-2">
                            <span class="font-bold text-xl">مبلغ پرداخت شده:</span>
                            <span class="font-bold text-success text-xl">{{ number_format($order->final_price) }} تومان</span>
                        </div>
                    </div>
                </div>

                <!-- Delivery Info -->
                <div class="bg-info/10 rounded-xl p-4 mb-6 text-right max-w-lg mx-auto">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-3xl">🚚</span>
                        <span class="font-bold text-lg">اطلاعات ارسال</span>
                    </div>
                    <p class="text-sm opacity-70">
                        سفارش شما به آدرس <strong>{{ $order->address }}</strong> ارسال خواهد شد.
                        کد رهگیری پس از ارسال از طریق پیامک به شماره {{ $order->phone }} اطلاع‌رسانی می‌شود.
                    </p>
                </div>

                <div class="card-actions justify-center gap-4">
                    <button class="btn btn-primary btn-lg shadow-lg hover:shadow-primary/50 transition-all duration-300"
                            wire:click="goToOrders">
                        پیگیری سفارش
                    </button>
                    <button class="btn btn-ghost btn-lg"
                            wire:click="goToShop">
                        بازگشت به فروشگاه
                    </button>
                </div>
            </div>

        @elseif($status === 'failed')
            <!-- Failure Result -->
            <div class="animate__animated animate__fadeInUp">
                <div class="mb-6">
                    <div class="w-28 h-28 bg-error/20 rounded-full flex items-center justify-center mx-auto mb-4
                                animate__animated animate__bounceIn">
                        <span class="text-7xl">❌</span>
                    </div>
                    <h2 class="card-title text-4xl font-bold text-error justify-center mb-2">
                        پرداخت ناموفق بود
                    </h2>
                    <p class="text-lg opacity-70">
                        متأسفانه پرداخت شما با خطا مواجه شد. لطفاً دوباره تلاش کنید
                    </p>
                </div>

                <!-- Error Details -->
                @if($errorMessage)
                    <div class="bg-error/10 rounded-xl p-4 mb-6 text-right max-w-lg mx-auto">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-2xl">⚠️</span>
                            <span class="font-bold">علت خطا</span>
                        </div>
                        <p class="text-sm opacity-70">
                            {{ $errorMessage }}
                        </p>
                    </div>
                @endif

                <!-- Suggestions -->
                <div class="bg-base-200 rounded-xl p-4 mb-6 text-right max-w-lg mx-auto">
                    <h4 class="font-bold mb-2">💡 راه‌حل‌های پیشنهادی:</h4>
                    <ul class="list-disc list-inside space-y-1 text-sm opacity-70">
                        <li>موجودی حساب خود را بررسی کنید</li>
                        <li>از کارت بانکی دیگری استفاده کنید</li>
                        <li>اطلاعات کارت را مجدداً بررسی کنید</li>
                        <li>در صورت تکرار مشکل، با پشتیبانی تماس بگیرید</li>
                    </ul>
                </div>

                <div class="card-actions justify-center gap-4">
                    <button class="btn btn-warning btn-lg shadow-lg hover:shadow-warning/50 transition-all duration-300"
                            wire:click="retryPayment">
                        تلاش مجدد
                    </button>
                    <button class="btn btn-ghost btn-lg"
                            wire:click="goToShop">
                        بازگشت به فروشگاه
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
