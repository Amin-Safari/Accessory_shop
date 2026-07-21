<div class="card bg-base-100 shadow-2xl">
    <div class="card-body">
        <h2 class="card-title text-3xl mb-6">
            <span class="text-4xl">👤</span>
            اطلاعات مشتری
        </h2>

        <form wire:submit="saveAndContinue">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">نام <span class="text-error">*</span></span>
                    </label>
                    <input type="text"
                           wire:model="name"
                           placeholder="نام و نام خانوادگی کامل خود را وارد کنید"
                           class="input input-bordered w-full transition-all duration-300 focus:input-primary" />
                    @error('name')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">شماره تماس <span class="text-error">*</span></span>
                    </label>
                    <input type="tel"
                           wire:model="phone"
                           placeholder="۰۹۱۲۳۴۵۶۷۸۹"
                           class="input input-bordered w-full text-left" dir="ltr" readonly/>
                    @error('phone')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <!-- Province -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">استان <span class="text-error">*</span></span>
                    </label>
                    <select wire:model.live="province" class="select select-bordered w-full transition-all duration-300 focus:select-primary">
                        <option value="">استان خود را انتخاب کنید</option>
                        @foreach($provinces as $prov)
                            <option value="{{ $prov['id'] }}">{{ $prov['name'] }}</option>
                        @endforeach
                    </select>
                    @error('province')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <!-- City -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">شهر <span class="text-error">*</span></span>
                    </label>
                    <select wire:model="city" class="select select-bordered w-full transition-all duration-300 focus:select-primary">
                        <option value="">شهر خود را انتخاب کنید</option>
                        @foreach($cities as $cit)
                            <option value="{{ $cit['id'] }}">{{ $cit['name'] }}</option>
                        @endforeach
                    </select>
                    @error('city')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <!-- Postal Code -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">کد پستی <span class="text-error">*</span></span>
                    </label>
                    <input type="text"
                           wire:model="postal_code"
                           placeholder="کد پستی ۱۰ رقمی"
                           class="input input-bordered w-full text-left" dir="ltr"
                           maxlength="10" />
                    @error('postal_code')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <!-- Address -->
                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text font-medium">آدرس <span class="text-error">*</span></span>
                    </label>
                    <textarea wire:model="address"
                              placeholder="آدرس کامل خود را وارد کنید"
                              class="textarea textarea-bordered w-full transition-all duration-300 focus:textarea-primary"
                              rows="3"></textarea>
                    @error('address')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text font-medium">یادداشت سفارش</span>
                    </label>
                    <textarea wire:model="notes"
                              placeholder="توضیحات اضافی برای سفارش خود بنویسید"
                              class="textarea textarea-bordered w-full transition-all duration-300 focus:textarea-primary"
                              rows="2"></textarea>
                </div>
            </div>

            <div class="card-actions justify-between mt-8">
                <button type="button"
                        class="btn btn-ghost btn-lg group"
                        wire:click="$dispatch('previousStep')">
                    <span class="inline-block transition-transform group-hover:-translate-x-1">→</span>
                    بازگشت به سبد خرید
                </button>

                <button type="submit"
                        class="btn btn-primary btn-lg group hover:scale-105 transition-all duration-300"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>
                        ادامه به پرداخت
                        <span class="inline-block transition-transform group-hover:translate-x-1">←</span>
                    </span>
                    <span wire:loading class="loading loading-spinner loading-md"></span>
                </button>
            </div>
        </form>
    </div>
</div>
