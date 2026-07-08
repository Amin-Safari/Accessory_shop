<div class="container mx-auto px-4 py-16 bg-base-200">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold">محصولات ویژه</h2>
        <a href="#" class="btn btn-ghost">مشاهده همه ←</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($this->featuredProducts as $product)
            <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all group">
                <figure class="px-4 pt-4 relative">
                    <img src="{{ $product->image_url }}"
                         alt="{{ $product->name }}"
                         class="rounded-xl h-48 w-full object-cover group-hover:scale-105 transition-transform duration-300"
                         loading="lazy">
                    @if($product->price > 0 && $product->discount > 0)
                        <div class="absolute top-6 left-6">
                            <div class="badge badge-error gap-1">
                                {{ number_format((($product->price - $product->total) / $product->price) * 100) }}٪
                            </div>
                        </div>
                    @endif
                </figure>
                <div class="card-body">
                    <div class="badge badge-outline mb-2">{{ $product->category->name }}</div>
                    <h3 class="card-title text-lg">
                        <a href="#" class="group-hover:text-primary transition-colors">
                            {{ $product->name }}
                        </a>
                    </h3>
                    <button class="btn btn-primary btn-sm mt-3"
                                wire:click="addToCart({{ $product->id }})">
                            افزودن به سبد
                        </button>
                    <div class="flex items-center gap-2 mt-2">
                        @if($product->discount > 0)
                            <span class="text-xl font-bold text-primary">
                                {{ number_format($product->price * (1-($product->discount*0.01))) }} تومان
                        </span>
                            <span class="text-sm line-through opacity-50">
                            {{ number_format($product->price) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-lg opacity-70">محصول ویژه‌ای یافت نشد</p>
            </div>
        @endforelse
    </div>
</div>
