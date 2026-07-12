<div class="dropdown dropdown-end">

    <div tabindex="0" role="button" class="btn btn-ghost btn-circle">

        <div class="indicator">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="1.8"
                 stroke="currentColor"
                 class="size-6">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M2.25 3h1.386a1.5 1.5 0 011.464 1.175L5.383 6h13.867a.75.75 0 01.73.923l-1.5 6A.75.75 0 0117.75 13.5H6.75a.75.75 0 01-.73-.577L4.114 4.5H2.25" />

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M8.25 20.25a.75.75 0 100-1.5.75.75 0 000 1.5zm9 0a.75.75 0 100-1.5.75.75 0 000 1.5z"/>

            </svg>

            @if($this->count)

                <span class="badge badge-primary badge-sm indicator-item">

                    {{ $this->count }}

                </span>

            @endif

        </div>

    </div>

    <div tabindex="0"
         class="dropdown-content mt-3 w-60 sm:w-96 md:w-96 lg:w-96 rounded-box border border-base-300 bg-base-100 shadow-2xl">

        <div class="p-4 border-b">

            <div class="flex justify-between items-center">

                <h3 class="font-bold">

                    سبد خرید

                </h3>

                <span class="badge badge-outline">

                    {{ $this->count }} کالا

                </span>

            </div>

        </div>

        @if(count($items))

            <div class="max-h-96 overflow-y-auto">

                @foreach($items as $id => $item)

                    <div class="flex gap-3 p-4 hover:bg-base-200">

                        <img
                            src="{{ $item['image'] }}"
                            class="size-16 rounded-lg object-cover">

                        <div class="flex-1">

                            <p class="font-medium line-clamp-2">

                                {{ $item['title'] }}

                            </p>

                            <p class="text-sm opacity-60 mt-1">

                                {{ number_format($item['price'] * (1-( 0.01 * $item['discount']))) }} تومان

                            </p>

                            <p class="text-xs mt-1">

                                تعداد: {{ $item['qty'] }}

                            </p>

                        </div>

                        <button
                            wire:click="remove('{{ $id }}')"
                            class="btn btn-ghost btn-xs text-error">

                            ✕

                        </button>

                    </div>

                @endforeach

            </div>

            <div class="border-t p-4 space-y-4">

                <div class="flex justify-between font-bold">

                    <span>

                        جمع کل

                    </span>

                    <span>

                        {{ number_format($this->total) }}

                        تومان

                    </span>

                </div>

                <div class="grid grid-cols-2 gap-2">

                    <a
                        href="#"
                        class="btn btn-outline">

                        مشاهده سبد

                    </a>

                    <a
                        href="#"
                        class="btn btn-primary">

                        تسویه حساب

                    </a>

                </div>

            </div>

        @else

            <div class="p-10 text-center">

                <div class="text-5xl mb-4">

                    🛒

                </div>

                <h3 class="font-bold">

                    سبد خرید خالی است

                </h3>

                <p class="text-sm opacity-70 mt-2">

                    هنوز محصولی اضافه نکرده‌اید.

                </p>

            </div>

        @endif

    </div>

</div>
