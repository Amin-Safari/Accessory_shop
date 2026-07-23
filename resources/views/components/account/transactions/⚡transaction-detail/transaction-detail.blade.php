<div>
    <dialog id="transaction_{{ $transaction->id }}" class="modal">
        <div class="modal-box w-11/12 max-w-2xl">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold">جزئیات تراکنش</h3>
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute left-4 top-4">
                        ✕
                    </button>
                </form>
            </div>

            {{-- Status Banner --}}
            <div
                class="alert {{ $this->getStatusColor($transaction->status) === 'badge-success' ? 'alert-success' : ($this->getStatusColor($transaction->status) === 'badge-error' ? 'alert-error' : 'alert-warning') }} mb-6">
            <span class="font-bold">
                وضعیت تراکنش: {{ $this->getStatusLabel($transaction->status) }}
            </span>
            </div>

            {{-- Transaction Details --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Transaction ID --}}
                <div class="bg-base-200 rounded-lg p-4">
                    <div class="text-sm text-base-content/60 mb-1">شماره تراکنش</div>
                    <div class="font-mono font-bold">
                        {{ $transaction->transaction_id ?? '---' }}
                    </div>
                </div>

                {{-- Reference ID --}}
                <div class="bg-base-200 rounded-lg p-4">
                    <div class="text-sm text-base-content/60 mb-1">شماره مرجع</div>
                    <div class="font-mono font-bold">
                        {{ $transaction->reference_id ?? '---' }}
                    </div>
                </div>

                {{-- Amount --}}
                <div class="bg-base-200 rounded-lg p-4">
                    <div class="text-sm text-base-content/60 mb-1">مبلغ</div>
                    <div class="font-bold text-lg">
                        {{ number_format($transaction->amount) }}
                        <span class="text-sm">{{ $transaction->currency === 'IRR' ? 'ریال' : 'تومان' }}</span>
                    </div>
                </div>

                {{-- Gateway --}}
                <div class="bg-base-200 rounded-lg p-4">
                    <div class="text-sm text-base-content/60 mb-1">درگاه پرداخت</div>
                    <div class="font-bold">{{ $transaction->gateway }}</div>
                </div>

                {{-- Created At --}}
                <div class="bg-base-200 rounded-lg p-4">
                    <div class="text-sm text-base-content/60 mb-1">تاریخ ایجاد</div>
                    <div class="font-bold">
                        {{ $this->convertToJalali($transaction->created_at) }}
                    </div>
                </div>

                {{-- Completed At --}}
                <div class="bg-base-200 rounded-lg p-4">
                    <div class="text-sm text-base-content/60 mb-1">تاریخ تکمیل</div>
                    <div class="font-bold">
                        {{ $transaction->completed_at ? $this->convertToJalali($transaction->completed_at) : '---' }}
                    </div>
                </div>

                {{-- Expires At --}}
                @if($transaction->expires_at)
                    <div class="bg-base-200 rounded-lg p-4">
                        <div class="text-sm text-base-content/60 mb-1">تاریخ انقضا</div>
                        <div class="font-bold">
                            {{ $this->convertToJalali($transaction->expires_at) }}
                        </div>
                    </div>
                @endif

                {{-- Attempt Count --}}
                @if($transaction->attempt_count > 1)
                    <div class="bg-base-200 rounded-lg p-4">
                        <div class="text-sm text-base-content/60 mb-1">تعداد تلاش</div>
                        <div class="font-bold">{{ $transaction->attempt_count }}</div>
                    </div>
                @endif
            </div>

            {{-- Order Info --}}
            @if($transaction->order)
                <div class="mt-6">
                    <h4 class="font-bold mb-3">اطلاعات سفارش مرتبط</h4>
                    <div class="bg-base-200 rounded-lg p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm text-base-content/60">کد سفارش:</span>
                                <span class="font-mono font-bold">{{ $transaction->order->order_number }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-base-content/60">مبلغ سفارش:</span>
                                <span
                                    class="font-bold">{{ number_format($transaction->order->final_price) }} تومان</span>
                            </div>
                        </div>
                        @if($transaction->order->order_number)
                            <a href="{{ route('account.orders.show', $transaction->order) }}"
                               class="btn btn-primary btn-sm mt-3">
                                مشاهده سفارش
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Error Message --}}
            @if($transaction->error_message)
                <div class="mt-6">
                    <h4 class="font-bold mb-2 text-error">پیام خطا</h4>
                    <div class="alert alert-error">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $transaction->error_message }}</span>
                    </div>
                </div>
            @endif

            {{-- Modal Actions --}}
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn">بستن</button>
                </form>
            </div>
        </div>

        {{-- Click outside to close --}}
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</div>
