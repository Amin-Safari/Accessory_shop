<div class="card bg-base-100 shadow-2xl">
    <div class="card-body">
        <h2 class="card-title text-3xl mb-6">
            <span class="text-4xl">🚚</span>
            روش ارسال و پرداخت
        </h2>

        <!-- Shipping Methods -->
        <div class="mb-8">
            <h3 class="text-xl font-bold mb-4">انتخاب روش ارسال</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($shippingMethods as $method)
                    <div class="relative cursor-pointer group"
                         wire:click="selectShipping({{ $method['id'] }})">
                        <input type="radio"
                               wire:model="selectedShipping"
                               value="{{ $method['id'] }}"
                               class="hidden" />

                        <div class="card border-2 transition-all duration-300 hover:shadow-lg
                              {{ $selectedShipping == $method['id'] ? 'border-primary bg-primary/5 shadow-lg scale-105' : 'border-base-300 hover:border-primary/50' }}">
                            <div class="card-body text-center p-6">
                                <div class="text-4xl mb-3">
                                    @if($method['name'] === 'پیک')
                                        📮
                                    @elseif($method['name'] === 'تیپاکس')
                                        🚛
                                    @else
                                        🛵
                                    @endif
                                </div>

                                <h4 class="font-bold text-lg">{{ $method['name'] }}</h4>
                                <p class="text-sm opacity-70">{{ $method['description'] }}</p>
                                <p class="font-bold text-primary mt-2">{{ number_format($method['price']) }} تومان</p>

                                @if($selectedShipping == $method['id'])
                                    <div class="badge badge-primary mt-2">انتخاب شده</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="divider"></div>

        <!-- Payment Gateway -->
        <div class="mb-8">
            <h3 class="text-xl font-bold mb-4">درگاه پرداخت</h3>

            <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                <div class="card border-2 border-primary bg-primary/5 shadow-lg">
                    <div class="card-body text-center p-6">
                        <div class="flex items-center justify-center gap-4">
                            <div class="text-4xl">🏦</div>
                            <div>
                                <h4 class="font-bold text-xl">درگاه زیبال</h4>
                                <p class="text-sm opacity-70">پرداخت امن با تمام کارت‌های بانکی</p>
                            </div>
                            <div class="badge badge-success">فعال</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Final Price -->
        <div class="bg-gradient-to-r from-base-200 to-base-300 rounded-xl p-6 mb-6">
            <div class="space-y-3">
                <div class="flex justify-between text-lg">
                    <span>جمع سبد خرید:</span>
                    <span>{{ number_format($subtotal) }} تومان</span>
                </div>

                @if($discountAmount > 0)
                    <div class="flex justify-between text-success text-lg">
                        <span>تخفیف:</span>
                        <span>-{{ number_format($discountAmount) }} تومان</span>
                    </div>
                @endif

                <div class="flex justify-between text-lg">
                    <span>هزینه ارسال:</span>
                    <span>{{ number_format($shippingCost) }} تومان</span>
                </div>

                <div class="divider"></div>

                <div class="flex justify-between text-2xl font-bold animate__animated animate__pulse animate__infinite">
                    <span>مبلغ قابل پرداخت:</span>
                    <span class="text-primary">{{ number_format($totalAmount) }} تومان</span>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        @if($errorMessage)
            <div class="alert alert-error mb-4 animate__animated animate__shakeX">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{!! $errorMessage !!}</span>
            </div>
        @endif

        <!-- Actions -->
        <div class="card-actions justify-between mt-6">
            <button class="btn btn-ghost btn-lg group"
                    wire:click="$dispatch('previousStep')"
                    :disabled="$wire.isProcessing">
                <span class="inline-block transition-transform group-hover:-translate-x-1">→</span>
                بازگشت به اطلاعات مشتری
            </button>

            <button class="btn btn-success btn-lg group hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-success/50"
                    wire:click="proceedToPayment"
                    wire:loading.attr="disabled"
                    wire:target="proceedToPayment">
                <span wire:loading.remove wire:target="proceedToPayment">
                    پرداخت {{ number_format($totalAmount) }} تومان
                    <span class="inline-block transition-transform group-hover:translate-x-1">💳</span>
                </span>
                <span wire:loading wire:target="proceedToPayment">
                    <span class="loading loading-spinner loading-md"></span>
                    در حال اتصال به درگاه پرداخت...
                </span>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', function () {
        Livewire.on('redirectToUrl', (event) => {
            window.location.href = event.url;
        });
    });
</script>
