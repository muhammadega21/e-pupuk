<x-dashboard-layout :title="$title">
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.tailwindcss.min.css">
    @endpush

    <div class="page-title">
        <h1>{{ $title }}</h1>
    </div>

    <div class="mt-4">
        <div class="flex justify-end mb-3">
            <button data-modal-target="addPupuk" data-modal-toggle="addPupuk"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm">
                + Tambah Pupuk
            </button>
        </div>

        <table id="pupukTable" data-dt-theme="light">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>Berat</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Gambar</th>
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
                $('#pupukTable').DataTable({
                    processing: true,
                    serverSide: true,

                    ajax: {
                        url: '{{ route('dashboard.master-data.pupuk') }}',
                    }
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama',
                            name: 'nama'
                        },
                        {
                            data: 'jenis',
                            name: 'jenis'
                        },
                        {
                            data: 'berat',
                            name: 'berat'
                        },
                        {
                            data: 'stok',
                            name: 'stok'
                        },
                        {
                            data: 'harga',
                            name: 'harga'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'gambar',
                            name: 'gambar',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                })
            })

            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                const editModalEl = document.getElementById('editPupuk');
                const modal = new Modal(editModalEl);

                $('#edit_pupuk_name').val('Memuat...');
                $('#edit_pupuk_jenis').val('');
                $('#edit_pupuk_berat').val('');
                $('#edit_pupuk_stok').val('');
                $('#edit_pupuk_harga').val('');
                $('#edit_pupuk_status').val('');

                modal.show();

                $.get(`/dashboard/pupuk/${id}/edit`, function(data) {
                    $('#edit_pupuk_name').val(data.nama);
                    $('#edit_pupuk_jenis').val(data.jenis);
                    $('#edit_pupuk_berat').val(data.berat);
                    $('#edit_pupuk_stok').val(data.stok);
                    $('#edit_pupuk_harga').val(data.harga);
                    $('#edit_pupuk_status').val(data.status);
                    $('#editPupukForm').attr('action', `/dashboard/pupuk/${id}`);
                }).fail(function() {
                    alert('Gagal memuat data pupuk.');
                });
            });
        </script>

        <x-modal id="addPupuk" title="Tambah Pupuk">
            <form action="{{ route('dashboard.master-data.pupuk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Nama Pupuk</label>
                    <input type="text" name="nama" id="nama"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Jenis</label>
                    <input type="text" name="jenis" id="jenis"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Berat (kg)</label>
                    <input type="number" name="berat" id="berat"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Stok</label>
                    <input type="number" name="stok" id="stok"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Harga</label>
                    <input type="number" name="harga" id="harga"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Gambar</label>
                    <input type="file" name="gambar" id="gambar"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" accept="image/*" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                        <option value="aktif">Aktif</option>
                        <option value="tidak aktif">Tidak Aktif</option>
                    </select>
                </div>
                <div class="flex items-center gap-x-3 justify-end mt-3">
                    <button data-modal-hide="addPupuk" type="button"
                        class="py-2.5 px-5  text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">Batal</button>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Tambah</button>
                </div>
            </form>
        </x-modal>

        <x-modal id="editPupuk" title="Edit Pupuk">
            <form id="editPupukForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Nama Pupuk</label>
                    <input type="text" name="nama" id="edit_pupuk_name"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Jenis</label>
                    <input type="text" name="jenis" id="edit_pupuk_jenis"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Berat (kg)</label>
                    <input type="number" name="berat" id="edit_pupuk_berat"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Stok</label>
                    <input type="number" name="stok" id="edit_pupuk_stok"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Harga</label>
                    <input type="number" name="harga" id="edit_pupuk_harga"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Gambar</label>
                    <input type="file" name="gambar" id="edit_pupuk_gambar"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="edit_pupuk_status"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                        <option value="aktif">Aktif</option>
                        <option value="tidak aktif">Tidak Aktif</option>
                    </select>
                </div>

                <div class="flex items-center gap-x-3 justify-end mt-3">
                    <button data-modal-hide="editPupuk" type="button"
                        class="py-2.5 px-5 text-sm font-medium bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">Batal</button>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
                </div>
            </form>
        </x-modal>

        <script>
            $(document).on('click', '[data-modal-hide="editPupuk"]', function() {
                const editModalEl = document.getElementById('editPupuk');
                const modal = editModalEl.__xModal ?? new Modal(editModalEl);
                modal.hide();
            });
        </script>

        <x-alert />
    @endpush
</x-dashboard-layout>
