<div class="min-h-screen bg-base-200">
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <div class="text-sm breadcrumbs mb-6">
            <ul>
                <li><a href="/" class="text-primary">خانه</a></li>
                <li><a href="/products" class="text-primary">محصولات</a></li>
                @if($category)
                    <li>{{ \App\Models\Category::find($category)?->name }}</li>
                @endif
            </ul>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- دکمه فیلتر در موبایل -->
            <div class="lg:hidden mb-4">
                <label for="filter-drawer" class="btn btn-primary drawer-button w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    فیلترها
                </label>
            </div>

            <!-- Drawer برای موبایل -->
            <div class="drawer lg:hidden">
                <input id="filter-drawer" type="checkbox" class="drawer-toggle"/>
                <div class="drawer-side z-50">
                    <label for="filter-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
                    <div class="menu p-4 w-80 min-h-full bg-base-100 text-base-content">
                        <h3 class="text-lg font-bold mb-4">فیلترها</h3>

                        {{-- فیلترهای موبایل --}}
                        <div>
                            <div class="mb-6">
                                <div class="relative">
                                    <input type="text" placeholder="جستجوی محصول..." class="input input-bordered w-full pl-10" wire:model.live.debounce.500ms="search">
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <div class="mb-6">
                                <h4 class="font-semibold mb-3">دسته‌بندی</h4>
                                <div class="space-y-2 max-h-60 overflow-y-auto">
                                    <label class="flex items-center gap-3 cursor-pointer hover:bg-base-200 p-2 rounded-lg">
                                        <input type="radio" name="mobile_category" value="" class="radio radio-primary radio-sm" wire:model.live="category">
                                        <span class="text-sm flex-1">همه دسته‌ها</span>
                                    </label>
                                    @foreach($categories as $cat)
                                        <label class="flex items-center gap-3 cursor-pointer hover:bg-base-200 p-2 rounded-lg">
                                            <input type="radio" name="mobile_category" value="{{ $cat->id }}" class="radio radio-primary radio-sm" wire:model.live="category">
                                            <span class="text-sm flex-1">{{ $cat->name }}</span>
                                            <span class="badge badge-sm">{{ $cat->products_count }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="divider"></div>

                            <div class="mb-6">
                                <h4 class="font-semibold mb-3">محدوده قیمت (تومان)</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-xs text-gray-500 mb-1 block">از</label>
                                        <input type="number" placeholder="۰" class="input input-bordered input-sm w-full" wire:model.live.debounce.500ms="min_price">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 mb-1 block">تا</label>
                                        <input type="number" placeholder="{{ $priceRange ? number_format($priceRange->max_price) : '' }}" class="input input-bordered input-sm w-full" wire:model.live.debounce.500ms="max_price">
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <div class="mb-6">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" class="toggle toggle-primary" wire:model.live="in_stock">
                                    <span class="text-sm font-semibold">فقط کالاهای موجود</span>
                                </label>
                            </div>

                            <button class="btn btn-outline btn-sm w-full" wire:click="resetFilters">
                                حذف همه فیلترها
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- فیلترها در دسکتاپ -->
            <div class="hidden lg:block w-80 flex-shrink-0">
                <div class="bg-base-100 rounded-xl p-6 sticky top-4">
                    <h3 class="text-lg font-bold mb-4">فیلترها</h3>

                    {{-- فیلترهای دسکتاپ --}}
                    <div>
                        <div class="mb-6">
                            <div class="relative">
                                <input type="text" placeholder="جستجوی محصول..." class="input input-bordered w-full pl-10" wire:model.live.debounce.500ms="search">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="mb-6">
                            <h4 class="font-semibold mb-3">دسته‌بندی</h4>
                            <div class="space-y-2 max-h-60 overflow-y-auto">
                                <label class="flex items-center gap-3 cursor-pointer hover:bg-base-200 p-2 rounded-lg">
                                    <input type="radio" name="desktop_category" value="" class="radio radio-primary radio-sm" wire:model.live="category">
                                    <span class="text-sm flex-1">همه دسته‌ها</span>
                                </label>
                                @foreach($categories as $cat)
                                    <label class="flex items-center gap-3 cursor-pointer hover:bg-base-200 p-2 rounded-lg">
                                        <input type="radio" name="desktop_category" value="{{ $cat->id }}" class="radio radio-primary radio-sm" wire:model.live="category">
                                        <span class="text-sm flex-1">{{ $cat->name }}</span>
                                        <span class="badge badge-sm">{{ $cat->products_count }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="mb-6">
                            <h4 class="font-semibold mb-3">محدوده قیمت (تومان)</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-gray-500 mb-1 block">از</label>
                                    <input type="number" placeholder="۰" class="input input-bordered input-sm w-full" wire:model.live.debounce.500ms="min_price">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 mb-1 block">تا</label>
                                    <input type="number" placeholder="{{ $priceRange ? number_format($priceRange->max_price) : '' }}" class="input input-bordered input-sm w-full" wire:model.live.debounce.500ms="max_price">
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="mb-6">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" class="toggle toggle-primary" wire:model.live="in_stock">
                                <span class="text-sm font-semibold">فقط کالاهای موجود</span>
                            </label>
                        </div>

                        <button class="btn btn-outline btn-sm w-full" wire:click="resetFilters">
                            حذف همه فیلترها
                        </button>
                    </div>
                </div>
            </div>

            <!-- بخش محصولات -->
            <div class="flex-1">
                <!-- تعداد نتایج و مرتب‌سازی -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-base-100 rounded-xl p-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">
                            {{ $products->total() }} محصول پیدا شد
                        </span>
                        @if($search)
                            <span class="badge badge-primary gap-1">
                                جستجو: {{ $search }}
                                <button wire:click="$set('search', '')" class="ml-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </span>
                        @endif
                    </div>

                    <select class="select select-bordered w-full sm:w-auto" wire:model.live="sort">
                        <option value="newest">جدیدترین</option>
                        <option value="cheapest">ارزان‌ترین</option>
                        <option value="expensive">گران‌ترین</option>
                        <option value="bestselling">پرفروش‌ترین</option>
                        <option value="most_viewed">بیشترین بازدید</option>
                        <option value="discounted">بیشترین تخفیف</option>
                    </select>
                </div>

                <!-- گرید محصولات -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($products as $product)
                        <livewire:products.card :product="$product" :key="$product->id"/>
                    @empty
                        <div class="col-span-full">
                            <div class="text-center py-12 bg-base-100 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-600 mb-2">محصولی یافت نشد</h3>
                                <p class="text-gray-500">با فیلترهای دیگری جستجو کنید</p>
                                <button class="btn btn-primary mt-4" wire:click="resetFilters">
                                    حذف همه فیلترها
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
