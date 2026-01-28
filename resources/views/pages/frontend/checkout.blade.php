<x-frontend-layout :title="$title">
    @if ($errors->any() || session()->has('error'))
        @push('scripts')
            <script>
                const errors = `{!! '<ul>' . collect($errors->all())->map(fn($e) => "<li>{$e}</li>")->implode('') . '</ul>' !!}`;

                Swal.fire({
                    icon: "error",
                    title: "Terjadi Kesalahan!",
                    html: errors,
                    confirmButtonText: "OK"
                });
            </script>
        @endpush
    @endif
    <div class="mb-3">
        <h1 class="font-bold pt-5 text-sm "><span class="font-normal">Beranda</span> / Checkout</h1>
    </div>
    <form action="{{ route('checkout.store') }}" class="grid grid-cols-2" method="POST" enctype="multipart/form-data">
        @method('post')
        @csrf
        <div class="p-10">
            <h1 class="font-bold text-xl uppercase">Penagihan & Pengiriman</h1>
            <div class="mt-4">
                <div>
                    <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Nama</label>
                    <input type="text" name="nama" id="nama"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded block w-full p-2.5 {{ $errors->has('nama') ? 'error' : '' }}"
                        value="{{ $pesanan->user_data->nama ?? '' }}" required>
                    @error('nama')
                        <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="telepon" class="block mb-2 text-sm font-medium text-gray-900">Telepon</label>
                    <input type="text" name="telepon" id="telepon"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded block w-full p-2.5 {{ $errors->has('telepon') ? 'error' : '' }}"
                        value="{{ $pesanan->user_data->telepon ?? '' }}" placeholder="0812xxxxxx" required>
                    @error('telepon')
                        <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Provinsi</label>
                    <select id="provinsi" class="select select-bordered w-full" required>
                        <option value="">Pilih Provinsi</option>
                        @foreach ($provinsi as $prov)
                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <label class="block mb-2 text-sm font-medium">Kabupaten / Kota</label>
                    <select id="kota" class="select select-bordered w-full" disabled required></select>
                </div>

                <div class="mt-4">
                    <label class="block mb-2 text-sm font-medium">Kecamatan</label>
                    <select id="kecamatan" class="select select-bordered w-full" disabled required></select>
                </div>

                <input type="hidden" name="alamat_indoregion" id="alamat_indoregion">
                <input type="hidden" name="ongkir" id="ongkir_input">

                <div class="mt-4">
                    <label for="alamat" class="block mb-2 text-sm font-medium text-gray-900">Alamat Lengkap</label>
                    <input type="text" name="alamat" id="alamat"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded block w-full p-2.5 {{ $errors->has('alamat') ? 'error' : '' }}"
                        placeholder="Provinsi - Kota - Kecamatan" value="{{ $pesanan->user_data->alamat ?? '' }}"
                        required>
                    @error('alamat')
                        <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Pilihan Transfer Bank --}}
                <div class="mt-6">
                    <label for="bank" class="block mb-2 text-sm font-medium text-gray-900">Pilih Bank
                        Transfer</label>
                    <select id="bank"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded block w-full p-2.5">
                        <option value="">-- Pilih Bank --</option>
                        <option value="bri">BRI</option>
                        <option value="bni">BNI</option>
                        <option value="mandiri">Mandiri</option>
                        <option value="bca">BCA</option>
                    </select>

                    {{-- Nomor Rekening yang muncul otomatis --}}
                    <div id="rekening-container" class="mt-3 hidden">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Nomor Rekening : </label>
                        <div id="rekening-number"
                            class="bg-white border border-gray-300 rounded p-2.5 text-gray-700 font-semibold"></div>
                    </div>
                </div>

                {{-- Bukti Pembayaran --}}
                <div class="mt-6">
                    <label for="bukti_url"
                        class="block mb-2 text-sm font-medium text-gray-900 after:content-['*'] after:ml-0.5 after:text-red-600">
                        Upload Bukti Pembayaran
                    </label>
                    <div
                        class="relative border border-gray-300 rounded bg-gray-50 p-2.5 cursor-pointer hover:bg-gray-100">
                        <i
                            class="fa-solid fa-upload text-gray-600 mr-3 text-lg absolute top-1/2 transform -translate-y-1/2 left-4"></i>
                        <input type="file" name="bukti_url" id="bukti_url" accept="image/*"
                            class="bg-white border border-gray-300 rounded ps-8 p-2.5 text-gray-700 font-semibold w-full cursor-pointer">
                    </div>
                    @error('bukti_url')
                        <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="p-10 bg-gray-100 h-max">
            <h1 class="font-bold text-xl uppercase text-center">Pesanan Anda</h1>
            <div class="bg-white p-5 mt-5 rounded-md">
                <div class="flex justify-between mb-3">
                    <p class="font-semibold">Produk</p>
                    <p class="font-semibold">Total</p>
                </div>
                @if ($pesanan)
                    <div class="border-t border-b border-gray-300 py-3">
                        @foreach ($pesanan->detailPesanan as $item)
                            <div class="flex justify-between mb-2">
                                <p>{{ $item->barang->nama }} ({{ $item->barang->berat }}kg) × {{ $item->qty_karung }}
                                </p>
                                <p>Rp {{ number_format($item->barang->harga * $item->qty_karung, 2, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center">Tidak ada produk</p>
                @endif
                @php
                    $total = $ongkir;
                    if ($pesanan) {
                        foreach ($pesanan->detailPesanan as $item) {
                            $total += $item->barang->harga * $item->qty_karung;
                        }
                    }
                @endphp
                <div class="border-b border-gray-300 py-3">
                    <div class="flex justify-between">
                        <p>Ongkir <span class="text-xs text-gray-500">(Lengkapi alamat terlebih dahulu)</span></p>
                        <p id="ongkir-text">Rp {{ number_format($ongkir, 2, ',', '.') }}</p>
                    </div>
                </div>
                <div class="flex justify-between mt-3">
                    <h2 class="font-bold text-lg">Total Pesanan</h2>
                    <h2 id="total-text" class="font-bold text-lg">Rp {{ number_format($total, 2, ',', '.') }}</h2>
                    <input type="hidden" name="total_bayar" id="total_input">
                </div>
                <button class="btn btn-primary w-full mt-5 ">
                    Checkout Sekarang
                </button>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            // Alamat
            const provinsi = document.getElementById('provinsi');
            const kota = document.getElementById('kota');
            const kecamatan = document.getElementById('kecamatan');

            provinsi.addEventListener('change', async function() {
                kota.innerHTML = '<option>Loading...</option>';
                kota.disabled = true;

                const res = await fetch(`/ajax/kota/${this.value}`);
                const data = await res.json();

                kota.innerHTML = '<option value="">Pilih Kota</option>';
                data.forEach(d => {
                    kota.innerHTML += `<option value="${d.id}">${d.name}</option>`;
                });
                kota.disabled = false;
            });

            kota.addEventListener('change', async function() {
                kecamatan.innerHTML = '<option>Loading...</option>';
                kecamatan.disabled = true;

                const res = await fetch(`/ajax/kecamatan/${this.value}`);
                const data = await res.json();

                kecamatan.innerHTML = '<option value="">Pilih Kecamatan</option>';
                data.forEach(d => {
                    kecamatan.innerHTML += `<option value="${d.name}">${d.name}</option>`;
                });
                kecamatan.disabled = false;
            });

            const totalProduk =
                {{ $pesanan ? $pesanan->detailPesanan->sum(fn($i) => $i->barang->harga * $i->qty_karung) : 0 }};

            kecamatan.addEventListener('change', async function() {
                const alamat =
                    `${this.value}, ${kota.options[kota.selectedIndex].text}, ${provinsi.options[provinsi.selectedIndex].text}`;

                document.getElementById('alamat_indoregion').value = alamat;

                const res = await fetch('/ajax/hitung-ongkir', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        alamat
                    })
                });

                const data = await res.json();

                // Ongkir
                document.getElementById('ongkir_input').value = data.ongkir;
                document.getElementById('ongkir-text').innerText = `Rp ${data.formatted}`;

                // Total
                const total = totalProduk + parseInt(data.ongkir);
                document.getElementById('total-text').innerText =
                    `Rp ${total.toLocaleString('id-ID', { minimumFractionDigits: 2 })}`;

                document.getElementById('total_input').value = total;
            });

            // Pilihan Bank
            const bankSelect = document.getElementById('bank');
            const rekeningContainer = document.getElementById('rekening-container');
            const rekeningNumber = document.getElementById('rekening-number');

            const rekeningList = {
                bri: 'BRI - 1234 5678 9101 a.n Agro Lestarindo',
                bni: 'BNI - 1122 3344 5566 a.n Agro Lestarindo',
                mandiri: 'Mandiri - 8877 6655 4433 a.n Agro Lestarindo',
                bca: 'BCA - 9999 8888 7777 a.n Agro Lestarindo'
            };

            bankSelect.addEventListener('change', function() {
                const selected = this.value;
                if (selected && rekeningList[selected]) {
                    rekeningContainer.classList.remove('hidden');
                    rekeningNumber.textContent = rekeningList[selected];
                } else {
                    rekeningContainer.classList.add('hidden');
                    rekeningNumber.textContent = '';
                }
            });
        </script>
    @endpush
</x-frontend-layout>
