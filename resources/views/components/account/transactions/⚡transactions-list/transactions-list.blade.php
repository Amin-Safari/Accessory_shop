<div>
    {{-- Filters --}}
    <div class="card bg-base-100 shadow mb-6">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Status Filter --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">وضعیت تراکنش</span>
                    </label>
                    <select wire:model.live="status" class="select select-bordered w-full">
                        <option value="all">همه تراکنش‌ها</option>
                        <option value="completed">موفق</option>
                        <option value="pending">در انتظار</option>
                        <option value="initiated">شروع شده</option>
                        <option value="failed">ناموفق</option>
                        <option value="cancelled">لغو شده</option>
                        <option value="timed_out">منقضی شده</option>
                    </select>
                </div>

                {{-- Date From --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">از تاریخ</span>
                    </label>
                    <input type="date"
                           wire:model.live="dateFrom"
                           class="input input-bordered w-full" />
                </div>

                {{-- Date To --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">تا تاریخ</span>
                    </label>
                    <input type="date"
                           wire:model.live="dateTo"
                           class="input input-bordered w-full" />
                </div>
            </div>

            @if($status !== 'all' || $dateFrom || $dateTo)
                <div class="mt-4 flex justify-end">
                    <button wire:click="$set('status', 'all'); $set('dateFrom', ''); $set('dateTo', '');"
                            class="btn btn-ghost btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        حذف فیلترها
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Transactions List --}}
    @if($transactions->isEmpty())
        <div class="card bg-base-100 shadow">
            <div class="card-body text-center py-12">
                <div class="text-6xl mb-4">💳</div>
                <h3 class="text-xl font-bold mb-2">تراکنشی یافت نشد</h3>
                <p class="text-base-content/60">
                    @if($status !== 'all' || $dateFrom || $dateTo)
                        هیچ تراکنشی با فیلترهای انتخاب شده یافت نشد.
                    @else
                        شما هنوز هیچ تراکنشی ندارید.
                    @endif
                </p>
            </div>
        </div>
    @else
        {{-- Desktop Table --}}
        <div class="hidden lg:block">
            <div class="card bg-base-100 shadow">
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>شماره تراکنش</th>
                                <th>شماره مرجع</th>
                                <th>مبلغ</th>
                                <th>درگاه</th>
                                <th>وضعیت</th>
                                <th>تاریخ</th>
                                <th>ساعت</th>
                                <th>جزئیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transactions as $transaction)
                                <tr class="hover">
                                    <td>
                                            <span class="font-mono text-sm">
                                                {{ $transaction->transaction_id ?? '---' }}
                                            </span>
                                    </td>
                                    <td>
                                            <span class="font-mono text-sm">
                                                {{ $transaction->reference_id ?? '---' }}
                                            </span>
                                    </td>
                                    <td>
                                            <span class="font-bold">
                                                {{ number_format($transaction->amount) }}
                                            </span>
                                        <span class="text-xs text-base-content/60 block">
                                                {{ $transaction->currency === 'IRR' ? 'ریال' : 'تومان' }}
                                            </span>
                                    </td>
                                    <td>
                                        <div class="badge badge-ghost">
                                            {{ $transaction->gateway }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="badge {{ $this->getStatusColor($transaction->status) }} gap-1">
                                            {{ $this->getStatusIcon($transaction->status) }}
                                            {{ $this->getStatusLabel($transaction->status) }}
                                        </div>
                                    </td>
                                    <td>
                                        {{ $this->convertToJalaliDate($transaction->created_at) }}
                                    </td>
                                    <td>
                                        {{ $this->convertToJalaliTime($transaction->created_at) }}
                                    </td>
                                    <td>
                                        <button onclick="transaction_{{ $transaction->id }}.showModal()"
                                                class="btn btn-ghost btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile Cards --}}
        <div class="lg:hidden space-y-4">
            @foreach($transactions as $transaction)
                <div class="card bg-base-100 shadow"
                     onclick="transaction_{{ $transaction->id }}.showModal()">
                    <div class="card-body">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <div class="badge {{ $this->getStatusColor($transaction->status) }} gap-1 mb-2">
                                    {{ $this->getStatusIcon($transaction->status) }}
                                    {{ $this->getStatusLabel($transaction->status) }}
                                </div>
                            </div>
                            <span class="font-bold text-lg">
                                {{ number_format($transaction->amount) }}
                                <span class="text-xs text-base-content/60">تومان</span>
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-base-content/60 block">شماره تراکنش:</span>
                                <span class="font-mono">{{ $transaction->transaction_id ?? '---' }}</span>
                            </div>
                            <div>
                                <span class="text-base-content/60 block">شماره مرجع:</span>
                                <span class="font-mono">{{ $transaction->reference_id ?? '---' }}</span>
                            </div>
                            <div>
                                <span class="text-base-content/60 block">درگاه:</span>
                                <span>{{ $transaction->gateway }}</span>
                            </div>
                            <div>
                                <span class="text-base-content/60 block">تاریخ:</span>
                                <span>{{ $this->convertToJalaliDate($transaction->created_at) }}</span>
                            </div>
                        </div>

                        <div class="text-xs text-base-content/40 mt-2">
                            {{ $this->convertToJalaliTime($transaction->created_at) }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Transaction Detail Modals --}}
        @foreach($transactions as $transaction)
            <livewire:account.transactions.transaction-detail
                :transaction="$transaction"
                :key="'modal-'.$transaction->id" />
        @endforeach

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    @endif
</div>
