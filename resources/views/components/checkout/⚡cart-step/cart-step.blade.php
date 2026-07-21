<div class="card bg-base-100 shadow-2xl hover:shadow-3xl transition-all duration-300">
    <div class="card-body">
        <h2 class="card-title text-3xl mb-6">
            <span class="text-4xl">🛒</span>
            سبد خرید شما
        </h2>

        @if(empty($cartItems))
            <div class="text-center py-12 animate__animated animate__fadeIn">
                <span class="text-8xl mb-4 block">🛒</span>
                <h3 class="text-2xl font-bold mb-2">سبد خرید شما خالی است</h3>
                <p class="text-base-content/70 mb-6">محصولات مورد نظر خود را به سبد خرید اضافه کنید</p>
                <a href="{{ route('shop') }}" class="btn btn-primary btn-lg">
                    رفتن به فروشگاه
                </a>
            </div>
        @else
            <!-- Cart Items -->
            <div class="space-y-4 mb-6">
                @foreach($cartItems as $index => $item)
                    <div class="flex flex-col sm:flex-row items-center gap-4 p-4 bg-base-200 rounded-xl
                            hover:shadow-md transition-all duration-300 animate__animated animate__fadeIn"
                         style="animation-delay: {{ $index * 0.1 }}s">

                        <!-- Product Image -->
                        <div class="w-20 h-20 bg-base-300 rounded-lg flex items-center justify-center text-4xl">
                            {{ $item['image'] ? "<img src='{$item['image']}' class='w-full h-full object-cover rounded-lg'>" : '📦' }}
                        </div>

                        <!-- Product Details -->
                        <div class="flex-1 text-center sm:text-right">
                            <h3 class="font-semibold text-lg">{{ $item['name'] }}</h3>
                            <div class="flex items-center justify-center sm:justify-start gap-2 mt-1">
                                <span class="text-sm opacity-70">{{ number_format($item['price']) }} تومان</span>
                                @if($item['discount'] > 0)
                                    <span class="badge badge-success badge-sm">{{ $item['discount'] }}% تخفیف</span>
                                @endif
                            </div>
                            @if(!$item['is_available'])
                                <span class="badge badge-error badge-sm mt-1">ناموجود</span>
                            @elseif($item['stock'] < 5)
                                <span class="badge badge-warning badge-sm mt-1">تنها {{ $item['stock'] }} عدد در انبار</span>
                            @endif
                        </div>

                        <!-- Quantity Controls -->
                        <div class="flex items-center gap-2">
                            <button class="btn btn-sm btn-circle btn-ghost hover:btn-primary transition-colors duration-200"
                                    wire:click="updateQuantity('{{ $item['id'] }}', {{ $item['quantity'] - 1 }})">
                                ➖
                            </button>
                            <span class="w-10 text-center font-bold text-lg">{{ $item['quantity'] }}</span>
                            <button class="btn btn-sm btn-circle btn-ghost hover:btn-primary transition-colors duration-200"
                                    wire:click="updateQuantity('{{ $item['id'] }}', {{ $item['quantity'] + 1 }})"
                                    @if($item['quantity'] >= $item['stock']) disabled @endif>
                                ➕
                            </button>
                        </div>

                        <!-- Item Total -->
                        <div class="text-left font-bold text-lg min-w-[120px]">
                            {{ number_format($item['price'] * $item['quantity']) }} تومان
                        </div>

                        <!-- Remove Button -->
                        <button class="btn btn-ghost btn-circle hover:btn-error hover:text-white transition-all duration-200"
                                wire:click="removeItem('{{ $item['id'] }}')"
                                wire:confirm="آیا از حذف این محصول اطمینان دارید؟">
                            🗑️
                        </button>
                    </div>
                @endforeach
            </div>

            <!-- Order Summary -->
            <div class="divider"></div>
            <div class="bg-base-200 rounded-xl p-6 space-y-3">
                <div class="flex justify-between text-lg">
                    <span>تعداد کل اقلام:</span>
                    <span class="font-bold">{{ $this->totalItems }}</span>
                </div>
                <div class="flex justify-between text-lg">
                    <span>جمع سبد خرید:</span>
                    <span>{{ number_format($this->subtotal) }} تومان</span>
                </div>
                @if($this->discountAmount > 0)
                    <div class="flex justify-between text-success text-lg">
                        <span>تخفیف:</span>
                        <span>-{{ number_format($this->discountAmount) }} تومان</span>
                    </div>
                @endif
                <div class="divider"></div>
                <div class="flex justify-between text-2xl font-bold">
                    <span>مبلغ قابل پرداخت:</span>
                    <span class="text-primary">{{ number_format($this->subtotal - $this->discountAmount) }} تومان</span>
                </div>
            </div>

            <!-- Validation Errors -->
            @if(!empty($validationErrors))
                <div class="alert alert-error mt-4 animate__animated animate__shakeX">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="font-bold">خطا در سبد خرید</h3>
                        <ul class="list-disc list-inside">
                            @foreach($validationErrors as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="card-actions justify-end mt-6">
                <button class="btn btn-primary btn-lg group hover:scale-105 transition-all duration-300"
                        wire:click="proceedToNextStep"
                        wire:loading.attr="disabled"
                        wire:target="proceedToNextStep">
                    <span wire:loading.remove wire:target="proceedToNextStep">
                        ادامه فرآیند خرید
                        <span class="inline-block transition-transform group-hover:translate-x-1">←</span>
                    </span>
                    <span wire:loading wire:target="proceedToNextStep" class="loading loading-spinner loading-md"></span>
                </button>
            </div>
        @endif
    </div>
</div>
