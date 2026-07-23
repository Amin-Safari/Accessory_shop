<div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">سفارش‌های در جریان</h2>
        @if(count($orders) > 0)
            <a href="{{ route('account.orders') }}" class="btn btn-ghost btn-sm">
                مشاهده همه
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
        @endif
    </div>

    @if(count($orders) === 0)
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <div class="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-info shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>شما سفارش در جریانی ندارید.</span>
                </div>
            </div>
        </div>
    @else
        <div class="grid gap-4">
            @foreach($orders as $order)
                <div class="card bg-base-100 shadow hover:shadow-lg transition-shadow">
                    <div class="card-body">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            {{-- Order Info --}}
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="text-sm text-base-content/60">کد سفارش:</span>
                                    <span class="font-bold text-lg">{{ $order->order_number }}</span>

                                    {{-- Status Badge --}}
                                    @php
                                        $statusColors = [
                                            'pending' => 'badge-warning',
                                            'awaiting_payment' => 'badge-warning',
                                            'processing' => 'badge-info',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'در انتظار',
                                            'awaiting_payment' => 'در انتظار پرداخت',
                                            'processing' => 'در حال پردازش',
                                        ];
                                    @endphp
                                    <div class="badge {{ $statusColors[$order->status] }} gap-1">
                                        {{ $statusLabels[$order->status] }}
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-4 text-sm text-base-content/70">
                                            <span>
                                                📅 {{ verta($order->created_at)->format('Y/m/d') }}
                                            </span>
                                    <span>
                                                💰 {{ number_format($order->final_price) }} تومان
                                            </span>
                                    <span>
                                                📦 {{ $order->items->count() }} محصول
                                            </span>
                                </div>
                            </div>

                            {{-- Action Button --}}
                            <div class="flex gap-2">
                                <a href="{{ route('account.orders.show', $order) }}"
                                   class="btn btn-primary btn-sm">
                                    مشاهده جزئیات
                                </a>
                            </div>
                        </div>

                        {{-- Products Preview --}}
                        @if($order->items->count() > 0)
                            <div class="mt-4 pt-4 border-t border-base-300">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($order->items->take(3) as $item)
                                        <div class="avatar">
                                            <div class="w-12 h-12 rounded-lg">
                                                @if($item->product && isset($item->product->images[0]))
                                                    <img src="{{ $item->product->images[0] }}" alt="{{ $item->product->name }}" class="object-cover" />
                                                @else
                                                    <div class="bg-base-300 w-full h-full flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($order->items->count() > 3)
                                        <div class="avatar placeholder">
                                            <div class="w-12 h-12 rounded-lg bg-base-300 text-base-content/60">
                                                <span>+{{ $order->items->count() - 3 }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
