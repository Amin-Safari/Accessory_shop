<div>
    @guest

        <button
            class="btn btn-primary"
            wire:click="$dispatch('open-auth')">

            ورود | ثبت نام

        </button>

    @endguest

    @auth

        <div class="dropdown dropdown-end lg:dropdown-bottom">

            <label tabindex="0" class="btn">

                {{ auth()->user()->phone }}

            </label>

            <ul
                tabindex="0"
                class="dropdown-content menu bg-base-100 rounded-box w-52 shadow">

                <li>

                    <a>حساب کاربری</a>

                </li>

                <li>

                    <a>سفارش‌ها</a>

                </li>

                <li>

                    <button wire:click="logout">

                        خروج

                    </button>

                </li>

            </ul>

        </div>

    @endauth
</div>
