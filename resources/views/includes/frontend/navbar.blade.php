<nav class="h-max md:h-20 w-full border-b border-gray-300 p-5 md:px-20 bg-white">
    <div class="flex gap-x-10 items-center justify-between h-full w-full">
        <a href="{{ route('home') }}" class="text-nowrap font-bold text-2xl text-primary">Agro Lestarindo</a>
        <div class="flex items-center">
            <div class="nav-menu border-r-2 border-gray-200 pe-4 hidden md:flex gap-x-5">
                <a href="{{ route('home') }}"
                    class="{{ Route::is(['home', 'produk.*']) ? 'text-primary border-b border-primary' : '' }} hover:text-primary transition duration-150">Beranda</a>
                <a href="{{ route('featured') }}"
                    class="{{ Route::is('featured') ? 'text-primary border-b border-primary' : '' }} hover:text-primary transition duration-150">Produk
                    Unggulan</a>
                <a href="{{ route('about') }}"
                    class="{{ Route::is('about') ? 'text-primary border-b border-primary' : '' }} hover:text-primary transition duration-150">Tentang
                    Kami</a>
            </div>
            <div class="flex gap-x-5 items-end ms-4">
                @auth
                    @php
                        $cart_count =
                            \App\Models\Pesanan::where('created_by', Auth::user()->user_id)
                                ->where('channel', 'cart')
                                ->first()
                                ?->detailPesanan?->count() ?? 0;
                    @endphp
                    <div class="cart-icon">
                        <a href="{{ route('cart') }}" class="relative">
                            @if ($cart_count > 0)
                                <span
                                    class="absolute -top-3 -right-2 bg-primary text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                                    {{ $cart_count }}
                                </span>
                            @endif
                            <i class="fa-solid fa-cart-shopping text-xl text-gray-600"></i>
                        </a>
                    </div>
                    <div class="dropdown dropdown-center">
                        <div tabindex="0" role="button" class="">
                            <div class="avatar cursor-pointer">
                                <div class="w-10 rounded-full">
                                    <img src="{{ Avatar::create(Auth::user()->user_data->nama)->toBase64() }}" />
                                </div>
                            </div>
                        </div>
                        <ul tabindex="-1"
                            class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm mt-1">
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
                    <a href="{{ route('login') }}" class="text-primary">LOGIN/REGISTER</a>
                @endguest

            </div>
        </div>
    </div>
    <div class="w-full md:w-1/2 md:hidden mt-4">
        {{-- <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-primary">Menu</label>
            <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                <li><a href="">Beranda</a></li>
                <li><a href="">Produk Unggulan</a></li>
                <li><a href="">Tentang Kami</a></li>
            </ul>
        </div> --}}
        <div class="nav-menu flex justify-center gap-x-3">
            <a href="{{ route('home') }}"
                class="{{ Route::is('home') ? 'text-primary border-b border-primary' : '' }} hover:text-primary transition duration-150">Beranda</a>
            <a href="{{ route('featured') }}"
                class="{{ Route::is('featured') ? 'text-primary border-b border-primary' : '' }} hover:text-primary transition duration-150">Produk
                Unggulan</a>
            <a href="{{ route('about') }}"
                class="{{ Route::is('about') ? 'text-primary border-b border-primary' : '' }} hover:text-primary transition duration-150">Tentang
                Kami</a>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cari elemen dropdown
        const dropdown = document.querySelector('.dropdown');
        const dropdownContent = dropdown?.querySelector('.dropdown-content');

        // Tutup dropdown saat item di dalamnya diklik
        if (dropdownContent) {
            dropdownContent.querySelectorAll('a, button').forEach(el => {
                el.addEventListener('click', () => {
                    dropdownContent.classList.add('hidden');
                    setTimeout(() => {
                        dropdownContent.classList.remove('hidden');
                    }, 300); // reset agar dropdown bisa dibuka lagi nanti
                });
            });
        }
    });
</script>
