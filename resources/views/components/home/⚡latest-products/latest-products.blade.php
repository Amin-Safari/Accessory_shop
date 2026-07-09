<div class="container mx-auto px-4 py-16">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold">جدیدترین محصولات</h2>
            <p class="text-sm opacity-70 mt-2">آخرین محصولات اضافه شده به فروشگاه</p>
        </div>
        <a href="#" class="btn btn-ghost">
            مشاهده همه ←
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($this->latestProducts as $product)
            <div class="card bg-base-100 shadow-lg hover:shadow-2xl transition-all duration-300 group">
                <figure class="relative px-4 pt-4 overflow-hidden">
                    <img src="{{ $product->image_url }}"
                         alt="{{ $product->name }}"
                         class="rounded-xl h-48 w-full object-cover group-hover:scale-105 transition-transform duration-300"
                         loading="lazy">
                    @if($product->is_new)
                        <div class="absolute top-6 left-6">
                            <div class="badge badge-success gap-1">جدید</div>
                        </div>
                    @endif
                </figure>
                <div class="card-body">
                    <div class="badge badge-outline mb-2">{{ $product->category->name }}</div>
                    <h3 class="card-title text-lg group-hover:text-primary transition-colors">
                        <a href="#">{{ $product->name }}</a>
                    </h3>
                    <div class="flex justify-between items-center mt-2">
                        @if($product->discount > 0)
                            <span class="text-xl font-bold text-primary">
                                {{ number_format($product->price * (1-($product->discount*0.01))) }} تومان
                        </span>
                            <span class="text-sm line-through opacity-50">
                            {{ number_format($product->price) }}
                            </span>
                        @else
                            <span class="text-lg font-bold text-primary">
                        {{ number_format($product->price) }} تومان
                    </span>
                        @endif
                        {{--                        <span class="text-sm line-through opacity-50">--}}
                        {{--                        {{ number_format($product->total) }}--}}
                        {{--                    </span>--}}
                    </div>
                    <div class="card-actions mt-3">
                        <button class="btn btn-primary btn-sm btn-block"
                                wire:click="addToCart({{ $product->id }})">
                            افزودن به سبد خرید
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-lg opacity-70">محصول جدیدی یافت نشد</p>
            </div>
        @endforelse
    </div>
</div>
