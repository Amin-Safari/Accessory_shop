<div x-data="{
    activeImage: '{{ $activeImage }}',
    isZoomed: false,
    zoomX: 0,
    zoomY: 0,

    setActive(url) {
        this.activeImage = url;
    },

    zoom(e) {
        const rect = e.target.getBoundingClientRect();
        this.zoomX = ((e.clientX - rect.left) / rect.width) * 100;
        this.zoomY = ((e.clientY - rect.top) / rect.height) * 100;
    }
}" class="space-y-4">

    <!-- تصویر اصلی -->
    <div class="relative bg-base-100 rounded-2xl overflow-hidden cursor-zoom-in"
         @mousemove="isZoomed && zoom($event)"
         @click="isZoomed = !isZoomed"
         @mouseleave="isZoomed = false">

        <div class="aspect-square relative overflow-hidden">
            <img :src="activeImage"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover transition-transform duration-300"
                 :class="{ 'scale-150': isZoomed }"
                 :style="isZoomed ? `transform-origin: ${zoomX}% ${zoomY}%` : ''">

            <!-- Badge تخفیف -->
            @if($product->discount > 0)
                <div class="absolute top-4 right-4">
                    <div class="badge badge-error gap-1 text-white border-0 px-3 py-4">
                        <span class="text-lg font-bold">{{ $product->discount }}%</span>
                        <span>تخفیف</span>
                    </div>
                </div>
            @endif

            <!-- Badge جدید -->
            @if($product->is_new)
                <div class="absolute top-4 left-4">
                    <div class="badge badge-info gap-1 text-white border-0 px-3 py-4">
                        <span>جدید</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Thumbnails -->
    @if(count($images) > 1)
        <div class="grid grid-cols-5 gap-3">
            @foreach($images as $image)
                <button
                    @click="setActive('{{ $image }}')"
                    class="aspect-square rounded-xl overflow-hidden border-2 transition-all duration-200 hover:border-primary"
                    :class="{ 'border-primary shadow-lg ring-2 ring-primary ring-opacity-50': activeImage === '{{ $image }}', 'border-base-300': activeImage !== '{{ $image }}' }">

                    <img src="{{ $image }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover"
                         loading="lazy">
                </button>
            @endforeach
        </div>
    @endif
</div>
