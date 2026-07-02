<!DOCTYPE html>
<html lang="fa" dir="rtl" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'فروشگاه اکسسوری | Accessory Shop')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>💎</text></svg>">

    <!-- Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/vazirmatn@33.0.3/Vazirmatn-font-face.css" rel="stylesheet">

    <!-- Tailwind & DaisyUI -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles

    <style>
        * {
            font-family: 'Vazirmatn', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: hsl(var(--b2));
        }

        ::-webkit-scrollbar-thumb {
            background: hsl(var(--p));
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: hsl(var(--pf));
        }

        /* Loading Animation */
        .loading-spinner {
            border: 3px solid hsl(var(--b3));
            border-top: 3px solid hsl(var(--p));
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Smooth Transitions */
        .smooth-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="min-h-screen bg-base-200" x-data="{
    darkMode: localStorage.getItem('theme') === 'dark',
    mobileMenuOpen: false,
    searchOpen: false,
    cartOpen: false,

    init() {
        this.$watch('darkMode', value => {
            localStorage.setItem('theme', value ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', value ? 'dark' : 'light');
        });

        // Set initial theme
        if (this.darkMode) {
            document.documentElement.setAttribute('data-theme', 'dark');
        }

        // Listen for Livewire events
        Livewire.on('cart-updated', (event) => {
            console.log('Cart updated:', event);
        });
    }
}">

<!-- Loading Indicator -->
<div wire:loading class="fixed top-0 left-0 right-0 z-50">
    <div class="h-1 bg-primary">
        <div class="h-full bg-primary-content animate-pulse" style="width: 100%"></div>
    </div>
</div>

<!-- Page Loading Spinner -->
<div wire:loading.delay class="fixed inset-0 bg-base-100/50 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="loading-spinner"></div>
</div>

<!-- Top Bar -->
<div class="bg-primary text-primary-content hidden lg:block">
    <div class="container mx-auto px-4 py-2">
        <div class="flex justify-between items-center text-sm">
            <div class="flex gap-6">
                <a href="tel:02112345678" class="hover:opacity-80 transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    ۰۲۱-۱۲۳۴۵۶۷۸
                </a>
                <a href="mailto:info@accessoryshop.com" class="hover:opacity-80 transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    info@accessoryshop.com
                </a>
            </div>
            <div class="flex gap-4">
                <span>🚚 ارسال رایگان برای سفارشات بالای ۵۰۰ هزار تومان</span>
            </div>
        </div>
    </div>
</div>

<!-- Main Header / Navbar -->
<header class="bg-base-100 shadow-lg sticky top-0 z-40 backdrop-blur-lg bg-base-100/80">
    <div class="container mx-auto px-4">
        <div class="navbar min-h-[4rem]">
            <!-- Mobile Menu Button -->
            <div class="navbar-start">
                <div class="dropdown">
                    <label tabindex="0" class="btn btn-ghost lg:hidden" @click="mobileMenuOpen = !mobileMenuOpen">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </label>

                    <!-- Mobile Menu -->
                    <div x-show="mobileMenuOpen"
                         @click.away="mobileMenuOpen = false"
                         x-transition
                         class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-72 mt-3 z-50">
                        <li><a href="/" class="active">🏠 صفحه اصلی</a></li>
                        <li><a href="#">🛍️ محصولات</a></li>
                        <li>
                            <a href="#">📿 گردنبند</a>
                            <ul class="p-2">
                                <li><a href="#">طلا</a></li>
                                <li><a href="#">نقره</a></li>
                                <li><a href="#">بدلیجات</a></li>
                            </ul>
                        </li>
                        <li><a href="#">💫 دستبند</a></li>
                        <li><a href="#">💍 انگشتر</a></li>
                        <li><a href="#">✨ گوشواره</a></li>
                        <li><a href="#">⌚ ساعت</a></li>
                        <li><a href="#">👜 کیف</a></li>
                        <div class="divider my-1"></div>
                        <li><a href="#">ℹ️ درباره ما</a></li>
                        <li><a href="#">📞 تماس با ما</a></li>
                        <li><a href="#">📝 وبلاگ</a></li>
                    </div>
                </div>

                <!-- Logo -->
                <a href="/" class="btn btn-ghost normal-case text-xl font-bold gap-2">
                    <span class="text-3xl">💎</span>
                    <div>
                        <div class="text-base">Accessory Shop</div>
                        <div class="text-xs opacity-70 font-normal">فروشگاه اکسسوری</div>
                    </div>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="navbar-center hidden lg:flex">
                <ul class="menu menu-horizontal px-1">
                    <li><a href="/" class="font-medium">صفحه اصلی</a></li>
                    <li tabindex="0">
                        <a href="#" class="font-medium">
                            محصولات
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </a>
                        <ul class="p-2 bg-base-100 z-50 w-52">
                            <li><a href="#">📿 گردنبند</a></li>
                            <li><a href="#">💫 دستبند</a></li>
                            <li><a href="#">💍 انگشتر</a></li>
                            <li><a href="#">✨ گوشواره</a></li>
                            <li><a href="#">⌚ ساعت</a></li>
                            <li><a href="#">👜 کیف و کوله</a></li>
                        </ul>
                    </li>
                    <li><a href="#" class="font-medium">تخفیف‌ها</a></li>
                    <li><a href="#" class="font-medium">جدیدترین‌ها</a></li>
                    <li><a href="#" class="font-medium">درباره ما</a></li>
                    <li><a href="#" class="font-medium">تماس با ما</a></li>
                </ul>
            </div>

            <!-- Right Side Actions -->
            <div class="navbar-end gap-1">
                <!-- Search Toggle -->
                <button class="btn btn-ghost btn-circle" @click="searchOpen = !searchOpen">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                <!-- Wishlist -->
                <button class="btn btn-ghost btn-circle hidden sm:flex">
                    <div class="indicator">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span class="badge badge-sm indicator-item">0</span>
                    </div>
                </button>

                <!-- Cart -->
                <button class="btn btn-ghost btn-circle" @click="cartOpen = !cartOpen">
                    <div class="indicator">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                        </svg>
                        <span class="badge badge-sm indicator-item" id="cart-count">0</span>
                    </div>
                </button>

                <!-- Theme Toggle -->
                <button class="btn btn-ghost btn-circle" @click="darkMode = !darkMode">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>

                <!-- User Menu -->
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                        <div class="w-10 rounded-full">
                            <img src="https://placehold.co/100x100?text=👤" alt="User" />
                        </div>
                    </label>
                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52 z-50 mt-3">
                        <li><a href="#" class="font-medium">👤 پروفایل من</a></li>
                        <li><a href="#" class="font-medium">📦 سفارشات</a></li>
                        <li><a href="#" class="font-medium">❤️ علاقه‌مندی‌ها</a></li>
                        <div class="divider my-1"></div>
                        <li><a href="#" class="text-error">🚪 خروج</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Search Bar (Expandable) -->
        <div x-show="searchOpen"
             @click.away="searchOpen = false"
             x-transition
             class="py-3 border-t border-base-300">
            <div class="form-control">
                <div class="input-group">
                    <input type="text"
                           placeholder="جستجو در محصولات..."
                           class="input input-bordered w-full focus:input-primary"
                           x-ref="searchInput"
                           x-init="$nextTick(() => $refs.searchInput.focus())" />
                    <button class="btn btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        جستجو
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="min-h-[60vh]">
    {{ $slot }}
</main>

<!-- Footer -->
<footer class="bg-base-300 text-base-content mt-16">
    <!-- Newsletter -->
    <div class="bg-primary text-primary-content py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold mb-2">📧 عضویت در خبرنامه</h3>
                    <p class="opacity-90">برای دریافت جدیدترین تخفیف‌ها و محصولات عضو شوید</p>
                </div>
                <div class="flex gap-2 w-full md:w-auto">
                    <input type="email"
                           placeholder="ایمیل خود را وارد کنید"
                           class="input input-bordered w-full md:w-80 text-base-content" />
                    <button class="btn btn-accent">عضویت</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- About -->
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-3xl">💎</span>
                    <div>
                        <h3 class="text-xl font-bold">Accessory Shop</h3>
                        <p class="text-xs opacity-70">فروشگاه آنلاین اکسسوری</p>
                    </div>
                </div>
                <p class="opacity-70 mb-4 leading-relaxed">
                    ما بهترین و باکیفیت‌ترین اکسسوری‌ها را با مناسب‌ترین قیمت به شما ارائه می‌دهیم.
                    با بیش از ۱۰ سال تجربه در زمینه فروش زیورآلات و اکسسوری.
                </p>
                <div class="flex gap-2">
                    <a href="#" class="btn btn-circle btn-ghost btn-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="btn btn-circle btn-ghost btn-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </a>
                    <a href="#" class="btn btn-circle btn-ghost btn-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678a6.162 6.162 0 100 12.324 6.162 6.162 0 100-12.324zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405a1.441 1.441 0 11-2.882 0 1.441 1.441 0 012.882 0z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-bold mb-4">دسترسی سریع</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="link link-hover opacity-70 hover:opacity-100">صفحه اصلی</a></li>
                    <li><a href="#" class="link link-hover opacity-70 hover:opacity-100">محصولات جدید</a></li>
                    <li><a href="#" class="link link-hover opacity-70 hover:opacity-100">پرفروش‌ترین‌ها</a></li>
                    <li><a href="#" class="link link-hover opacity-70 hover:opacity-100">تخفیف‌های ویژه</a></li>
                    <li><a href="#" class="link link-hover opacity-70 hover:opacity-100">وبلاگ</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div>
                <h3 class="text-lg font-bold mb-4">خدمات مشتریان</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="link link-hover opacity-70 hover:opacity-100">سوالات متداول</a></li>
                    <li><a href="#" class="link link-hover opacity-70 hover:opacity-100">راهنمای خرید</a></li>
                    <li><a href="#" class="link link-hover opacity-70 hover:opacity-100">شرایط بازگشت کالا</a></li>
                    <li><a href="#" class="link link-hover opacity-70 hover:opacity-100">حریم خصوصی</a></li>
                    <li><a href="#" class="link link-hover opacity-70 hover:opacity-100">قوانین و مقررات</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-bold mb-4">اطلاعات تماس</h3>
                <ul class="space-y-3">
                    <li class="flex items-start gap-2 opacity-70">
                        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>تهران، خیابان ولیعصر، مرکز خرید الماس، طبقه ۳</span>
                    </li>
                    <li class="flex items-center gap-2 opacity-70">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>۰۲۱-۱۲۳۴۵۶۷۸ | ۰۲۱-۸۷۶۵۴۳۲۱</span>
                    </li>
                    <li class="flex items-center gap-2 opacity-70">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>info@accessoryshop.com</span>
                    </li>
                    <li class="flex items-center gap-2 opacity-70">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>شنبه تا پنجشنبه: ۹ صبح تا ۹ شب<br>جمعه: ۱۰ صبح تا ۶ عصر</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Trust Badges -->
        <div class="divider my-8"></div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div class="p-4 rounded-lg bg-base-100">
                <div class="text-3xl mb-2">🚚</div>
                <div class="font-bold text-sm">ارسال سریع</div>
                <div class="text-xs opacity-70">تحویل ۲۴ ساعته</div>
            </div>
            <div class="p-4 rounded-lg bg-base-100">
                <div class="text-3xl mb-2">💯</div>
                <div class="font-bold text-sm">ضمانت اصالت</div>
                <div class="text-xs opacity-70">تضمین کیفیت</div>
            </div>
            <div class="p-4 rounded-lg bg-base-100">
                <div class="text-3xl mb-2">🔄</div>
                <div class="font-bold text-sm">بازگشت ۷ روزه</div>
                <div class="text-xs opacity-70">تضمین بازگشت</div>
            </div>
            <div class="p-4 rounded-lg bg-base-100">
                <div class="text-3xl mb-2">💳</div>
                <div class="font-bold text-sm">پرداخت امن</div>
                <div class="text-xs opacity-70">درگاه معتبر</div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="divider my-8"></div>

        <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm opacity-70">
            <p>© {{ date('Y') }} تمامی حقوق برای فروشگاه اکسسوری محفوظ است.</p>
            <div class="flex gap-4">
                <a href="#" class="link link-hover">قوانین</a>
                <a href="#" class="link link-hover">حریم خصوصی</a>
                <a href="#" class="link link-hover">نقشه سایت</a>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button class="btn btn-circle btn-primary fixed bottom-4 left-4 shadow-lg z-50"
        x-data="{ show: false }"
        x-init="window.addEventListener('scroll', () => { show = window.scrollY > 500 })"
        x-show="show"
        x-transition
        @click="window.scrollTo({top: 0, behavior: 'smooth'})">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
    </svg>
</button>

<!-- Livewire Scripts -->
@livewireScripts

<script>
    // Cart counter update listener
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('cart-updated', (event) => {
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                cartCount.textContent = event.count || 0;
            }
        });
    });
</script>

@stack('scripts')
</body>
</html>
