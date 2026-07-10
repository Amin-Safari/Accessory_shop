<div class="space-y-6">
    @forelse($reviews as $review)
        <div class="bg-base-200 rounded-xl p-4 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="avatar placeholder">
                        <div class="bg-primary text-primary-content rounded-full w-10">
                            <span class="text-sm">{{ substr($review->user->name ?? 'U', 0, 1) }}</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-semibold">{{ $review->user->name ?? 'کاربر ناشناس' }}</h4>
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-warning' : 'text-gray-300' }}"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>
                </div>
                <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
            </div>

            @if($review->title)
                <h5 class="font-medium text-base">{{ $review->title }}</h5>
            @endif

            <p class="text-gray-600">{{ $review->body }}</p>
        </div>
    @empty
        <div class="text-center py-8">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
            <p class="text-gray-500">هنوز نظری برای این محصول ثبت نشده است</p>
            <p class="text-sm text-gray-400 mt-2">اولین نفری باشید که نظر می‌دهید</p>
        </div>
    @endforelse
</div>
