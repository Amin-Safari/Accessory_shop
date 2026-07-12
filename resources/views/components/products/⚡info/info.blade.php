<div class="space-y-6">
    <!-- نام محصول -->
    <h1 class="text-2xl lg:text-3xl font-bold text-base-content">
        {{ $product->name }}
    </h1>

    <!-- دسته‌بندی -->
    @if($product->category)
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <span>دسته‌بندی:</span>
            <a href="/products?category={{ $product->category_id }}" class="text-primary hover:underline">
                {{ $product->category->name }}
            </a>
        </div>
    @endif

    <!-- قیمت -->
    <div class="bg-base-200 rounded-2xl p-6 space-y-3">
        @if($product->discount > 0)
            <div class="flex items-center gap-3">
                <span class="text-3xl font-bold text-error">
                    {{ number_format($product->final_price) }}
                </span>
                <span class="text-sm text-gray-500">تومان</span>
                <div class="badge badge-error gap-1 text-white ml-2">
                    {{ $product->discount }}%
                </div>
            </div>
            <div class="text-lg text-gray-500 line-through">
                {{ number_format($product->price) }} تومان
            </div>
            <div class="text-sm text-success font-medium">
                {{ number_format($product->price - $product->final_price) }} تومان سود شما
            </div>
        @else
            <div class="flex items-center gap-2">
                <span class="text-3xl font-bold text-base-content">
                    {{ number_format($product->price) }}
                </span>
                <span class="text-sm text-gray-500">تومان</span>
            </div>
        @endif
    </div>

    <!-- وضعیت موجودی -->
    <div class="flex items-center gap-3">
        <div class="w-3 h-3 rounded-full {{ $product->total > 0 ? 'bg-success' : 'bg-error' }}"></div>
        <span class="font-medium {{ $product->total > 0 ? 'text-success' : 'text-error' }}">
            @if($product->total > 10)
                موجود در انبار
            @elseif($product->total > 0)
                فقط {{ $product->total }} عدد باقی‌مانده
            @else
                ناموجود
            @endif
        </span>
        @if($product->total > 0 && $product->total <= 10)
            <span class="badge badge-warning badge-sm">موجودی محدود</span>
        @endif
    </div>

    <!-- انتخاب تعداد -->
    @if($product->total > 0)
        <div class="space-y-3">
            <label class="text-sm font-semibold">تعداد:</label>
            <div class="flex items-center gap-3">
                <button wire:click="decrement"
                        wire:target="decrement"
                        wire:loading.attr="disabled"
                        class="btn btn-circle btn-outline btn-sm hover:btn-primary"
                    {{ $quantity <= 1 ? 'disabled' : '' }}>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                </button>

                <span class="text-xl font-bold w-12 text-center select-none">{{ $quantity }}</span>

                <button wire:click="increment"
                        wire:target="increment"
                        wire:loading.attr="disabled"
                        class="btn btn-circle btn-outline btn-sm hover:btn-primary"
                    {{ $quantity >= $product->total ? 'disabled' : '' }}>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- دکمه‌های عملیات -->
        <div class="space-y-3">
            <button wire:click="addToCart({{ $product->id }})"
                    class="btn btn-primary w-full gap-2 text-lg h-14"
                    wire:loading.attr="disabled"
                    wire:target="addToCart">
                <span wire:loading.remove wire:target="addToCart">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                </span>
                <span wire:loading wire:target="addToCart" class="loading loading-spinner loading-md"></span>
                <span>افزودن به سبد خرید</span>
            </button>

            <div class="flex gap-3">
                <button wire:click="addToWishlist"
                        class="btn btn-outline flex-1 gap-2"
                        wire:loading.attr="disabled"
                        wire:target="addToWishlist">
                    <span wire:loading.remove wire:target="addToWishlist">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </span>
                    <span wire:loading wire:target="addToWishlist" class="loading loading-spinner loading-sm"></span>
                    علاقه‌مندی
                </button>

                <button wire:click="shareProduct"
                        class="btn btn-outline btn-square"
                        wire:loading.attr="disabled"
                        wire:target="shareProduct">
                    <span wire:loading.remove wire:target="shareProduct">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                    </span>
                    <span wire:loading wire:target="shareProduct" class="loading loading-spinner loading-sm"></span>
                </button>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <span>این محصول در حال حاضر ناموجود است</span>
        </div>
    @endif

    <!-- اطلاعات اضافی -->
    <div class="grid grid-cols-2 gap-4 text-sm">
        @if($product->sold_count > 0)
            <div class="flex items-center gap-2 text-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                <span>{{ $product->sold_count }} فروش</span>
            </div>
        @endif
        @if($product->views > 0)
            <div class="flex items-center gap-2 text-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <span>{{ $product->views }} بازدید</span>
            </div>
        @endif
    </div>
</div>
