{{-- resources/views/livewire/account/orders/orders-list.blade.php --}}
<div>
    {{-- Filter Tabs --}}
    <div class="tabs tabs-boxed mb-6 overflow-x-auto flex-nowrap pb-1 scrollbar-hide">
        <button wire:click="$set('status', 'all')"
                class="tab whitespace-nowrap {{ $status === 'all' ? 'tab-active' : '' }}">
            همه سفارش‌ها
        </button>
        <button wire:click="$set('status', 'processing')"
                class="tab whitespace-nowrap {{ $status === 'processing' ? 'tab-active' : '' }}">
            در حال پردازش
        </button>
        <button wire:click="$set('status', 'shipped')"
                class="tab whitespace-nowrap {{ $status === 'shipped' ? 'tab-active' : '' }}">
            ارسال شده
        </button>
        <button wire:click="$set('status', 'delivered')"
                class="tab whitespace-nowrap {{ $status === 'delivered' ? 'tab-active' : '' }}">
            تحویل شده
        </button>
        <button wire:click="$set('status', 'cancelled')"
                class="tab whitespace-nowrap {{ $status === 'cancelled' ? 'tab-active' : '' }}">
            لغو شده
        </button>
    </div>

    {{-- Search --}}
    <div class="mb-4">
        <div class="join w-full">
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="جستجو با کد سفارش یا کد پیگیری..."
                   class="input input-bordered join-item w-full" />
            <button class="btn btn-primary join-item">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </div>
    </div>

    @if($orders->isEmpty())
        <!-- Empty State بدون تغییر -->
        <div class="card bg-base-100 shadow">
            <div class="card-body text-center py-12">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="text-xl font-bold mb-2">سفارشی یافت نشد</h3>
                <p class="text-base-content/60">
                    @if($status !== 'all')
                        شما سفارشی با وضعیت "{{ $this->getStatusLabel($status) }}" ندارید.
                    @else
                        شما هنوز هیچ سفارشی ثبت نکرده‌اید.
                    @endif
                </p>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="card bg-base-100 shadow hover:shadow-lg transition-shadow overflow-hidden">
                    <div class="card-body p-4 sm:p-5 lg:p-6">

                        <!-- Top Row -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span class="text-sm text-base-content/60">کد سفارش:</span>
                                    <span class="font-bold">{{ $order->order_number }}</span>
                                </div>

                                <div class="badge {{ $this->getStatusColor($order->status) }} gap-1 text-xs shrink-0">
                                    {{ $this->getStatusLabel($order->status) }}
                                </div>
                            </div>

                            @if($order->tracking_code)
                                <div class="badge badge-ghost gap-1 text-xs whitespace-nowrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                    {{ $order->tracking_code }}
                                </div>
                            @endif
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4 text-sm">
                            <div class="bg-base-200 rounded-lg p-3">
                                <span class="text-base-content/60 block text-xs">تاریخ ثبت</span>
                                <span class="font-medium">{{ verta($order->created_at)->format('Y/m/d') }}</span>
                            </div>
                            <div class="bg-base-200 rounded-lg p-3">
                                <span class="text-base-content/60 block text-xs">مبلغ نهایی</span>
                                <span class="font-medium text-primary">{{ number_format($order->final_price) }} ت</span>
                            </div>
                            <div class="bg-base-200 rounded-lg p-3">
                                <span class="text-base-content/60 block text-xs">تعداد محصولات</span>
                                <span class="font-medium">{{ $order->items->count() }} محصول</span>
                            </div>
                            <div class="bg-base-200 rounded-lg p-3">
                                <span class="text-base-content/60 block text-xs">روش ارسال</span>
                                <span class="font-medium truncate">{{ $order->shipping_method ?? '---' }}</span>
                            </div>
                        </div>

                        <!-- Products Preview -->
                        @if($order->items->count() > 0)
                            <div class="border-t border-base-300 pt-4 mb-4">
                                <div class="flex flex-wrap gap-3">
                                    @foreach($order->items->take(4) as $item)
                                        <div class="tooltip" data-tip="{{ $item->product->name ?? 'محصول' }}">
                                            <div class="avatar">
                                                <div class="w-12 h-12 rounded-xl overflow-hidden">
                                                    @if($item->product && isset($item->product->images[0]))
                                                        <img src="{{ $item->product->images[0] }}"
                                                             alt="" class="object-cover w-full h-full" />
                                                    @else
                                                        <div class="bg-base-300 w-full h-full flex items-center justify-center">
                                                            <span class="text-2xl">📦</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($order->items->count() > 4)
                                        <div class="avatar placeholder">
                                            <div class="w-12 h-12 rounded-xl bg-base-300 text-base-content/60 flex items-center justify-center text-sm font-medium">
                                                +{{ $order->items->count() - 4 }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Action Button -->
                        <div class="card-actions">
                            <a href="{{ route('account.orders.show', $order) }}"
                               class="btn btn-primary btn-block sm:w-auto sm:btn-md">
                                مشاهده جزئیات
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
