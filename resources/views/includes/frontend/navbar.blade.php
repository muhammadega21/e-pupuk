<nav class="flex gap-x-10 items-center justify-between h-20 w-full border-b border-gray-300 px-5 md:px-20 bg-white">
    <h1 class="text-nowrap font-bold text-2xl text-[var(--color-primary)]">Pupuk Sriwindo</h1>
    <div class="search-box w-full flex">
        <input type="text" placeholder="Search..."
            class="px-4 py-2 w-full rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]">
        <button class="ml-2 px-4 py-2 bg-[var(--color-primary)] text-white rounded-md">Search</button>
    </div>
    <div class="flex gap-x-5 items-center">
        @auth
            <div class="dropdown dropdown-center">
                <div tabindex="0" role="button" class="">
                    <div class="avatar cursor-pointer">
                        <div class="w-10 rounded-full">
                            <img src="{{ Avatar::create(Auth::user()->user_data->nama)->toBase64() }}" />
                        </div>
                    </div>
                </div>
                <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm mt-1">
                    <li><a href="{{ route('dashboard.home') }}">Dashboard</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="cursor-pointer">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        @endauth
        @guest
            <a href="{{ route('login') }}" class="text-[var(--color-primary)]">LOGIN/REGISTER</a>
        @endguest
        <div class="cart-icon">
            <i class="fa-solid fa-cart-shopping "></i>
        </div>
    </div>
</nav>
