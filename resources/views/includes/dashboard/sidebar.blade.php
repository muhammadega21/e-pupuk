<section id="sidebar" class="bg-white w-0 md:w-[250px] border-r border-gray-200 h-screen flex flex-col justify-between">
    <div>
        <div class="brand flex gap-x-2 justify-center items-center h-16 text-2xl text-blue-600"> <i
                class="fa-solid fa-cart-shopping"></i>
            <h1 class="font-bold">UD Sriwindo</h1>
        </div>
        <div class="side-menu mt-4 ">
            <ul class="space-y-1 text-gray-700 px-3">
                <li> <a href="{{ route('dashboard.home') }}"
                        class="{{ Route::is('dashboard.home') ? 'active' : '' }} flex items-center gap-x-3 px-4 py-2 rounded-md">
                        <i class="fa-solid fa-gauge"></i> <span>Dashboard</span> </a> </li>
                @if (Auth::user()->hasRole(['admin', 'karyawan']))
                    <li class="px-4 mt-4 text-gray-500 uppercase text-xs font-semibold">Master Data</li>
                    @if (Auth::user()->hasRole(['admin']))
                        <li> <a href="#" class="flex items-center gap-x-3 px-4 py-2  rounded-md"> <i
                                    class="fa-solid fa-user-shield"></i> <span>Role</span> </a> </li>
                        <li> <a href="#" class="flex items-center gap-x-3 px-4 py-2  rounded-md"> <i
                                    class="fa-solid fa-users"></i> <span>Pengguna</span> </a> </li>
                    @endif
                    <li> <a href="#" class="flex items-center gap-x-3 px-4 py-2  rounded-md"> <i
                                class="fa-solid fa-user"></i> <span>Customer</span> </a> </li>
                    <li class="px-4 mt-4 text-gray-500 uppercase text-xs font-semibold">Produk & Produksi</li>
                    <li> <a href="#" class="flex items-center gap-x-3 px-4 py-2  rounded-md"> <i
                                class="fa-solid fa-seedling"></i> <span>Data Pupuk</span> </a> </li>
                    <li> <a href="#" class="flex items-center gap-x-3 px-4 py-2  rounded-md"> <i
                                class="fa-solid fa-industry"></i> <span>Produksi</span> </a> </li>
                    <li class="px-4 mt-4 text-gray-500 uppercase text-xs font-semibold">Transaksi</li>
                    <li> <a href="#" class="flex items-center gap-x-3 px-4 py-2  rounded-md"> <i
                                class="fa-solid fa-box"></i> <span>Pesanan</span> </a> </li>
                    <li> <a href="#" class="flex items-center gap-x-3 px-4 py-2  rounded-md"> <i
                                class="fa-solid fa-credit-card"></i> <span>Pembayaran</span> </a> </li>
                    <li> <a href="#" class="flex items-center gap-x-3 px-4 py-2  rounded-md"> <i
                                class="fa-solid fa-truck"></i> <span>Pengiriman</span> </a> </li>
                    <li class="px-4 mt-4 text-gray-500 uppercase text-xs font-semibold">Laporan</li>
                    <li> <a href="#" class="flex items-center gap-x-3 px-4 py-2  rounded-md"> <i
                                class="fa-solid fa-file-invoice-dollar"></i> <span>Penjualan</span> </a> </li>
                    <li> <a href="#" class="flex items-center gap-x-3 px-4 py-2  rounded-md"> <i
                                class="fa-solid fa-chart-line"></i> <span>Produksi</span> </a> </li>
                    <li> <a href="#" class="flex items-center gap-x-3 px-4 py-2  rounded-md"> <i
                                class="fa-solid fa-cubes"></i> <span>Stok</span> </a> </li>
                    <li class="px-4 mt-4 text-gray-500 uppercase text-xs font-semibold">Pengaturan</li>
                @endif
                <li> <a href="#" class="flex items-center gap-x-3 px-4 py-2  rounded-md"> <i
                            class="fa-solid fa-gear"></i> <span>Profil</span> </a> </li>
            </ul>
        </div>
    </div>
    <div class="p-4 mt-3 border-t border-gray-200">
        <form method="POST" action="/logout">
            @csrf
            <button type="submit"
                class="flex items-center gap-x-3 w-full text-left text-red-600 hover:bg-red-50 px-4 py-2 rounded-md transition-all cursor-pointer">
                <i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span>
            </button>
        </form>
    </div>
</section>
