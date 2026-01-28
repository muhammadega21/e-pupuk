@props(['order_no', 'barangs'])

<x-modal id="editPesanan" title="Edit Pesanan">
    <form id="editPesananForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Informasi Umum Pesanan --}}
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Pesanan</label>
                <input type="text" id="edit_order_no" readonly
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 bg-gray-100">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Metode Pengiriman</label>
                <select name="order_type" id="edit_order_type"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    required>
                    <option value="">Pilih Metode</option>
                    <option value="delivery">Delivery</option>
                    <option value="pickup">Pickup</option>
                </select>
            </div>
        </div>

        {{-- Detail Barang --}}
        <div id="editPupukContainer"></div>

        <div class="flex justify-end mb-3">
            <button type="button" id="editAddPupuk"
                class="bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-2 rounded-md">
                + Tambah Pupuk
            </button>
        </div>

        {{-- Total Bayar --}}
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700">Total Bayar</label>
            <input type="text" name="total_bayar" id="edit_total_bayar" readonly
                class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 bg-gray-100" required>
        </div>

        {{-- Pembayaran --}}
        <div class="grid grid-cols-2 gap-4 mb-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                <select name="metode_pembayaran" id="edit_metode_pembayaran"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" required>
                    <option value="">Pilih Metode</option>
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer Bank</option>
                </select>
            </div>
            <div id="editBuktiPembayaranWrapper" class="hidden">
                <label class="block text-sm font-medium text-gray-700">Bukti Pembayaran</label>
                <input type="file" name="bukti_url" id="edit_bukti_url" accept="image/*"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1">
                <img id="edit_preview_bukti" class="mt-2 w-24 rounded-md hidden" alt="Preview Bukti">
            </div>
        </div>

        {{-- Pengiriman --}}
        <div id="editDeliverySection" class="border-t border-gray-200 pt-3 mt-3 hidden">
            <h3 class="font-semibold text-gray-700 mb-2">Informasi Pengiriman</h3>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Penerima</label>
                    <input type="text" name="nama_penerima" id="edit_nama_penerima"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Telepon</label>
                    <input type="text" name="telepon" id="edit_telepon"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1">
                </div>
            </div>
            <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="alamat" id="edit_alamat" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"></textarea>
            </div>
            <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700">Ongkir</label>
                <input type="number" name="ongkir" id="edit_ongkir"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1">
            </div>
        </div>

        <div class="flex justify-end mt-4">
            <button data-modal-hide="editPesanan" type="button"
                class="py-2.5 px-5 text-sm font-medium bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                Batal
            </button>
            <button type="submit"
                class="ml-3 text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">
                Simpan Perubahan
            </button>
        </div>
    </form>
</x-modal>

<script>
    $(document).on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        const editModalEl = document.getElementById('editPesanan');
        let modal = editModalEl.__xModal ?? new Modal(editModalEl);
        editModalEl.__xModal = modal;

        $('#editPesananForm')[0].reset();
        $('#editPupukContainer').html('<p>Memuat...</p>');
        $('#edit_preview_bukti').addClass('hidden');
        modal.show();

        $.get(`/dashboard/pesanan/${id}/edit`, function(data) {
            $('#edit_order_no').val(data.order_no);
            $('#edit_order_type').val(data.order_type);
            $('#edit_total_bayar').val(data.total_bayar);
            $('#edit_metode_pembayaran').val(data.pembayaran.metode);

            if (data.pembayaran.bukti_url) {
                $('#edit_preview_bukti').attr('src', '/storage/' + data.pembayaran.bukti_url)
                    .removeClass('hidden');
            }

            if (data.order_type === 'delivery' && data.pengiriman) {
                $('#editDeliverySection').removeClass('hidden');
                $('#edit_nama_penerima').val(data.pengiriman.nama_penerima);
                $('#edit_telepon').val(data.pengiriman.telepon);
                $('#edit_alamat').val(data.pengiriman.alamat);
                $('#edit_ongkir').val(data.pengiriman.ongkir);
            } else {
                $('#editDeliverySection').addClass('hidden');
            }

            const isPaid = data.payment_status === 'paid';
            const isDelivered = data.fulfillment_status === 'delivered';

            $('#editPesananForm').find('input, select, textarea, button').prop('disabled', false);

            if (isPaid && isDelivered) {
                $('#editPesananForm').find('input, select, textarea, button').prop('disabled', true);
                $('#editPesananForm').find('[data-modal-hide="editPesanan"]').prop('disabled', false);
                Swal.fire({
                    icon: 'info',
                    title: 'Pesanan Selesai',
                    text: 'Pesanan ini sudah dibayar dan dikirim. Tidak dapat diedit.',
                    confirmButtonColor: '#3085d6'
                });
            } else if (isPaid) {
                $('#edit_metode_pembayaran, #edit_bukti_url, #editAddPupuk').prop('disabled', true);
                Swal.fire({
                    icon: 'info',
                    title: 'Pembayaran Selesai',
                    text: 'Data pembayaran tidak dapat diubah karena status sudah "paid".',
                    confirmButtonColor: '#3085d6'
                });
            } else if (isDelivered) {
                $('#edit_order_type, #edit_nama_penerima, #edit_telepon, #edit_alamat').prop('disabled',
                    true);
                Swal.fire({
                    icon: 'info',
                    title: 'Pesanan Terkirim',
                    text: 'Data pengiriman tidak dapat diubah karena status sudah "delivered".',
                    confirmButtonColor: '#3085d6'
                });
            }

            let html = '';
            data.detail_pesanan.forEach((item, index) => {
                let options = `
            @foreach ($barangs as $barang)
                <option value="{{ $barang->pupuk_id }}" data-harga="{{ $barang->harga }}">
                    {{ $barang->nama }}
                </option>
            @endforeach
        `;

                html += `
        <div class="pupuk-item border rounded-lg p-3 mb-3 bg-gray-50">
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pupuk</label>
                    <select name="pupuk_id[]" class="edit_pupuk_id w-full border border-gray-300 rounded-md px-3 py-2 mt-1" required>
                        ${options}
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jumlah Karung</label>
                    <input type="number" name="total_karung[]" min="1" value="${item.qty_karung}"
                        class="edit_total_karung w-full border border-gray-300 rounded-md px-3 py-2 mt-1" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                    <input type="text" name="subtotal[]" readonly value="${parseFloat(item.subtotal).toFixed(2)}"
                        class="edit_subtotal w-full border border-gray-300 rounded-md px-3 py-2 mt-1 bg-gray-100">
                </div>
            </div>
        </div>
        `;
            });


            $('#editPupukContainer').html(html);


            data.detail_pesanan.forEach((item, index) => {
                $('#editPupukContainer .pupuk-item').eq(index).find('.edit_pupuk_id').val(item
                    .pupuk_id);
            });


            $('#editPesananForm').attr('action', `/dashboard/pesanan/${id}`);
        }).fail(function() {
            alert('Gagal memuat data pesanan.');
        });
    });

    $(document).on('change keyup', '.edit_pupuk_id, .edit_total_karung', function() {
        let item = $(this).closest('.pupuk-item');
        let harga = $('option:selected', item.find('.edit_pupuk_id')).data('harga') || 0;
        let qty = parseInt(item.find('.edit_total_karung').val()) || 0;
        let subtotal = harga * qty;
        item.find('.edit_subtotal').val(subtotal.toFixed(2));
        hitungEditTotal();
    });

    function hitungEditTotal() {
        let total = 0;
        $('.edit_subtotal').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#edit_total_bayar').val(total.toFixed(2));
    }

    $(document).on('click', '#editAddPupuk', () => clonePupukItem('#editPupukContainer'));


    $('#edit_metode_pembayaran').on('change', function() {
        if ($(this).val() === 'transfer') {
            $('#editBuktiPembayaranWrapper').removeClass('hidden');
        } else {
            $('#editBuktiPembayaranWrapper').addClass('hidden');
        }
    });

    $('#edit_order_type').on('change', function() {
        if ($(this).val() === 'delivery') {
            $('#editDeliverySection').removeClass('hidden');
        } else {
            $('#editDeliverySection').addClass('hidden');
        }
    });

    $(document).on('click', '[data-modal-hide="editPesanan"]', function() {
        const editModalEl = document.getElementById('editPesanan');
        const modal = editModalEl.__xModal ?? new Modal(editModalEl);
        modal.hide();
    });
</script>
