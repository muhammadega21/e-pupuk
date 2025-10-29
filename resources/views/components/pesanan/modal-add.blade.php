@props(['order_no', 'barangs'])

<x-modal id="addPesanan" title="Tambah Pesanan">
    <form action="{{ route('dashboard.transaksi.pesanan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Informasi Umum Pesanan --}}
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Pesanan</label>
                <input type="text" name="order_no" id="order_no" value="{{ $order_no }}" readonly
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 bg-gray-100 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Metode Pengiriman</label>
                <select name="order_type" id="order_type"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    required>
                    <option value="">Pilih Metode</option>
                    <option value="delivery">Delivery</option>
                    <option value="pickup">Pickup</option>
                </select>
            </div>
        </div>

        {{-- Produk (Bisa Lebih Dari Satu) --}}
        <div id="pupukContainer">
            <div class="pupuk-item border rounded-lg p-3 mb-3 bg-gray-50">
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pupuk</label>
                        <select name="barang_id[]"
                            class="barang_id w-full border border-gray-300 rounded-md px-3 py-2 mt-1" required>
                            <option value="">Pilih Pupuk</option>
                            @foreach ($barangs as $barang)
                                <option value="{{ $barang->barang_id }}" data-harga="{{ $barang->harga }}">
                                    {{ $barang->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jumlah Karung</label>
                        <input type="number" name="total_karung[]" min="1" value="1"
                            class="total_karung w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                        <input type="text" name="subtotal[]" readonly
                            class="subtotal w-full border border-gray-300 rounded-md px-3 py-2 mt-1 bg-gray-100"
                            required>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end mb-3">
            <button type="button" id="addPupuk"
                class="bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-2 rounded-md">
                + Tambah Pupuk
            </button>
        </div>

        {{-- Total Bayar --}}
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700">Total Bayar</label>
            <input type="text" name="total_bayar" id="total_bayar" readonly
                class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 bg-gray-100" required>
        </div>

        {{-- Pembayaran --}}
        <div class="grid grid-cols-2 gap-4 mb-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                <select name="metode_pembayaran" id="metode_pembayaran"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" required>
                    <option value="">Pilih Metode</option>
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer Bank</option>
                </select>
            </div>
            <div id="buktiPembayaranWrapper" class="hidden">
                <label class="block text-sm font-medium text-gray-700">Bukti Pembayaran</label>
                <input type="file" name="bukti_url" id="bukti_url" accept="image/*"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1">
            </div>
        </div>

        {{-- Pengiriman --}}
        <div id="deliverySection" class="border-t border-gray-200 pt-3 mt-3 hidden">
            <h3 class="font-semibold text-gray-700 mb-2">Informasi Pengiriman</h3>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Penerima</label>
                    <input type="text" name="nama_penerima" id="nama_penerima"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Telepon Penerima</label>
                    <input type="text" name="telepon" id="telepon"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1">
                </div>
            </div>
            <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="alamat" id="alamat" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"
                    placeholder="Ulak Karang, Padang"></textarea>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end mt-4">
            <button data-modal-hide="addPesanan" type="button"
                class="py-2.5 px-5 text-sm font-medium bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                Batal
            </button>
            <button type="submit"
                class="ml-3 text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">
                Simpan Pesanan
            </button>
        </div>
    </form>
</x-modal>

<script>
    $(document).ready(function() {
        // Tambah baris pupuk
        $('#addPupuk').click(() => clonePupukItem('#pupukContainer'));

        // Hitung subtotal dan total
        $(document).on('change keyup', '.barang_id, .total_karung', function() {
            let item = $(this).closest('.pupuk-item');
            let harga = $('option:selected', item.find('.barang_id')).data('harga') || 0;
            let qty = parseInt(item.find('.total_karung').val()) || 0;
            let subtotal = harga * qty;
            item.find('.subtotal').val(subtotal.toFixed(2));
            hitungTotal();
        });

        function hitungTotal() {
            let total = 0;
            $('.subtotal').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#total_bayar').val(total.toFixed(2));
        }

        // Jika metode pembayaran transfer → tampilkan upload
        $('#metode_pembayaran').on('change', function() {
            if ($(this).val() === 'transfer') {
                $('#buktiPembayaranWrapper').removeClass('hidden');
                $('#bukti_url').attr('required', false);
            } else {
                $('#buktiPembayaranWrapper').addClass('hidden');
                $('#bukti_url').removeAttr('required');
            }
        });

        // Jika metode pengiriman delivery → tampilkan alamat
        $('#order_type').on('change', function() {
            if ($(this).val() === 'delivery') {
                $('#deliverySection').removeClass('hidden');
                $('#nama_penerima, #telepon, #alamat').attr('required', true);
            } else {
                $('#deliverySection').addClass('hidden');
                $('#nama_penerima, #telepon, #alamat').removeAttr('required');
            }
        });
    });
</script>
