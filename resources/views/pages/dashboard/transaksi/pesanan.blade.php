<x-dashboard-layout :title="$title">
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.tailwindcss.min.css">
    @endpush

    <div class="page-title">
        <h1>{{ $title }}</h1>
    </div>

    <div class="mt-4">
        <div class="flex justify-end mb-3">
            <button data-modal-target="addPesanan" data-modal-toggle="addPesanan"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm">
                + Tambah Pesanan
            </button>
        </div>

        <table id="pesananTable" data-dt-theme="light">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. Pesanan</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Total Bayar</th>
                    <th>Metode</th>
                    <th>Channel</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/2.3.4/js/dataTables.tailwindcss.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#pesananTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('dashboard.transaksi.pesanan') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'order_no',
                            name: 'order_no',
                            orderable: false
                        },
                        {
                            data: 'tanggal_transaksi',
                            name: 'tanggal_transaksi',
                        },
                        {
                            data: 'user_data.nama',
                            name: 'user_data.nama',
                            orderable: false,
                            render: function(data) {
                                return data ? data : 'Guest Pickup';
                            }
                        },
                        {
                            data: 'total_bayar',
                            name: 'total_bayar',
                        },
                        {
                            data: 'order_type',
                            name: 'order_type',
                        },
                        {
                            data: 'channel',
                            name: 'channel',
                        },
                        {
                            data: 'payment_status',
                            name: 'payment_status',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                        },
                    ]
                })
            })

            function clonePupukItem(containerSelector) {
                let newItem = $(`${containerSelector} .pupuk-item:first`).clone();
                newItem.find('input').val('');
                newItem.find('select').prop('selectedIndex', 0);
                $(containerSelector).append(newItem);
            }

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
                    // Isi data utama
                    $('#edit_order_no').val(data.order_no);
                    $('#edit_order_type').val(data.order_type);
                    $('#edit_total_bayar').val(data.total_bayar);
                    $('#edit_metode_pembayaran').val(data.pembayaran.metode);

                    // tampilkan bukti
                    if (data.pembayaran.bukti_url) {
                        $('#edit_preview_bukti').attr('src', '/storage/' + data.pembayaran.bukti_url)
                            .removeClass('hidden');
                    }

                    // tampilkan data pengiriman
                    if (data.order_type === 'delivery' && data.pengiriman) {
                        $('#editDeliverySection').removeClass('hidden');
                        $('#edit_nama_penerima').val(data.pengiriman.nama_penerima);
                        $('#edit_telepon').val(data.pengiriman.telepon);
                        $('#edit_alamat').val(data.pengiriman.alamat);
                    } else {
                        $('#editDeliverySection').addClass('hidden');
                    }

                    // tampilkan produk
                    let html = '';
                    data.detail_pesanan.forEach((item, index) => {
                        let options = `
            @foreach ($barangs as $barang)
                <option value="{{ $barang->barang_id }}" data-harga="{{ $barang->harga }}">
                    {{ $barang->nama }}
                </option>
            @endforeach
        `;

                        html += `
        <div class="pupuk-item border rounded-lg p-3 mb-3 bg-gray-50">
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pupuk</label>
                    <select name="barang_id[]" class="edit_barang_id w-full border border-gray-300 rounded-md px-3 py-2 mt-1" required>
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
                        $('#editPupukContainer .pupuk-item').eq(index).find('.edit_barang_id').val(item
                            .barang_id);
                    });


                    // update action form
                    $('#editPesananForm').attr('action', `/dashboard/pesanan/${id}`);
                }).fail(function() {
                    alert('Gagal memuat data pesanan.');
                });
            });

            // event perhitungan ulang subtotal dan total
            $(document).on('change keyup', '.edit_barang_id, .edit_total_karung', function() {
                let item = $(this).closest('.pupuk-item');
                let harga = $('option:selected', item.find('.edit_barang_id')).data('harga') || 0;
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

            // Tambah baris pupuk di modal edit
            $(document).on('click', '#editAddPupuk', () => clonePupukItem('#editPupukContainer'));


            // jika metode pembayaran transfer → tampilkan upload
            $('#edit_metode_pembayaran').on('change', function() {
                if ($(this).val() === 'transfer') {
                    $('#editBuktiPembayaranWrapper').removeClass('hidden');
                } else {
                    $('#editBuktiPembayaranWrapper').addClass('hidden');
                }
            });

            // jika delivery → tampilkan form alamat
            $('#edit_order_type').on('change', function() {
                if ($(this).val() === 'delivery') {
                    $('#editDeliverySection').removeClass('hidden');
                } else {
                    $('#editDeliverySection').addClass('hidden');
                }
            });
        </script>

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
                        $('#bukti_url').attr('required', true);
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




        <x-alert />
    @endpush
</x-dashboard-layout>
