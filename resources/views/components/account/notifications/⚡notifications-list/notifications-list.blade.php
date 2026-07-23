<div>
    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold">اعلان‌ها</h1>
            <p class="text-base-content/60 mt-1">
                @if($unreadCount > 0)
                    <span class="font-bold text-primary">{{ $unreadCount }}</span> اعلان خوانده نشده
                @else
                    همه اعلان‌ها را خوانده‌اید
                @endif
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead"
                        class="btn btn-primary btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    خواندن همه
                </button>
            @endif

            <button wire:click="deleteAllRead"
                    wire:confirm="آیا از حذف همه اعلان‌های خوانده شده اطمینان دارید؟"
                    class="btn btn-ghost btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                حذف خوانده‌شده‌ها
            </button>
        </div>
    </div>

    {{-- Success Messages --}}
    @if(session('all_read'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="alert alert-success mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('all_read') }}</span>
            <button @click="show = false" class="btn btn-ghost btn-sm btn-circle">✕</button>
        </div>
    @endif

    @if(session('all_read_deleted'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="alert alert-info mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('all_read_deleted') }}</span>
            <button @click="show = false" class="btn btn-ghost btn-sm btn-circle">✕</button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        {{-- Read Status Filter --}}
        <div class="tabs tabs-boxed">
            <button wire:click="$set('filter', 'all')"
                    class="tab {{ $filter === 'all' ? 'tab-active' : '' }}">
                همه
            </button>
            <button wire:click="$set('filter', 'unread')"
                    class="tab {{ $filter === 'unread' ? 'tab-active' : '' }}">
                <span class="flex items-center gap-2">
                    خوانده نشده
                    @if($unreadCount > 0)
                        <span class="badge badge-primary badge-sm">{{ $unreadCount }}</span>
                    @endif
                </span>
            </button>
            <button wire:click="$set('filter', 'read')"
                    class="tab {{ $filter === 'read' ? 'tab-active' : '' }}">
                خوانده شده
            </button>
        </div>

        {{-- Type Filter --}}
        <select wire:model.live="type" class="select select-bordered select-sm">
            <option value="all">همه نوع‌ها</option>
            <option value="order_status">📦 وضعیت سفارش</option>
            <option value="shipment">🚚 ارسال و تحویل</option>
            <option value="payment">💳 پرداخت</option>
            <option value="system">⚙️ سیستمی</option>
            <option value="promotion">🎉 تخفیف و پیشنهاد</option>
        </select>
    </div>

    {{-- Notifications List --}}
    @if($notifications->isEmpty())
        <div class="card bg-base-100 shadow">
            <div class="card-body text-center py-12">
                <div class="text-6xl mb-4">🔔</div>
                <h3 class="text-xl font-bold mb-2">
                    @if($filter === 'unread')
                        اعلان خوانده نشده‌ای ندارید
                    @elseif($filter === 'read')
                        اعلان خوانده شده‌ای ندارید
                    @elseif($type !== 'all')
                        اعلانی از این نوع ندارید
                    @else
                        اعلانی وجود ندارد
                    @endif
                </h3>
                <p class="text-base-content/60">
                    @if($filter === 'all' && $type === 'all')
                        در صورت ایجاد رویداد جدید، اعلان‌ها در این بخش نمایش داده می‌شوند.
                    @endif
                </p>
            </div>
        </div>
    @else
        <div class="space-y-3">
            @foreach($notifications as $notification)
                <div class="card bg-base-100 shadow hover:shadow-lg transition-shadow border-r-4 {{ $this->getNotificationColor($notification->type) }} {{ !$notification->is_read ? 'ring-1 ring-primary/20' : '' }}"
                     id="notification-{{ $notification->id }}">
                    <div class="card-body">
                        <div class="flex items-start gap-4">
                            {{-- Notification Icon --}}
                            <div class="text-3xl shrink-0">
                                {{ $notification->icon ?? $this->getNotificationIcon($notification->type) }}
                            </div>

                            {{-- Notification Content --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="font-bold text-lg {{ !$notification->is_read ? 'text-primary' : '' }}">
                                                {{ $notification->title }}
                                            </h3>

                                            @if(!$notification->is_read)
                                                <span class="badge badge-primary badge-sm animate-pulse">جدید</span>
                                            @endif

                                            <span class="badge badge-ghost badge-sm">
                                                {{ $notification->type_label }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="text-xs text-base-content/50 whitespace-nowrap">
                                        {{ $this->getTimeAgo($notification->created_at) }}
                                    </div>
                                </div>

                                <p class="text-base-content/80 mt-2">
                                    {{ $notification->message }}
                                </p>

                                {{-- Additional Data --}}
                                @if($notification->data)
                                    <div class="mt-3">
                                        @if(isset($notification->data['order_number']))
                                            <div class="flex flex-wrap gap-2 text-sm">
                                                <div class="badge badge-outline">
                                                    کد سفارش: {{ $notification->data['order_number'] }}
                                                </div>
                                                @if(isset($notification->data['tracking_code']))
                                                    <div class="badge badge-outline">
                                                        کد پیگیری: {{ $notification->data['tracking_code'] }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        @if(isset($notification->data['order_id']))
                                            <div class="mt-2">
                                                <a href="{{ route('account.orders.show', $notification->data['order_id']) }}"
                                                   class="btn btn-primary btn-sm">
                                                    مشاهده سفارش
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                {{-- Actions --}}
                                <div class="flex gap-2 mt-4">
                                    @if(!$notification->is_read)
                                        <button wire:click="markAsRead({{ $notification->id }})"
                                                class="btn btn-ghost btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            خواندم
                                        </button>
                                    @endif

                                    <button wire:click="deleteNotification({{ $notification->id }})"
                                            class="btn btn-ghost btn-sm text-error">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        حذف
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
