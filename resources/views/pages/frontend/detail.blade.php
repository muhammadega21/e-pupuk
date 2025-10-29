<x-frontend-layout :title="$title">
    <h1 class="font-bold pt-5 text-sm block md:hidden"><span class="font-normal">Beranda / Produk</span> /
        {{ $pupuk->nama }}</h1>
    <section class="pt-10 flex flex-col md:flex-row gap-10">
        <div class="w-full md:w-1/3">
            <img src="{{ asset('/storage/' . $pupuk->gambar) }}" alt="{{ $pupuk->nama }}">
        </div>
        <div class="w-full md:w-2/3">
            <h1 class="font-bold text-sm hidden md:block"><span class="font-normal">Beranda / Produk</span> /
                {{ $pupuk->nama }}</h1>
            <div class="bg-gray-50 w-full p-5 rounded-md mt-3">
                <div class="border-b border-dashed border-gray-300 mb-4">
                    <h2 class="font-bold text-3xl mb-2">{{ $pupuk->nama }} ({{ $pupuk->berat }} Kg)</h2>
                    <p class="text-2xl font-bold text-primary mb-4">Rp {{ number_format($pupuk->harga, 2, ',', '.') }}
                    </p>
                </div>
                <div>
                    <p class="mb-2 font-semibold"><i class="fa-solid fa-check text-primary"></i> Stok
                        {{ $pupuk->stok }}</p>
                    <form action="{{ route('cart.store') }}" method="POST" class="flex items-center gap-3 mt-3">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $pupuk->pupuk_id }}">

                        <!-- Input Qty -->
                        <div class="flex items-center border border-gray-300 rounded-md overflow-hidden">
                            <button type="button" id="decreaseQty"
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 transition cursor-pointer">−</button>
                            <input type="text" name="qty" id="qty" value="1" min="1"
                                max="{{ $pupuk->stok }}" class="w-10 text-center border-none focus:outline-none"
                                required>
                            <button type="button" id="increaseQty"
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 transition cursor-pointer">+</button>
                        </div>

                        <!-- Tombol Beli -->
                        @auth
                            <button type="submit" class="btn btn-primary px-6 py-2 text-white rounded-md">
                                Masuk Keranjang
                            </button>
                        @endauth
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary px-6 py-2 text-white rounded-md">
                                Masuk Keranjang
                            </a>
                        @endguest
                    </form>
                </div>
                <div class="my-4 border-t border-gray-300 mb-4">
                    <p class="my-3 font-semibold text-primary">Deskripsi</p>
                    <table class="text-left">
                        <tr>
                            <th>Jenis Pupuk</th>
                            <td class="pl-2">:</td>
                            <td class="pl-3">{{ $pupuk->jenis }}</td>
                        </tr>
                        <tr>
                            <th>Berat</th>
                            <td class="pl-2">:</td>
                            <td class="pl-3">{{ $pupuk->berat }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td class="pl-2">:</td>
                            <td class="pl-3">{{ $pupuk->status === 'aktif' ? 'Tersedia' : 'Habis' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <section class="my-10">
        <h2
            class="w-max text-2xl font-bold relative after:content-[''] after:w-1/2 after:rounded after:h-[2px] after:bg-green-600 after:absolute after:-bottom-1 after:left-0">
            Produk Lainnya</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
            @foreach ($pupukLainnya as $item)
                <div
                    class="card bg-base-100 border border-gray-200 shadow-sm h-max hover:shadow-lg hover:scale-102 transition-all duration-300">
                    <figure>
                        <img src="{{ asset('/storage/' . $item->gambar) }}" alt="{{ $item->nama }}" />
                    </figure>
                    <div class="card-body border-t border-gray-200">
                        <h2 class="card-title">{{ $item->nama }} ({{ $item->berat }} Kg)</h2>
                        <p class="text-lg font-bold text-primary">Rp {{ number_format($item->harga, 2, ',', '.') }}</p>
                        <div class="card-actions justify-end">
                            <a href="{{ route('produk.detail', $item->slug) }}" class="btn btn-primary w-full">Lihat
                                Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </section>

    @push('scripts')
        <script>
            const qtyInput = document.getElementById('qty');
            const decreaseBtn = document.getElementById('decreaseQty');
            const increaseBtn = document.getElementById('increaseQty');
            const maxQty = parseInt(qtyInput.max);

            decreaseBtn.addEventListener('click', () => {
                let value = parseInt(qtyInput.value);
                if (value > 1) qtyInput.value = value - 1;
            });

            increaseBtn.addEventListener('click', () => {
                let value = parseInt(qtyInput.value);
                if (value < maxQty) qtyInput.value = value + 1;
            });

            qtyInput.addEventListener('input', function() {
                // Hapus karakter non-numerik
                this.value = this.value.replace(/[^0-9]/g, '');

                // Pastikan tidak lebih besar dari stok
                if (parseInt(this.value) > maxQty) {
                    this.value = maxQty;
                }

                // Minimal 1
                if (this.value === '' || parseInt(this.value) < 1) {
                    this.value = 1;
                }
            });
        </script>
    @endpush
</x-frontend-layout>
