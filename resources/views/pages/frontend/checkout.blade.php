<x-frontend-layout :title="$title">
    <div class="mb-3">
        <h1 class="font-bold pt-5 text-sm "><span class="font-normal">Beranda</span> / Checkout</h1>
    </div>
    <div class="grid grid-cols-2">
        <div class="p-10">
            <h1 class="font-bold text-xl uppercase">Penagihan & Pengiriman</h1>
            <div class="mt-4">
                <div>
                    <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Nama</label>
                    <input type="text" name="nama" id="nama"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded block w-full p-2.5 {{ $errors->has('nama') ? 'error' : '' }}"
                        required>
                    @error('nama')
                        <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="telepon" class="block mb-2 text-sm font-medium text-gray-900">Telepon</label>
                    <input type="text" name="telepon" id="telepon"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded block w-full p-2.5 {{ $errors->has('telepon') ? 'error' : '' }}"
                        placeholder="0812xxxxxx" required>
                    @error('telepon')
                        <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="alamat" class="block mb-2 text-sm font-medium text-gray-900">Alamat</label>
                    <input type="text" name="alamat" id="alamat"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded block w-full p-2.5 {{ $errors->has('alamat') ? 'error' : '' }}"
                        placeholder="Provinsi - Kota - Kecamatan" required>
                    @error('alamat')
                        <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                    <input type="email" name="email" id="email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded block w-full p-2.5 {{ $errors->has('email') ? 'error' : '' }}"
                        placeholder="name@gmail.com" required>
                    @error('email')
                        <span class="mt-1 text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Pilihan Transfer Bank --}}
                <div class="mt-6">
                    <label for="bank" class="block mb-2 text-sm font-medium text-gray-900">Pilih Bank
                        Transfer</label>
                    <select id="bank" name="bank"
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
                    <label for="bukti"
                        class="block mb-2 text-sm font-medium text-gray-900 after:content-['*'] after:ml-0.5 after:text-red-600">
                        Upload Bukti Pembayaran
                    </label>
                    <div
                        class="relative border border-gray-300 rounded bg-gray-50 p-2.5 cursor-pointer hover:bg-gray-100">
                        <i
                            class="fa-solid fa-upload text-gray-600 mr-3 text-lg absolute top-1/2 transform -translate-y-1/2 left-4"></i>
                        <input type="file" name="bukti" id="bukti" accept="image/*"
                            class="bg-white border border-gray-300 rounded ps-8 p-2.5 text-gray-700 font-semibold w-full cursor-pointer">
                    </div>
                    @error('bukti')
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
                <div class="border-t border-b border-gray-300 py-3">
                    <div class="flex justify-between mb-2">
                        <p>Pupuk Daun Urea (1kg) × 1</p>
                        <p>Rp 10.000,00</p>
                    </div>
                    <div class="flex justify-between mb-2">
                        <p>Pupuk Kapur Dolomit Super (2 Kg) × 1</p>
                        <p>Rp 10.000,00</p>
                    </div>
                </div>
                <div class="border-b border-gray-300 py-3">
                    <div class="flex justify-between">
                        <p>Ongkir</p>
                        <p>Rp 7.000,00</p>
                    </div>
                </div>
                <div class="flex justify-between mt-3">
                    <h2 class="font-bold text-lg">Total Pesanan</h2>
                    <h2 class="font-bold text-lg">Rp 27.000,00</h2>
                </div>
                <button class="btn btn-primary w-full mt-5 ">
                    Checkout Sekarang
                </button>
            </div>
        </div>
    </div>

    {{-- Script tampil nomor rekening --}}
    <script>
        const bankSelect = document.getElementById('bank');
        const rekeningContainer = document.getElementById('rekening-container');
        const rekeningNumber = document.getElementById('rekening-number');

        const rekeningList = {
            bri: 'BRI - 1234 5678 9101 a.n PT Toko Pupuk Sejahtera',
            bni: 'BNI - 1122 3344 5566 a.n PT Toko Pupuk Sejahtera',
            mandiri: 'Mandiri - 8877 6655 4433 a.n PT Toko Pupuk Sejahtera',
            bca: 'BCA - 9999 8888 7777 a.n PT Toko Pupuk Sejahtera'
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
</x-frontend-layout>
