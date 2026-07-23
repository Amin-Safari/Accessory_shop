<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    {{-- Total Orders --}}
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-base-content/70 text-sm font-medium">کل سفارش‌ها</h3>
                    <p class="text-3xl font-bold mt-2">{{ $totalOrders }}</p>
                </div>
                <div class="p-3 rounded-lg bg-primary/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <div class="text-xs text-base-content/60 mt-2">
                تعداد کل سفارش‌های ثبت شده
            </div>
        </div>
    </div>

    {{-- Paid Orders --}}
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-base-content/70 text-sm font-medium">پرداخت موفق</h3>
                    <p class="text-3xl font-bold mt-2 text-success">{{ $paidOrders }}</p>
                </div>
                <div class="p-3 rounded-lg bg-success/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-xs text-base-content/60 mt-2">
                سفارش‌های با پرداخت موفق
            </div>
        </div>
    </div>

    {{-- Pending Orders --}}
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-base-content/70 text-sm font-medium">در انتظار</h3>
                    <p class="text-3xl font-bold mt-2 text-warning">{{ $pendingOrders }}</p>
                </div>
                <div class="p-3 rounded-lg bg-warning/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-xs text-base-content/60 mt-2">
                سفارش‌های در انتظار پرداخت
            </div>
        </div>
    </div>

    {{-- Cancelled Orders --}}
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-base-content/70 text-sm font-medium">لغو شده</h3>
                    <p class="text-3xl font-bold mt-2 text-error">{{ $cancelledOrders }}</p>
                </div>
                <div class="p-3 rounded-lg bg-error/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-xs text-base-content/60 mt-2">
                سفارش‌های لغو شده
            </div>
        </div>
    </div>
</div>
