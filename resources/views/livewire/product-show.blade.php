<div class="min-h-screen bg-base-200">
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <div class="text-sm breadcrumbs mb-6">
            <ul>
                <li><a href="/" class="text-primary">خانه</a></li>
                <li><a href="/products" class="text-primary">محصولات</a></li>
                @if($product->category)
                    <li>
                        <a href="/products?category={{ $product->category_id }}" class="text-primary">
                            {{ $product->category->name }}
                        </a>
                    </li>
                @endif
                <li>{{ $product->name }}</li>
            </ul>
        </div>

        <!-- بخش اصلی محصول -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
            <!-- گالری تصاویر -->
            <div class="lg:sticky lg:top-4 h-fit">
                <livewire:products.gallery
                    :product="$product"
                    wire:key="gallery-{{ $product->id }}"
                />
            </div>

            <!-- اطلاعات محصول -->
            <div>
                <livewire:products.info
                    :product="$product"
                    :quantity="$quantity"
                    wire:key="info-{{ $product->id }}"
                />
            </div>
        </div>

        <!-- تب‌ها -->
        <div class="mt-12">
            <livewire:products.tabs
                :product="$product"
                wire:key="tabs-{{ $product->id }}"
            />
        </div>

        <!-- محصولات مرتبط -->
        @if($relatedProducts->count() > 0)
            <div class="mt-12">
                <livewire:products.related
                    :products="$relatedProducts"
                    wire:key="related-{{ $product->id }}"
                />
            </div>
        @endif
    </div>
</div>
