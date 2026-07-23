<div class="card bg-base-100 shadow">
    <div class="card-body">
        <h2 class="card-title mb-6">ویرایش اطلاعات شخصی</h2>

        @if(session('profile_updated'))
            <div x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 3000)"
                 class="alert alert-success mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('profile_updated') }}</span>
                <button @click="show = false" class="btn btn-ghost btn-sm btn-circle">✕</button>
            </div>
        @endif

        <form wire:submit="updateProfile">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Name --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">نام و نام خانوادگی</span>
                        <span class="label-text-alt text-error">*</span>
                    </label>
                    <input type="text"
                           wire:model="name"
                           class="input input-bordered w-full @error('name') input-error @enderror"
                           placeholder="نام و نام خانوادگی خود را وارد کنید" />
                    @error('name')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                {{-- Phone (Read Only) --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">شماره موبایل</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="text"
                               value="{{ auth()->user()->phone }}"
                               class="input input-bordered w-full bg-base-200"
                               disabled
                               readonly />
                        <div class="tooltip tooltip-end md:tooltip-right md:tooltip-center" data-tip="برای تغییر شماره موبایل از بخش کناری استفاده کنید">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <label class="label">
                        <span class="label-text-alt text-base-content/60">شماره موبایل قابل تغییر در این بخش نیست</span>
                    </label>
                </div>

                {{-- Province --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">استان</span>
                    </label>
                    <select wire:model.live="province_id"
                            class="select select-bordered w-full @error('province_id') select-error @enderror">
                        <option value="">انتخاب استان</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                        @endforeach
                    </select>
                    @error('province_id')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                {{-- City --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">شهر</span>
                    </label>
                    <select wire:model="city_id"
                            class="select select-bordered w-full @error('city_id') select-error @enderror"
                            @if(!$province_id) disabled @endif>
                        <option value="">انتخاب شهر</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                    @error('city_id')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                    @enderror
                    @if(!$province_id)
                        <label class="label">
                            <span class="label-text-alt text-base-content/60">ابتدا استان را انتخاب کنید</span>
                        </label>
                    @endif
                </div>

                {{-- Postal Code --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">کد پستی</span>
                    </label>
                    <input type="text"
                           wire:model="postal_code"
                           class="input input-bordered w-full @error('postal_code') input-error @enderror"
                           placeholder="کد پستی ۱۰ رقمی"
                           maxlength="10" />
                    @error('postal_code')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                {{-- Address --}}
                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text font-medium">آدرس</span>
                    </label>
                    <textarea wire:model="address"
                              class="textarea textarea-bordered w-full h-24 @error('address') textarea-error @enderror"
                              placeholder="آدرس کامل خود را وارد کنید"></textarea>
                    @error('address')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="btn btn-primary w-full md:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    ذخیره تغییرات
                </button>
            </div>
        </form>
    </div>
</div>
