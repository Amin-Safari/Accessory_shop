<div>
    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold">جزئیات سفارش</h1>
            <p class="text-base-content/60 mt-1">کد سفارش: {{ $order->order_number }}</p>
        </div>
        <a href="{{ route('account.orders') }}" class="btn btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            بازگشت به لیست سفارش‌ها
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content - Order Info & Products --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Order Status Timeline --}}
            <livewire:account.order-detail.order-status :order="$order" />

            {{-- Products List --}}
            <livewire:account.order-detail.order-products :order="$order" />

            {{-- Payment Transaction Info --}}
            <livewire:account.order-detail.payment-info :order="$order" />
        </div>

        {{-- Sidebar - Customer Info & Price Summary --}}
        <div class="space-y-6">
            {{-- Customer Information --}}
            <livewire:account.order-detail.customer-info :order="$order" />

            {{-- Price Summary --}}
            <livewire:account.order-detail.price-summary :order="$order" />
        </div>
    </div>
</div>
