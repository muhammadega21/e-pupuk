<section id="sidebar"
    class="bg-white fixed top-0 left-0 h-full w-[250px] border-r border-gray-200 flex flex-col justify-between transform transition-transform duration-300 -translate-x-full md:translate-x-0 z-40">
    <div class="mt-4">
        <div class="brand flex items-center gap-x-2 justify-center text-[--color-primary]">
            <i class="fa-solid fa-cart-shopping text-2xl"></i>
            <h1 class="font-bold text-2xl">UD Sriwindo</h1>
        </div>
        <div class="side-menu mt-4">
            <ul class="space-y-1 text-gray-700 px-3">
                <li>
                    <a href="{{ route('dashboard.home') }}"
                        class="{{ Route::is('dashboard.home') ? 'active' : '' }} flex items-center gap-x-3 px-4 py-2 rounded-md">
                        <i class="fa-solid fa-gauge"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @if (Auth::user()->hasRole(['admin', 'karyawan']))
                    <li class="px-4 mt-4 text-gray-500 uppercase text-xs font-semibold">Master Data</li>
                    @if (Auth::user()->hasRole(['admin']))
                        <li>
                            <a href="{{ route('dashboard.master-data.role') }}"
                                class="{{ Route::is('dashboard.master-data.role') ? 'active' : '' }} flex items-center gap-x-3 px-4 py-2 rounded-md">
                                <i class="fa-solid fa-user-shield"></i>
                                <span>Role</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.master-data.karyawan') }}"
                                class="{{ Route::is('dashboard.master-data.karyawan') ? 'active' : '' }} flex items-center gap-x-3 px-4 py-2 rounded-md">
                                <i class="fa-solid fa-user"></i>
                                <span>Karyawan</span>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('dashboard.master-data.pelanggan') }}"
                            class="{{ Route::is('dashboard.master-data.pelanggan') ? 'active' : '' }} flex items-center gap-x-3 px-4 py-2 rounded-md">
                            <i class="fa-solid fa-users"></i>
                            <span>Pelanggan</span>
                        </a>
                    </li>
                    <li class="px-4 mt-4 text-gray-500 uppercase text-xs font-semibold">Produk & Produksi</li>
                    <li>
                        <a href="{{ route('dashboard.master-data.pupuk') }}"
                            class="{{ Route::is('dashboard.master-data.pupuk') ? 'active' : '' }} flex items-center gap-x-3 px-4 py-2 rounded-md">
                            <i class="fa-solid fa-seedling"></i>
                            <span>Data Pupuk</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard.master-data.produksi') }}"
                            class="{{ Route::is('dashboard.master-data.produksi') ? 'active' : '' }} flex items-center gap-x-3 px-4 py-2 rounded-md">
                            <i class="fa-solid fa-industry"></i>
                            <span>Produksi</span>
                        </a>
                    </li>
                    <li class="px-4 mt-4 text-gray-500 uppercase text-xs font-semibold">Transaksi</li>
                @endif
                <li>
                    <a href="{{ route('dashboard.transaksi.pesanan') }}"
                        class="{{ Route::is(['dashboard.transaksi.pesanan', 'dashboard.transaksi.detail-pesanan']) ? 'active' : '' }} flex items-center gap-x-3 px-4 py-2 rounded-md">
                        <i class="fa-solid fa-box"></i>
                        <span>Pesanan</span>
                    </a>
                </li>
                <li class="px-4 mt-4 text-gray-500 uppercase text-xs font-semibold">Pengaturan</li>
                <li>
                    <a href="{{ route('dashboard.profile') }}"
                        class="{{ Route::is('dashboard.profile') ? 'active' : '' }} flex items-center gap-x-3 px-4 py-2 rounded-md">
                        <i class="fa-solid fa-gear"></i>
                        <span>Profil</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="p-4 mt-3 border-t border-gray-200">
        <form method="POST" action="/logout">
            @csrf
            <button type="submit"
                class="flex items-center gap-x-3 w-full text-left text-red-600 hover:bg-red-50 px-4 py-2 rounded-md transition-all cursor-pointer">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</section>
