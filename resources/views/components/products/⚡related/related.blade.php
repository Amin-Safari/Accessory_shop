<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold">محصولات مرتبط</h2>
        @if($products->first())
            <a href="/products?category={{ $products->first()->category_id }}"
               class="btn btn-ghost btn-sm gap-2">
                مشاهده همه
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endif
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($products as $relatedProduct)
            <a href="{{ route('products.show', $relatedProduct->slug) }}"
               class="group bg-base-100 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300">

                <div class="aspect-square overflow-hidden">
                    <img src="{{ $relatedProduct->image_url }}"
                         alt="{{ $relatedProduct->name }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                         loading="lazy">

                    @if($relatedProduct->discount > 0)
                        <div class="absolute top-2 right-2">
                            <div class="badge badge-error text-white border-0 text-xs">
                                {{ $relatedProduct->discount }}%
                            </div>
                        </div>
                    @endif
                </div>

                <div class="p-4 space-y-2">
                    <h3 class="font-medium text-sm line-clamp-2 group-hover:text-primary transition-colors min-h-[2.5rem]">
                        {{ $relatedProduct->name }}
                    </h3>

                    <div class="flex items-center gap-2">
                        @if($relatedProduct->discount > 0)
                            <span class="text-error font-bold text-sm">
                                {{ number_format($relatedProduct->final_price) }}
                            </span>
                            <span class="text-xs text-gray-500 line-through">
                                {{ number_format($relatedProduct->price) }}
                            </span>
                        @else
                            <span class="font-bold text-sm">
                                {{ number_format($relatedProduct->price) }}
                            </span>
                        @endif
                        <span class="text-xs text-gray-500 mr-auto">تومان</span>
                    </div>

                    @if($relatedProduct->total <= 0)
                        <div class="badge badge-ghost badge-sm w-full justify-center">ناموجود</div>
                    @elseif($relatedProduct->total <= 10)
                        <div class="badge badge-warning badge-sm w-full justify-center">تنها {{ $relatedProduct->total }} عدد</div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
</div>
