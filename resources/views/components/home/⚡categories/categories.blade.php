<div class="container mx-auto px-4 py-16">
    <h2 class="text-3xl font-bold text-center mb-8">دسته‌بندی محصولات</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">


        @foreach ($this->categories as $category)
            <a href="#" class="card bg-base-100 shadow-xl">
                <div class="card-body items-center text-center">

                    @if ($category->image)
                        <img
                            src="{{ Storage::url($category->image) }}"
                            alt="{{ $category->name }}"
                            class="w-12 h-12"
                        >
                    @endif

                    <h3>{{ $category->name }}</h3>
                    <p>{{ $category->products_count }} محصول</p>

                </div>
            </a>
        @endforeach
        <a href="#"
           class="card bg-base-200 hover:bg-base-300 transition-all cursor-pointer group border-2 border-dashed border-base-300">
            <div class="card-body items-center text-center p-6">
                <div class="text-4xl mb-3 opacity-50 group-hover:opacity-100 transition-opacity"> ➕</div>
                <h3 class="font-bold opacity-70">همه دسته‌بندی‌ها</h3>
                {{--            <p class="text-xs opacity-50">{{ Cache::remember('total_categories_count', 3600, fn() => Category::where('is_active', true)->count()) }}--}}
                {{--                دسته</p>--}}
            </div>
        </a>
    </div>
</div>
