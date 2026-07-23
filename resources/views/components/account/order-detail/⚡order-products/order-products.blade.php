<div class="card bg-base-100 shadow">
    <div class="card-body">
        <h2 class="card-title mb-4">محصولات سفارش</h2>

        <div class="divide-y divide-base-300">
            @foreach($order->items as $item)
                <div class="flex gap-4 py-4 first:pt-0 last:pb-0">
                    {{-- Product Image --}}
                    <div class="avatar">
                        <div class="w-20 h-20 rounded-lg">
                            @if($item->product && isset($item->product->images[0]))
                                <img src="{{ $item->product->images[0] }}"
                                     alt="{{ $item->product->name }}"
                                     class="object-cover" />
                            @else
                                <div class="bg-base-300 w-full h-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Product Details --}}
                    <div class="flex-1">
                        <h3 class="font-bold text-lg">{{ $item->product->name ?? 'محصول حذف شده' }}</h3>

                        <div class="flex flex-wrap gap-4 mt-2 text-sm">
                            <div>
                                <span class="text-base-content/60">تعداد:</span>
                                <span class="font-medium">{{ $item->quantity }}</span>
                            </div>
                            <div>
                                <span class="text-base-content/60">قیمت واحد:</span>
                                <span class="font-medium">{{ number_format($item->unit_price) }} تومان</span>
                            </div>
                            @if($item->discount > 0)
                                <div>
                                    <span class="text-base-content/60">تخفیف:</span>
                                    <span class="font-medium text-error">{{ $item->discount }}%</span>
                                </div>
                            @endif
                            <div>
                                <span class="text-base-content/60">قیمت کل:</span>
                                <span class="font-medium text-primary">{{ number_format($item->total_price) }} تومان</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
