<div class="card bg-base-100 shadow">
    <div class="card-body">
        <h2 class="card-title mb-4">خلاصه مبلغ</h2>

        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-base-content/60">مبلغ کل محصولات</span>
                <span class="font-medium">{{ number_format($order->price) }} تومان</span>
            </div>

            @if($order->discount_amount > 0)
                <div class="flex justify-between">
                    <span class="text-base-content/60">تخفیف</span>
                    <span class="font-medium text-error">- {{ number_format($order->discount_amount) }} تومان</span>
                </div>
            @endif

            <div class="flex justify-between">
                <span class="text-base-content/60">هزینه ارسال</span>
                <span class="font-medium">
                    @if($order->shipping_cost > 0)
                        {{ number_format($order->shipping_cost) }} تومان
                    @else
                        <span class="text-success">رایگان</span>
                    @endif
                </span>
            </div>

            @if($order->value_added > 0)
                <div class="flex justify-between">
                    <span class="text-base-content/60">ارزش افزوده</span>
                    <span class="font-medium">{{ number_format($order->value_added) }} تومان</span>
                </div>
            @endif

            <div class="divider my-2"></div>

            <div class="flex justify-between items-center">
                <span class="text-lg font-bold">مبلغ نهایی</span>
                <span class="text-lg font-bold text-primary">{{ number_format($order->final_price) }} تومان</span>
            </div>

            @if($order->status === 'awaiting_payment')
                <a href="#" class="btn btn-primary w-full mt-4">
                    پرداخت سفارش
                </a>
            @endif
        </div>
    </div>
</div>
