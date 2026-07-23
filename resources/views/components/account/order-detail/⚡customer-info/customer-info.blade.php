<div class="card bg-base-100 shadow">
    <div class="card-body">
        <h2 class="card-title mb-4">اطلاعات گیرنده</h2>

        <div class="space-y-3">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <div>
                    <span class="text-sm text-base-content/60 block">نام و نام خانوادگی</span>
                    <span class="font-medium">{{ $order->name }}</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <div>
                    <span class="text-sm text-base-content/60 block">شماره تماس</span>
                    <span class="font-medium">{{ $order->phone }}</span>
                </div>
            </div>

            <div class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/40 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <div>
                    <span class="text-sm text-base-content/60 block">آدرس</span>
                    <span class="font-medium">{{ $order->province }} - {{ $order->city }}</span>
                    <p class="text-sm mt-1">{{ $order->address }}</p>
                    @if($order->postal_code)
                        <p class="text-sm mt-1">
                            <span class="text-base-content/60">کد پستی: </span>
                            {{ $order->postal_code }}
                        </p>
                    @endif
                </div>
            </div>

            @if($order->shipping_method)
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                    <div>
                        <span class="text-sm text-base-content/60 block">روش ارسال</span>
                        <span class="font-medium">{{ $order->shipping_method }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
