<div>
    <div class="card bg-base-100 shadow border-2 border-error/20">
        <div class="card-body">
            <h2 class="card-title text-lg text-error mb-1">حذف حساب کاربری</h2>

            <div class="space-y-4">
                <div class="flex items-start gap-3 text-sm bg-error/5 rounded-lg p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-error shrink-0 mt-0.5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <div>
                        <p class="text-error font-medium">هشدار: این عملیات غیرقابل بازگشت است!</p>
                        <p class="text-base-content/60 mt-1 leading-relaxed">
                            با حذف حساب کاربری، تمام اطلاعات شما شامل سفارش‌ها، علاقه‌مندی‌ها و اطلاعات شخصی
                            حذف خواهد شد و قابل بازیابی نیست.
                        </p>
                    </div>
                </div>

                <button wire:click="openModal" class="btn btn-outline btn-error w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    حذف حساب کاربری
                </button>
            </div>
        </div>
    </div>

    {{-- Delete Account Modal --}}
        {{-- دکمه برای باز کردن مودال --}}

        {{-- مودال --}}
    <dialog class="modal" @if($showModal) open @endif>
        <div class="modal-box">

            <!-- دکمه بستن (دقیقاً مثل کد دوم) -->
            <form method="dialog">
                <button type="button" wire:click="closeModal"
                        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <br>

            {{-- پیام موفقیت --}}
            @if(session()->has('message'))
                <div class="alert alert-success alert-sm mb-4">
                    {{ session('message') }}
                </div>
            @endif

            {{-- مرحله ۱: تایید با کلمه "حذف" --}}
                <form wire:submit="deleteAccount" class="space-y-4">
                    <!-- آیکون هشدار -->
                    <div class="text-center">
                        <div class="mx-auto w-16 h-16 rounded-full bg-error/10 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <p class="font-bold text-2xl text-error">حذف حساب کاربری</p>
                        <p class="text-sm text-base-content/60 mt-2">
                            آیا از حذف حساب خود اطمینان دارید؟ این عملیات غیرقابل بازگشت است.
                        </p>
                    </div>

                    <!-- ورودی تایید -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">برای تایید، کلمه "حذف" را وارد کنید</span>
                        </label>
                        <input type="text"
                               wire:model="confirmation_text"
                               class="input input-bordered w-full text-center text-lg font-bold @error('confirmation_text') input-error @enderror"
                               placeholder="حذف"
                               autocomplete="off" />
                        @error('confirmation_text')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                        @enderror
                    </div>

                    <!-- لیست موارد حذف -->
                    <div class="bg-error/5 rounded-xl p-4">
                        <h4 class="font-medium text-sm mb-3">با حذف حساب، موارد زیر حذف خواهند شد:</h4>
                        <ul class="text-sm text-base-content/60 space-y-2 list-disc list-inside">
                            <li>تمام سفارش‌ها و تاریخچه خرید</li>
                            <li>لیست علاقه‌مندی‌ها</li>
                            <li>اطلاعات شخصی و آدرس‌ها</li>
                            <li>اعلان‌ها و پیام‌ها</li>
                        </ul>
                    </div>

                    <!-- دکمه‌های اقدام -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit" class="btn btn-error flex-1 p-1" wire:loading.attr="disabled">
                            <span wire:loading.remove>بله، حساب من حذف شود</span>
                            <span wire:loading class="loading loading-spinner"></span>
                        </button>
                        <button type="button" wire:click="closeModal" class="btn btn-ghost flex-1 p-1">
                            انصراف
                        </button>
                    </div>
                </form>
        </div>

        <!-- بک‌دراپ (دقیقاً مثل کد دوم) -->
        <form method="dialog" class="modal-backdrop">
            <button type="button" wire:click="closeModal">close</button>
        </form>
    </dialog>

    {{-- Delete Account Modal --}}
    {{--    @if($showModal)--}}
    {{--        <div class="fixed inset-0 z-50 overflow-y-auto">--}}
    {{--            <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">--}}
    {{--                <!-- Backdrop -->--}}
    {{--                <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="closeModal"></div>--}}

    {{--                <!-- Modal Content -->--}}
    {{--                <div class="relative bg-base-100 rounded-2xl shadow-xl--}}
    {{--                        w-full max-w-[95vw] sm:max-w-md md:max-w-lg--}}
    {{--                        p-5 sm:p-6 mx-auto">--}}

    {{--                    <!-- Close Button -->--}}
    {{--                    <button wire:click="closeModal"--}}
    {{--                            class="absolute left-4 top-4 text-base-content/40 hover:text-base-content z-10">--}}
    {{--                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
    {{--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />--}}
    {{--                        </svg>--}}
    {{--                    </button>--}}

    {{--                    <!-- Modal Header -->--}}
    {{--                    <div class="mb-8 text-center">--}}
    {{--                        <div class="mx-auto w-16 h-16 rounded-full bg-error/10 flex items-center justify-center mb-4">--}}
    {{--                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
    {{--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />--}}
    {{--                            </svg>--}}
    {{--                        </div>--}}
    {{--                        <h3 class="text-xl font-bold text-error">حذف حساب کاربری</h3>--}}
    {{--                        <p class="text-sm text-base-content/60 mt-2 leading-relaxed">--}}
    {{--                            آیا از حذف حساب کاربری خود اطمینان دارید؟<br>--}}
    {{--                            این عملیات غیرقابل بازگشت است.--}}
    {{--                        </p>--}}
    {{--                    </div>--}}

    {{--                    <!-- Confirmation Form -->--}}
    {{--                    <form wire:submit="deleteAccount">--}}
    {{--                        <div class="form-control mb-6">--}}
    {{--                            <label class="label">--}}
    {{--                                <span class="label-text font-medium">برای تایید، کلمه "حذف" را وارد کنید</span>--}}
    {{--                            </label>--}}
    {{--                            <input type="text"--}}
    {{--                                   wire:model="confirmation_text"--}}
    {{--                                   class="input input-bordered w-full text-center text-lg font-bold @error('confirmation_text') input-error @enderror"--}}
    {{--                                   placeholder="حذف"--}}
    {{--                                   autocomplete="off" />--}}
    {{--                            @error('confirmation_text')--}}
    {{--                            <label class="label">--}}
    {{--                                <span class="label-text-alt text-error">{{ $message }}</span>--}}
    {{--                            </label>--}}
    {{--                            @enderror--}}
    {{--                        </div>--}}

    {{--                        <!-- Consequences -->--}}
    {{--                        <div class="bg-error/5 rounded-xl p-4 mb-6">--}}
    {{--                            <h4 class="font-medium text-sm mb-3">با حذف حساب، موارد زیر حذف خواهند شد:</h4>--}}
    {{--                            <ul class="text-sm text-base-content/60 space-y-2 list-disc list-inside">--}}
    {{--                                <li>تمام سفارش‌ها و تاریخچه خرید</li>--}}
    {{--                                <li>لیست علاقه‌مندی‌ها</li>--}}
    {{--                                <li>اطلاعات شخصی و آدرس‌ها</li>--}}
    {{--                                <li>اعلان‌ها و پیام‌ها</li>--}}
    {{--                            </ul>--}}
    {{--                        </div>--}}

    {{--                        <!-- Actions -->--}}
    {{--                        <div class="flex flex-col sm:flex-row gap-3">--}}
    {{--                            <button type="submit" class="btn btn-error flex-1">--}}
    {{--                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
    {{--                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />--}}
    {{--                                </svg>--}}
    {{--                                بله، حساب من حذف شود--}}
    {{--                            </button>--}}
    {{--                            <button type="button" wire:click="closeModal" class="btn btn-ghost flex-1">--}}
    {{--                                انصراف--}}
    {{--                            </button>--}}
    {{--                        </div>--}}
    {{--                    </form>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    @endif--}}
</div>
