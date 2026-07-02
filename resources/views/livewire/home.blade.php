<div class="min-h-screen bg-base-200">
    <!-- Notification -->
    <div x-data="{
        show: false,
        message: '',
        type: 'success'
    }"
         @notify.window="
        show = true;
        message = $event.detail.message;
        type = $event.detail.type;
        setTimeout(() => show = false, 3000)
    ">
        <div x-show="show"
             x-transition
             class="fixed top-20 right-4 z-50">
            <div :class="{
                'alert-success': type === 'success',
                'alert-error': type === 'error',
                'alert-info': type === 'info'
            }" class="alert shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span x-text="message"></span>
            </div>
        </div>
    </div>

    <div class="drawer lg:drawer-open">
        <!-- Drawer Toggle -->
        <input id="filter-drawer" type="checkbox" class="drawer-toggle" />

        <!-- Main Content -->
        <div class="drawer-content">
            <!-- Header Banner -->
            <div class="relative bg-gradient-to-r from-primary to-secondary text-primary-content py-12 px-4">
                <div class="container mx-auto">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="mb-6 md:mb-0">
                            <h1 class="text-4xl md:text-5xl font-bold mb-4">🛍️ فروشگاه اکسسوری</h1>
                            <p class="text-lg opacity-90">جدیدترین ترندهای اکسسوری را کشف کنید</p>
                        </div>
                        <div class="stats shadow">
                            <div class="stat">
                                <div class="stat-figure text-primary-content">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0a4.5 4.5 0 01-3.375 4.228M15 21H3v-1a6 6 0 0112 0v1z" />
                                    </svg>
                                </div>
                                <div class="stat-value text-primary-content">+۵۰۰۰</div>
                                <div class="stat-desc text-primary-content/80">مشتری راضی</div>
                            </div>

                            <div class="stat">
                                <div class="stat-figure text-primary-content">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div class="stat-value text-primary-content">+۲۰۰</div>
                                <div class="stat-desc text-primary-content/80">محصول جدید</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container mx-auto px-4 py-8">
                <!-- Toolbar -->
                <div class="flex flex-wrap gap-4 items-center justify-between mb-8 bg-base-100 p-4 rounded-box shadow">
                    <!-- Search & Filter Toggle -->
                    <div class="flex gap-2 flex-1">
                        <div class="form-control flex-1 max-w-xs">
                            <label class="input input-bordered flex items-center gap-2">
                                <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text"
                                       wire:model.live.debounce.300ms="search"
                                       class="grow"
                                       placeholder="جستجوی محصول..." />
                            </label>
                        </div>

                        <label for="filter-drawer" class="btn btn-ghost drawer-button lg:hidden">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            فیلترها
                        </label>
                    </div>

                    <!-- Sort & View Options -->
                    <div class="flex gap-2">
                        <select wire:model.live="sortBy" class="select select-bordered w-full max-w-xs">
                            <option value="newest">جدیدترین</option>
                            <option value="price_asc">قیمت: کم به زیاد</option>
                            <option value="price_desc">قیمت: زیاد به کم</option>
                            <option value="name_asc">نام: الف تا ی</option>
                            <option value="popular">محبوب‌ترین</option>
                        </select>

                        <div class="join">
                            <button class="join-item btn btn-square"
                                    :class="{ 'btn-active': $wire.viewMode === 'grid' }"
                                    wire:click="$set('viewMode', 'grid')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            </button>
                            <button class="join-item btn btn-square"
                                    :class="{ 'btn-active': $wire.viewMode === 'list' }"
                                    wire:click="$set('viewMode', 'list')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Cart Button -->
                    <button class="btn btn-primary gap-2" wire:click="$toggle('showCart')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                        </svg>
                        سبد خرید
                        <span class="badge badge-sm" x-text="$wire.cartCount">0</span>
                    </button>
                </div>

                <!-- Active Filters -->
                @if($selectedCategories || $search)
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="text-sm opacity-70">فیلترهای فعال:</span>
                        @if($search)
                            <span class="badge badge-primary gap-1">
                        جستجو: {{ $search }}
                        <button wire:click="$set('search', '')">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </span>
                        @endif

                        @foreach($selectedCategories as $catId)
                            @php $cat = $categories->find($catId); @endphp
                            <span class="badge badge-secondary gap-1">
                            {{ $cat->name ?? 'دسته‌بندی' }}
                            <button wire:click="removeFilter({{ $catId }})">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </span>
                        @endforeach
                    </div>
                @endif

                <!-- Products Grid -->
                <div class="{{ $viewMode === 'grid' ? 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6' : 'space-y-4' }}">
                    @forelse($products as $product)
                        @if($viewMode === 'grid')
                            <!-- Grid Card -->
                            <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300 group">
                                <figure class="relative px-4 pt-4">
                                    <img src="{{ $product->image_url ?? 'https://placehold.co/400x400' }}"
                                         alt="{{ $product->name }}"
                                         class="rounded-xl h-48 w-full object-cover" />

                                    <!-- Badges -->
                                    <div class="absolute top-2 right-2 flex flex-col gap-1">
                                        @if($product->is_new)
                                            <span class="badge badge-accent">جدید</span>
                                        @endif
                                        @if($product->discount_percent)
                                            <span class="badge badge-error">-{{ $product->discount_percent }}%</span>
                                        @endif
                                    </div>

                                    <!-- Quick Actions -->
                                    <div class="absolute top-2 left-2 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col gap-1">
                                        <button class="btn btn-circle btn-sm btn-ghost bg-base-100/80 backdrop-blur"
                                                wire:click="toggleWishlist({{ $product->id }})">
                                            <svg class="w-4 h-4 {{ in_array($product->id, $wishlist) ? 'text-error fill-current' : '' }}"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                        </button>
                                        <button class="btn btn-circle btn-sm btn-ghost bg-base-100/80 backdrop-blur"
                                                wire:click="quickView({{ $product->id }})">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </figure>

                                <div class="card-body">
                                    <div class="badge badge-outline">{{ $product->category->name ?? 'عمومی' }}</div>
                                    <h2 class="card-title text-lg">
                                        <a href="#" class="hover:text-primary transition-colors">{{ $product->name }}</a>
                                    </h2>

                                    <!-- Rating -->
                                    <div class="rating rating-sm">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" name="rating-{{ $product->id }}"
                                                   class="mask mask-star-2 bg-orange-400"
                                                   {{ $i <= $product->rating ? 'checked' : '' }}
                                                   disabled />
                                        @endfor
                                        <span class="text-xs opacity-70 mr-1">({{ $product->reviews_count }})</span>
                                    </div>

                                    <div class="flex justify-between items-center mt-2">
                                        <div>
                                            @if($product->discount_price)
                                                <span class="text-error font-bold text-lg">{{ number_format($product->discount_price) }} تومان</span>
                                                <span class="text-sm line-through opacity-50 mr-2">{{ number_format($product->price) }}</span>
                                            @else
                                                <span class="font-bold text-lg">{{ number_format($product->price) }} تومان</span>
                                            @endif
                                        </div>

                                        @if($product->stock > 0)
                                            <span class="badge badge-success badge-sm">موجود</span>
                                        @else
                                            <span class="badge badge-error badge-sm">ناموجود</span>
                                        @endif
                                    </div>

                                    <div class="card-actions justify-end mt-4">
                                        <button class="btn btn-primary btn-sm gap-2"
                                                wire:click="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->discount_price ?? $product->price }}, '{{ $product->image_url }}')"
                                                @if(!$product->stock) disabled @endif>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                                            </svg>
                                            افزودن به سبد
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- List Card -->
                            <div class="card bg-base-100 shadow-xl card-side">
                                <figure class="w-48 shrink-0">
                                    <img src="{{ $product->image_url ?? 'https://placehold.co/400x400' }}"
                                         alt="{{ $product->name }}"
                                         class="h-full w-full object-cover" />
                                </figure>
                                <div class="card-body">
                                    <div class="flex justify-between">
                                        <div>
                                            <span class="badge badge-outline">{{ $product->category->name }}</span>
                                            <h2 class="card-title mt-2">{{ $product->name }}</h2>
                                        </div>
                                        <div class="text-left">
                                            @if($product->discount_price)
                                                <span class="text-error font-bold text-xl">{{ number_format($product->discount_price) }}</span>
                                                <span class="text-sm line-through opacity-50 block">{{ number_format($product->price) }} تومان</span>
                                            @else
                                                <span class="font-bold text-xl">{{ number_format($product->price) }} تومان</span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="opacity-70 line-clamp-2">{{ $product->description }}</p>
                                    <div class="card-actions justify-end">
                                        <button class="btn btn-primary btn-sm" wire:click="quickView({{ $product->id }})">
                                            مشاهده سریع
                                        </button>
                                        <button class="btn btn-outline btn-primary btn-sm"
                                                wire:click="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->discount_price ?? $product->price }}, '{{ $product->image_url }}')">
                                            افزودن به سبد
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="col-span-full text-center py-20">
                            <div class="text-6xl mb-4">🔍</div>
                            <h3 class="text-2xl font-bold mb-2">محصولی یافت نشد</h3>
                            <p class="opacity-70">لطفاً عبارت جستجو یا فیلترهای خود را تغییر دهید</p>
                            <button class="btn btn-primary mt-4" wire:click="$set('search', '')">
                                پاک کردن جستجو
                            </button>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>

                <!-- Featured Categories -->
                <div class="mt-16">
                    <h2 class="text-3xl font-bold mb-8 text-center">دسته‌بندی‌های محبوب</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($categories->take(6) as $category)
                            <button class="card bg-base-100 hover:shadow-xl transition-shadow cursor-pointer text-center p-4"
                                    wire:click="$toggle('selectedCategories.{{ $category->id }}')"
                                    :class="{ 'ring-2 ring-primary': $wire.selectedCategories.includes({{ $category->id }}) }">
                                <div class="text-4xl mb-2">
                                    {!! $category->icon ?? '📦' !!}
                                </div>
                                <div class="font-medium">{{ $category->name }}</div>
                                <div class="text-xs opacity-70">{{ $category->products_count }} محصول</div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Filters -->
        <div class="drawer-side z-40">
            <label for="filter-drawer" class="drawer-overlay"></label>
            <div class="bg-base-200 w-80 min-h-full p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">فیلترها</h3>
                    <label for="filter-drawer" class="btn btn-ghost btn-sm drawer-button lg:hidden">✕</label>
                </div>

                <!-- Categories Filter -->
                <div class="mb-6">
                    <h4 class="font-semibold mb-3">دسته‌بندی‌ها</h4>
                    <div class="space-y-2">
                        @foreach($categories as $category)
                            <label class="flex items-center gap-3 cursor-pointer hover:bg-base-300 p-2 rounded-lg transition">
                                <input type="checkbox"
                                       wire:model.live="selectedCategories"
                                       value="{{ $category->id }}"
                                       class="checkbox checkbox-primary checkbox-sm" />
                                <span class="flex-1">{{ $category->name }}</span>
                                <span class="badge badge-sm">{{ $category->products_count }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range -->
                <div class="mb-6">
                    <h4 class="font-semibold mb-3">محدوده قیمت</h4>
                    <div class="space-y-3">
                        <input type="range"
                               min="0"
                               max="1000000"
                               wire:model.live="priceRange.1"
                               class="range range-primary range-sm" />
                        <div class="flex justify-between">
                            <div class="badge">از {{ number_format($priceRange[0]) }} ت</div>
                            <div class="badge">تا {{ number_format($priceRange[1]) }} ت</div>
                        </div>
                    </div>
                </div>

                <!-- Reset Filters -->
                <button class="btn btn-outline btn-error btn-block"
                        wire:click="$set('selectedCategories', []); $set('priceRange', [0, 1000000]); $set('search', ''); $set('sortBy', 'newest')">
                    حذف همه فیلترها
                </button>
            </div>
        </div>
    </div>

    <!-- Cart Drawer -->
    @if($showCart)
        <div class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" wire:click="$toggle('showCart')"></div>
            <div class="absolute left-0 top-0 h-full w-full max-w-md bg-base-100 shadow-2xl transform transition-transform">
                <div class="flex flex-col h-full">
                    <!-- Cart Header -->
                    <div class="flex justify-between items-center p-4 border-b">
                        <h3 class="text-xl font-bold">سبد خرید</h3>
                        <button class="btn btn-ghost btn-sm" wire:click="$toggle('showCart')">✕</button>
                    </div>

                    <!-- Cart Items -->
                    <div class="flex-1 overflow-y-auto p-4">
                        @if(count($cart) > 0)
                            <div class="space-y-4">
                                @foreach($cart as $item)
                                    <div class="flex gap-3 bg-base-200 p-3 rounded-lg">
                                        <img src="{{ $item['image'] }}"
                                             class="w-20 h-20 object-cover rounded" />
                                        <div class="flex-1">
                                            <h4 class="font-medium">{{ $item['name'] }}</h4>
                                            <div class="text-primary font-bold">{{ number_format($item['price']) }} ت</div>
                                            <div class="flex items-center gap-2 mt-2">
                                                <button class="btn btn-ghost btn-xs"
                                                        wire:click="updateCartQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})">-</button>
                                                <span class="w-8 text-center">{{ $item['quantity'] }}</span>
                                                <button class="btn btn-ghost btn-xs"
                                                        wire:click="updateCartQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})">+</button>
                                            </div>
                                        </div>
                                        <div class="text-left">
                                            <div class="font-bold">{{ number_format($item['price'] * $item['quantity']) }} ت</div>
                                            <button class="btn btn-ghost btn-xs text-error mt-2"
                                                    wire:click="removeFromCart({{ $item['id'] }})">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-20">
                                <div class="text-6xl mb-4">🛒</div>
                                <p class="text-lg opacity-70">سبد خرید خالی است</p>
                                <button class="btn btn-primary mt-4" wire:click="$toggle('showCart')">
                                    شروع خرید
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Cart Footer -->
                    @if(count($cart) > 0)
                        <div class="border-t p-4 space-y-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span>جمع کل:</span>
                                <span class="text-primary">{{ number_format($cartTotal) }} تومان</span>
                            </div>
                            <button class="btn btn-primary btn-block btn-lg">
                                ادامه فرایند خرید
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Quick View Modal -->
    @if($quickViewProduct)
        <div class="modal modal-open">
            <div class="modal-box max-w-3xl">
                <button class="btn btn-sm btn-circle absolute right-2 top-2" wire:click="closeQuickView">✕</button>

                <div class="flex flex-col md:flex-row gap-6">
                    <div class="flex-1">
                        <img src="{{ $quickViewProduct->image_url ?? 'https://placehold.co/600x600' }}"
                             class="w-full rounded-lg" />
                    </div>
                    <div class="flex-1">
                        <span class="badge badge-outline">{{ $quickViewProduct->category->name }}</span>
                        <h3 class="text-2xl font-bold mt-2">{{ $quickViewProduct->name }}</h3>

                        <div class="rating rating-sm mt-2">
                            @for($i = 1; $i <= 5; $i++)
                                <input type="radio" class="mask mask-star-2 bg-orange-400"
                                       {{ $i <= $quickViewProduct->rating ? 'checked' : '' }} disabled />
                            @endfor
                        </div>

                        <div class="text-3xl font-bold text-primary mt-4">
                            {{ number_format($quickViewProduct->discount_price ?? $quickViewProduct->price) }} تومان
                        </div>

                        <p class="mt-4 opacity-70">{{ $quickViewProduct->description }}</p>

                        <div class="mt-6 space-y-3">
                            <button class="btn btn-primary btn-block btn-lg"
                                    wire:click="addToCart({{ $quickViewProduct->id }}, '{{ $quickViewProduct->name }}', {{ $quickViewProduct->discount_price ?? $quickViewProduct->price }}, '{{ $quickViewProduct->image_url }}')">
                                افزودن به سبد خرید
                            </button>
                            <button class="btn btn-outline btn-block"
                                    wire:click="toggleWishlist({{ $quickViewProduct->id }})">
                                @if(in_array($quickViewProduct->id, $wishlist))
                                    حذف از علاقه‌مندی‌ها
                                @else
                                    افزودن به علاقه‌مندی‌ها
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
