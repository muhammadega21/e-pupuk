<x-dashboard-layout :title="$title">
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.tailwindcss.min.css">
    @endpush

    <div class="page-title">
        <h1>{{ $title }}</h1>
    </div>

    <div class="mt-4">
        <table id="pelangganTable" data-dt-theme="light">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat</th>
                    <th>No Telepon</th>
                    <th>Email</th>
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
                $('#pelangganTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('dashboard.master-data.pelanggan') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'user_data.nama',
                            name: 'user_data.nama',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'user_data.alamat',
                            name: 'user_data.alamat',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'user_data.telepon',
                            name: 'user_data.telepon',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'email',
                            name: 'email',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: true,
                            searchable: false
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
                const editModalEl = document.getElementById('editPelanggan');
                const modal = new Modal(editModalEl);

                $('#edit_pelanggan_name').val('Memuat...');
                $('#edit_pelanggan_alamat').val('');
                $('#edit_pelanggan_telepon').val('');
                $('#edit_pelanggan_email').val('');
                $('#edit_pelanggan_status').val('');

                modal.show();

                $.get(`/dashboard/pelanggan/${id}/edit`, function(data) {
                    $('#edit_pelanggan_name').val(data.user_data.nama);
                    $('#edit_pelanggan_alamat').val(data.user_data.alamat);
                    $('#edit_pelanggan_telepon').val(data.user_data.telepon);
                    $('#edit_pelanggan_email').val(data.email);
                    $('#edit_pelanggan_status').val(data.status);
                    $('#editPelangganForm').attr('action', `/dashboard/pelanggan/${id}`);
                }).fail(function() {
                    alert('Gagal memuat data pelanggan.');
                });
            });
        </script>

        <x-modal id="editPelanggan" title="Edit Pelanggan">
            <form id="editPelangganForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                    <input type="text" name="nama" id="edit_pelanggan_name"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="alamat" id="edit_pelanggan_alamat"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required></textarea>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Telepon</label>
                    <input type="text" name="telepon" id="edit_pelanggan_telepon"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="edit_pelanggan_email"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="edit_pelanggan_status"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                        <option value="aktif">Aktif</option>
                        <option value="tidak aktif">Tidak Aktif</option>
                    </select>
                </div>

                <div class="flex items-center gap-x-3 justify-end mt-3">
                    <button data-modal-hide="editPelanggan" type="button"
                        class="py-2.5 px-5 text-sm font-medium bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">Batal</button>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
                </div>
            </form>
        </x-modal>

        <script>
            $(document).on('click', '[data-modal-hide="editPelanggan"]', function() {
                const editModalEl = document.getElementById('editPelanggan');
                const modal = editModalEl.__xModal ?? new Modal(editModalEl);
                modal.hide();
            });
        </script>

        <x-alert />
    @endpush
</x-dashboard-layout>
