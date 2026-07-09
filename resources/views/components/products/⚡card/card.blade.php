<div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300 group">
    <!-- تصویر محصول -->
    <figure class="relative px-4 pt-4 overflow-hidden">
        <a href="#" class="block">
            <img
                src="{{ $product->image_url }}"
                alt="{{ $product->name }}"
                class="rounded-xl h-48 w-full object-cover transition-transform duration-300 group-hover:scale-110"
                loading="lazy"
            />
        </a>

        <!-- نشان‌های تخفیف و جدید -->
        <div class="absolute top-6 right-6 flex flex-col gap-2">
            @if($product->discount_percent)
                <span class="badge badge-error text-white font-bold px-2 py-3">
                    ٪{{ $product->discount_percent }}
                </span>
            @endif

            @if($product->is_new)
                <span class="badge badge-info text-white">جدید</span>
            @endif
        </div>
    </figure>

    <!-- اطلاعات محصول -->
    <div class="card-body p-4">
        <!-- نام محصول -->
        <a href="#" class="block">
            <h3 class="card-title text-lg font-semibold hover:text-primary transition line-clamp-2">
                {{ $product->name }}
            </h3>
        </a>

        <!-- دسته‌بندی -->
        <p class="text-xs text-gray-500 -mt-2">
            {{ $product->category?->name }}
        </p>

        <!-- قیمت -->
        <div class="mt-3 space-y-1">
            <div class="flex items-center gap-2">
                @if($product->discount)
                    <span class="text-xl font-bold text-error">
                                {{ number_format($product->price * (1-($product->discount*0.01))) }} تومان
                    </span>
                    <span class="text-sm text-gray-400 line-through">
                        {{ number_format($product->price) }}
                    </span>
                @else
                    <span class="text-xl font-bold text-primary">
                        {{ number_format($product->price) }}
                    </span>
                @endif
                <span class="text-xs text-gray-500">تومان</span>
            </div>

            <!-- موجودی -->
            @if($product->total > 0)
                <div class="flex items-center gap-1 text-success text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>موجود در انبار</span>
                </div>
            @else
                <div class="flex items-center gap-1 text-error text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span>ناموجود</span>
                </div>
            @endif
        </div>

        <!-- دکمه‌ها -->
        <div class="card-actions mt-4 gap-2">
            @if($product->total > 0)
                <button
                    class="btn btn-primary flex-1"
                    wire:click="addToCart"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                    خرید
                </button>
            @endif

            <a
                href="#"
                class="btn btn-outline btn-sm"
            >
                جزئیات
            </a>
        </div>
    </div>
</div>
