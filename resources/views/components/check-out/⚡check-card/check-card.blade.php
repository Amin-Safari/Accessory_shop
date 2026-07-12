<div class="card bg-base-100 shadow-xl mb-8">
    <div class="card-body">
        <h2 class="card-title text-2xl mb-4">🛒 سبد خرید شما</h2>

        <!-- Cart Items -->
        <div class="space-y-4 mb-6">
            @foreach($this->items as $item)
                <div class="flex items-center gap-4 p-4 bg-base-200 rounded-lg">
                    <span class="text-4xl">🎧</span>
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg">هدفون بی‌سیم</h3>
                        <p class="text-sm opacity-70">۲,۵۰۰,۰۰۰ تومان</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="btn btn-sm btn-circle btn-ghost">➖</button>
                        <span class="w-8 text-center font-bold">۱</span>
                        <button class="btn btn-sm btn-circle btn-ghost">➕</button>
                    </div>
                    <div class="text-left font-bold text-lg min-w-[120px]">
                        ۲,۵۰۰,۰۰۰ تومان
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Order Summary -->
        <div class="divider"></div>
        <div class="space-y-2">
            <div class="flex justify-between">
                <span>جمع سبد خرید:</span>
                <span>۷,۰۵۰,۰۰۰ تومان</span>
            </div>
            <div class="flex justify-between text-success">
                <span>تخفیف:</span>
                <span>-۳۰۰,۰۰۰ تومان</span>
            </div>
            <div class="flex justify-between">
                <span>هزینه ارسال:</span>
                <span>۱۵۰,۰۰۰ تومان</span>
            </div>
            <div class="divider"></div>
            <div class="flex justify-between text-xl font-bold">
                <span>مبلغ قابل پرداخت:</span>
                <span class="text-primary">۶,۹۰۰,۰۰۰ تومان</span>
            </div>
        </div>

        <div class="card-actions justify-end mt-6">
            <button class="btn btn-primary btn-lg">
                ادامه فرآیند خرید ←
            </button>
        </div>
    </div>
</div>
