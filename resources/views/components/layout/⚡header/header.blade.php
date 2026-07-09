<div>
    <div class="drawer lg:drawer-close">

        <input id="mobile-drawer" type="checkbox" class="drawer-toggle"/>

        <div class="drawer-content">

            <div class="navbar bg-base-100 shadow rounded-xl">


                <div class="navbar-start">

                    <label
                        for="mobile-drawer"
                        class="btn btn-ghost lg:hidden">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             class="size-6">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>

                        </svg>

                    </label>

                    <a
                        href="{{ route('home') }}"
                        class="btn btn-ghost text-xl">

                        فروشگاه

                    </a>

                </div>


                <div class="navbar-center hidden lg:flex">

                    <ul class="menu menu-horizontal gap-2">

                        <li><a>محصولات</a></li>

                        <li><a>وبلاگ</a></li>

                        <li><a>درباره ما</a></li>

                        <li><a>تماس با ما</a></li>

                    </ul>

                </div>


                <div class="navbar-end gap-2">

                    @livewire('layout.theme-switcher')

                    @livewire('cart.dropdown')

                    @livewire('user.menu')

                </div>

            </div>

        </div>

        {{-- Drawer --}}

        <div class="drawer-side z-50">

            <label
                for="mobile-drawer"
                class="drawer-overlay">
            </label>

            <ul class="menu p-4 w-72 min-h-full bg-base-100">

                <li class="menu-title">

                    فروشگاه

                </li>

                <li><a href="{{ route('products') }}">محصولات</a></li>

                <li><a>وبلاگ</a></li>

                <li><a>درباره ما</a></li>

                <li><a>تماس با ما</a></li>
                <li>
                    <div class="divider"></div>
                </li>


                <li>
                    @livewire('user.menu')
                </li>


            </ul>

        </div>
    </div>
    @livewire('auth.modal')

</div>
