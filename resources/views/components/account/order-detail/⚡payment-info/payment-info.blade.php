<div class="card bg-base-100 shadow">
    <div class="card-body">
        <h2 class="card-title mb-4">اطلاعات تراکنش پرداخت</h2>

        @if($order->paymentTransactions)
            @php $transaction = $order->paymentTransactions->first(); @endphp

            <div class="overflow-x-auto">
                <table class="table">
                    <tbody>
                    <tr>
                        <td class="text-base-content/60">شماره تراکنش</td>
                        <td class="font-medium">{{ $transaction->transaction_id ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="text-base-content/60">شماره مرجع</td>
                        <td class="font-medium">{{ $transaction->reference_id ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td class="text-base-content/60">درگاه پرداخت</td>
                        <td class="font-medium">{{ $transaction->gateway }}</td>
                    </tr>
                    <tr>
                        <td class="text-base-content/60">مبلغ</td>
                        <td class="font-medium">{{ number_format($transaction->amount) }} {{ $transaction->currency === 'IRR' ? 'ریال' : 'تومان' }}</td>
                    </tr>
                    <tr>
                        <td class="text-base-content/60">وضعیت</td>
                        <td>
                            @php
                                $statusColor = match($transaction->status) {
                                    'completed' => 'badge-success',
                                    'pending', 'initiated' => 'badge-warning',
                                    'failed', 'cancelled', 'timed_out' => 'badge-error',
                                    default => 'badge-ghost'
                                };
                                $statusLabel = match($transaction->status) {
                                    'initiated' => 'شروع شده',
                                    'pending' => 'در انتظار',
                                    'completed' => 'موفق',
                                    'failed' => 'ناموفق',
                                    'cancelled' => 'لغو شده',
                                    'timed_out' => 'منقضی شده',
                                    default => $transaction->status
                                };
                            @endphp
                            <div class="badge {{ $statusColor }}">{{ $statusLabel }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-base-content/60">تاریخ تراکنش</td>
                        <td class="font-medium">{{ verta($transaction->created_at)->format('Y/m/d H:i') }}</td>
                    </tr>
                    @if($transaction->completed_at)
                        <tr>
                            <td class="text-base-content/60">تاریخ تکمیل</td>
                            <td class="font-medium">{{ verta($transaction->completed_at)->format('Y/m/d H:i') }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-info shrink-0 w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>تراکنش پرداختی برای این سفارش ثبت نشده است.</span>
            </div>
        @endif
    </div>
</div>
