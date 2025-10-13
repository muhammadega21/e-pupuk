<x-dashboard-layout :title="$title">
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.tailwindcss.min.css">
    @endpush

    <div class="page-title">
        <h1>{{ $title }}</h1>
    </div>

    <div class="mt-4">
        <div class="flex justify-end mb-3">
            <button data-modal-target="addProduksi" data-modal-toggle="addProduksi"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm">
                + Tambah Produksi
            </button>
        </div>

        <table id="produksiTable" data-dt-theme="light">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Pupuk</th>
                    <th>Jumlah Karung</th>
                    <th>Catatan</th>
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
                $('#produksiTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('dashboard.master-data.produksi') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'tanggal_produksi',
                            name: 'tanggal_produksi',
                        },
                        {
                            data: 'barang.nama',
                            name: 'barang.nama',
                            orderable: false,
                        },
                        {
                            data: 'jumlah_karung',
                            name: 'jumlah_karung',
                        },
                        {
                            data: 'note',
                            name: 'note',
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

            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                const editModalEl = document.getElementById('editProduksi');
                const modal = new Modal(editModalEl);

                $('#edit_tanggal_produksi').val('Memuat...');
                $('#edit_barang_id').val('');
                $('#edit_jumlah_karung').val('');
                $('#edit_note').val('');

                modal.show();

                $.get(`/dashboard/produksi/${id}/edit`, function(data) {
                    $('#edit_tanggal_produksi').val(data.tanggal_produksi);
                    $('#edit_barang_id').val(data.barang_id);
                    $('#edit_jumlah_karung').val(data.jumlah_karung);
                    $('#edit_note').val(data.note);

                    $('#editProduksiForm').attr('action', `/dashboard/produksi/${id}`);
                }).fail(function() {
                    alert('Gagal memuat data produksi.');
                });
            });


            $(document).on('submit', '.promotion-form', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Yakin ingin melanjutkan?',
                    text: 'Perubahan role akan segera diterapkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, lanjutkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        </script>

        <x-modal id="addProduksi" title="Tambah Produksi">
            <form action="{{ route('dashboard.master-data.produksi.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Tanggal Produksi</label>
                    <input type="date" name="tanggal_produksi" id="tanggal_produksi"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Pupuk</label>
                    <select name="barang_id" id="barang_id"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                        <option value="">Pilih Pupuk</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->barang_id }}">{{ $barang->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Jumlah Karung</label>
                    <input type="number" name="jumlah_karung" id="jumlah_karung"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                    <textarea name="note" id="note"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>
                <div class="flex items-center gap-x-3 justify-end mt-3">
                    <button data-modal-hide="addProduksi" type="button"
                        class="py-2.5 px-5  text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">Batal</button>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Tambah</button>
                </div>
            </form>
        </x-modal>

        <x-modal id="editProduksi" title="Edit Produksi">
            <form id="editProduksiForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Tanggal Produksi</label>
                    <input type="date" name="tanggal_produksi" id="edit_tanggal_produksi"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Pupuk</label>
                    <select name="barang_id" id="edit_barang_id"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                        <option value="">Pilih Pupuk</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->barang_id }}">{{ $barang->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Jumlah Karung</label>
                    <input type="number" name="jumlah_karung" id="edit_jumlah_karung"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                    <textarea name="note" id="edit_note"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>

                <div class="flex items-center gap-x-3 justify-end mt-3">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
                </div>
            </form>
        </x-modal>


        <x-alert />
    @endpush
</x-dashboard-layout>
