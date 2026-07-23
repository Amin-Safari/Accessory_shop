<div>
    {{-- Success Messages --}}
    @if(session('favorite_removed'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="alert alert-success mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('favorite_removed') }}</span>
            <button @click="show = false" class="btn btn-ghost btn-sm btn-circle">✕</button>
        </div>
    @endif

    @if(session('all_favorites_removed'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="alert alert-info mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('all_favorites_removed') }}</span>
            <button @click="show = false" class="btn btn-ghost btn-sm btn-circle">✕</button>
        </div>
    @endif

    {{-- Controls Bar --}}
    @if($favorites->total() > 0)
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            {{-- Sort Options --}}
            <div class="flex items-center gap-2">
                <span class="text-sm text-base-content/60">مرتب‌سازی:</span>
                <div class="join">
                    <button wire:click="sortBy('newest')"
                            class="join-item btn btn-sm {{ $sortBy === 'newest' ? 'btn-active' : 'btn-ghost' }}">
                        جدیدترین
                    </button>
                    <button wire:click="sortBy('oldest')"
                            class="join-item btn btn-sm {{ $sortBy === 'oldest' ? 'btn-active' : 'btn-ghost' }}">
                        قدیمی‌ترین
                    </button>
                    <button wire:click="sortBy('price_asc')"
                            class="join-item btn btn-sm {{ $sortBy === 'price_asc' ? 'btn-active' : 'btn-ghost' }}">
                        ارزان‌ترین
                    </button>
                    <button wire:click="sortBy('price_desc')"
                            class="join-item btn btn-sm {{ $sortBy === 'price_desc' ? 'btn-active' : 'btn-ghost' }}">
                        گران‌ترین
                    </button>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                <span class="text-sm text-base-content/60">
                    {{ $favorites->total() }} محصول
                </span>
                <button wire:click="removeAllFavorites"
                        wire:confirm="آیا از حذف همه محصولات از علاقه‌مندی‌ها اطمینان دارید؟"
                        class="btn btn-outline btn-error btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    حذف همه
                </button>
            </div>
        </div>
    @endif

    {{-- Empty State --}}
    @if($favorites->isEmpty())
        <div class="card bg-base-100 shadow">
            <div class="card-body text-center py-12">
                <div class="text-6xl mb-4">❤️</div>
                <h3 class="text-xl font-bold mb-2">لیست علاقه‌مندی‌ها خالی است</h3>
                <p class="text-base-content/60 mb-6">
                    شما هنوز محصولی را به علاقه‌مندی‌های خود اضافه نکرده‌اید.
                </p>
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    رفتن به فروشگاه
                </a>
            </div>
        </div>
    @else
        {{-- Products Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($favorites as $favorite)
                @if($favorite->product)
                    <div class="card bg-base-100 shadow hover:shadow-lg transition-all duration-300 group">
                        {{-- Product Image --}}
                        <figure class="relative px-4 pt-4">
                            <a href="{{ route('product.show', $favorite->product->slug) }}" class="w-full">
                                @if(isset($favorite->product->images[0]))
                                    <img src="{{ $favorite->product->images[0] }}"
                                         alt="{{ $favorite->product->name }}"
                                         class="rounded-xl h-48 w-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                @else
                                    <div class="rounded-xl h-48 w-full bg-base-300 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </a>

                            {{-- Discount Badge --}}
                            @if($favorite->product->discount > 0)
                                <div class="absolute top-6 right-6">
                                    <div class="badge badge-error gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $favorite->product->discount }}%
                                    </div>
                                </div>
                            @endif

                            {{-- Out of Stock Badge --}}
                            @if($favorite->product->total <= 0)
                                <div class="absolute inset-0 bg-black/50 rounded-xl flex items-center justify-center m-4">
                                    <span class="text-white font-bold text-lg">ناموجود</span>
                                </div>
                            @endif
                        </figure>

                        {{-- Product Info --}}
                        <div class="card-body">
                            {{-- Category --}}
                            @if($favorite->product->category)
                                <div class="text-xs text-base-content/60 mb-1">
                                    {{ $favorite->product->category->name }}
                                </div>
                            @endif

                            {{-- Product Name --}}
                            <a href="{{ route('product.show', $favorite->product->slug) }}"
                               class="card-title text-base hover:text-primary transition-colors line-clamp-2">
                                {{ $favorite->product->name }}
                            </a>

                            {{-- Price --}}
                            <div class="flex items-end gap-2 mt-2">
                                @if($favorite->product->discount > 0)
                                    <span class="text-sm text-base-content/40 line-through">
                                        {{ number_format($favorite->product->price) }}
                                    </span>
                                    <span class="text-lg font-bold text-primary">
                                        {{ number_format($favorite->product->price * (100 - $favorite->product->discount) / 100) }}
                                    </span>
                                    <span class="text-xs text-base-content/60">تومان</span>
                                @else
                                    <span class="text-lg font-bold text-primary">
                                        {{ number_format($favorite->product->price) }}
                                    </span>
                                    <span class="text-xs text-base-content/60">تومان</span>
                                @endif
                            </div>

                            {{-- Stock Info --}}
                            @if($favorite->product->total > 0 && $favorite->product->total <= 5)
                                <div class="text-xs text-warning mt-1">
                                    فقط {{ $favorite->product->total }} عدد در انبار باقی مانده
                                </div>
                            @endif

                            {{-- Actions --}}
                            <div class="card-actions mt-4">
                                <button wire:click="confirmDelete({{ $favorite->product_id }})"
                                        class="btn btn-outline btn-error btn-sm flex-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    حذف
                                </button>

                                @if($favorite->product->total > 0)
                                    <button class="btn btn-primary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                                        </svg>
                                        خرید
                                    </button>
                                @else
                                    <button class="btn btn-disabled btn-sm flex-1" disabled>
                                        ناموجود
                                    </button>
                                @endif
                            </div>

                            {{-- Added Date --}}
                            <div class="text-xs text-base-content/40 mt-2">
                                اضافه شده در {{ verta($favorite->created_at)->format('Y/m/d') }}
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $favorites->links() }}
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black/50 transition-opacity"
                 wire:click="cancelDelete"></div>

            {{-- Modal Content --}}
            <div class="relative bg-base-100 rounded-lg shadow-xl max-w-sm w-full p-6">
                {{-- Close Button --}}
                <button wire:click="cancelDelete"
                        class="absolute left-4 top-4 text-base-content/40 hover:text-base-content">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Modal Content --}}
                <div class="text-center mb-6">
                    <div class="mx-auto w-16 h-16 rounded-full bg-error/10 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold">حذف از علاقه‌مندی‌ها</h3>
                    <p class="text-sm text-base-content/60 mt-2">
                        آیا از حذف این محصول از لیست علاقه‌مندی‌ها اطمینان دارید؟
                    </p>
                </div>

                {{-- Modal Actions --}}
                <div class="flex gap-3">
                    <button wire:click="removeFromFavorites" class="btn btn-error flex-1">
                        بله، حذف شود
                    </button>
                    <button wire:click="cancelDelete" class="btn btn-ghost flex-1">
                        انصراف
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
