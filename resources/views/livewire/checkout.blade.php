<div class="min-h-screen bg-gradient-to-br from-base-200 to-base-300 py-8 px-4">
    <div class="max-w-5xl mx-auto">

        <!-- Header -->
        <div class="text-center mb-8 animate__animated animate__fadeInDown">
            <h1 class="text-4xl font-bold mb-2 bg-gradient-to-l from-primary to-secondary bg-clip-text text-transparent mb-5 ">
                🛍️ تکمیل خرید
            </h1>
            <p class="text-base-content/70 text-lg">خرید خود را در چند مرحله ساده تکمیل کنید</p>
        </div>

        <!-- Steps Indicator -->
        <div class="mb-10 animate__animated animate__fadeIn mt-10">
            <ul class="steps steps-horizontal w-full">
                <li class="step cursor-pointer transition-all duration-300
                    {{ $currentStep >= 1 ? 'step-primary' : '' }}"
                    @click="$wire.currentStep = 1">
                    <span class="hidden sm:inline">سبد خرید</span>
                </li>
                <li class="step cursor-pointer transition-all duration-300
                    {{ $currentStep >= 2 ? 'step-primary' : '' }}"
                    @click="$wire.currentStep = 2">
                    <span class="hidden sm:inline">اطلاعات مشتری</span>
                </li>
                <li class="step cursor-pointer transition-all duration-300
                    {{ $currentStep >= 3 ? 'step-primary' : '' }}"
                    @click="$wire.currentStep = 3">
                    <span class="hidden sm:inline">پرداخت</span>
                </li>
                <li class="step cursor-pointer transition-all duration-300
                    {{ $currentStep >= 4 ? 'step-primary' : '' }}"
                    @click="$wire.currentStep = 4">
                    <span class="hidden sm:inline">نتیجه</span>
                </li>
            </ul>
        </div>

        <!-- Step Content with Animation -->
        <div x-data="{
            animation: 'animate__fadeInRight',
            currentStep: @entangle('currentStep'),
            prevStep: {{ $currentStep }}
        }"
             x-init="$watch('currentStep', (value, oldValue) => {
            if (value > oldValue) {
                animation = 'animate__fadeInRight';
            } else {
                animation = 'animate__fadeInLeft';
            }
        })">
            @switch($currentStep)

                @case(1)
                    <!-- Step 1: Cart -->
                    <div
                        x-transition:enter="transition-all duration-500 ease-out"
                        x-transition:enter-start="opacity-0 translate-x-8"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition-all duration-300 ease-in"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-8">
                        @livewire('checkout.cart-step')
                    </div>
                    @break

                @case(2)
                    <!-- Step 2: Customer Info -->
                    <div
                        x-transition:enter="transition-all duration-500 ease-out"
                        x-transition:enter-start="opacity-0 translate-x-8"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition-all duration-300 ease-in"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-8">
                        @livewire('checkout.customer-info-step')
                    </div>
                    @break

                @case(3)
                    <!-- Step 3: Shipping & Payment -->
                    <div
                        x-transition:enter="transition-all duration-500 ease-out"
                        x-transition:enter-start="opacity-0 translate-x-8"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition-all duration-300 ease-in"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 -translate-x-8">
                        @livewire('checkout.shipping-payment-step')
                    </div>
                    @break

                @case(4)
                    <div
                        x-transition:enter="transition-all duration-500 ease-out"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100">
                        @livewire('checkout.result-step')
                    </div>
                    @break

            @endswitch

        </div>
        <!-- Tips Section -->
        @if($currentStep < 4)
            <div class="mt-8 text-center animate__animated animate__fadeInUp">
                <div class="alert alert-info max-w-2xl mx-auto shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         class="stroke-current shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>
                    @if($currentStep === 1)
                            لطفاً سبد خرید خود را بررسی و تایید کنید
                        @elseif($currentStep === 2)
                            اطلاعات تماس و آدرس خود را با دقت وارد کنید
                        @elseif($currentStep === 3)
                            روش ارسال و درگاه پرداخت مورد نظر را انتخاب کنید
                        @endif
                </span>
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endpush
