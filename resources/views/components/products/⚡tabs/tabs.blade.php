<div>
    <!-- Tab Buttons -->
    <div role="tablist" class="tabs tabs-lifted tabs-lg">
        <button
            role="tab"
            wire:click="$set('activeTab', 'description')"
            class="tab {{ $activeTab === 'description' ? 'tab-active font-bold' : '' }}">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
            </svg>
            توضیحات
        </button>

        <button
            role="tab"
            wire:click="$set('activeTab', 'reviews')"
            class="tab {{ $activeTab === 'reviews' ? 'tab-active font-bold' : '' }}">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
            نظرات
        </button>
    </div>

    <!-- Tab Content -->
    <div class="bg-base-100 rounded-b-2xl rounded-tr-2xl p-6 min-h-[300px]">
        @if($activeTab === 'description')
            <div class="prose max-w-none">
                @if($product->description)
                    <p class="text-gray-500 leading-relaxed">{{ $product->description }}</p>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p>توضیحاتی برای این محصول ثبت نشده است</p>
                    </div>
                @endif
            </div>
        @elseif($activeTab === 'reviews')
            <div class="text-center py-8 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
                <p>بخش نظرات به زودی اضافه خواهد شد</p>
            </div>
        @endif
    </div>
</div>
