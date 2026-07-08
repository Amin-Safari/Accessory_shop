<div class="container mx-auto px-4 py-16">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold">آخرین مطالب وبلاگ</h2>
            <p class="text-sm opacity-70 mt-2">مقالات آموزشی و راهنمای خرید</p>
        </div>
        <button class="btn btn-ghost">مشاهده وبلاگ →</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @for($i = 1; $i <= 3; $i++)
            <div class="card bg-base-100 shadow-lg hover:shadow-2xl transition-all cursor-pointer group">
                <figure class="overflow-hidden">
                    <img src="/images/blog-{{ $i }}.jpg" alt="مقاله" class="h-48 w-full object-cover group-hover:scale-105 transition-transform duration-300" />
                </figure>
                <div class="card-body">
                    <div class="flex gap-2 mb-2">
                        <div class="badge badge-outline">راهنمای خرید</div>
                        <div class="badge badge-ghost">جدید</div>
                    </div>
                    <h3 class="card-title text-lg group-hover:text-primary transition-colors">
                        راهنمای خرید بهترین قاب برای آیفون ۱۵
                    </h3>
                    <p class="text-sm opacity-70 line-clamp-2">
                        در این مقاله بهترین قاب‌های محافظ برای آیفون ۱۵ را بررسی می‌کنیم...
                    </p>
                    <div class="flex justify-between items-center mt-3">
                        <span class="text-xs opacity-50">📅 ۱۵ دی ۱۴۰۴</span>
                        <span class="text-xs opacity-50">⏱️ ۵ دقیقه مطالعه</span>
                    </div>
                    <button class="btn btn-ghost btn-sm mt-2 group-hover:text-primary">
                        ادامه مطلب ←
                    </button>
                </div>
            </div>
        @endfor
    </div>
</div>
