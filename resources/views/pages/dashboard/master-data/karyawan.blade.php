<x-dashboard-layout :title="$title">
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.tailwindcss.min.css">
    @endpush

    <div class="page-title">
        <h1>{{ $title }}</h1>
    </div>

    <div class="mt-4">
        <div class="flex justify-end mb-3">
            <button data-modal-target="addKaryawan" data-modal-toggle="addKaryawan"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm">
                + Tambah Karyawan
            </button>
        </div>

        <table id="karyawanTable" data-dt-theme="light">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Karyawan</th>
                    <th>Email</th>
                    <th>Role</th>
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
                $('#karyawanTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('dashboard.master-data.karyawan') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'user_data.nama',
                            name: 'user_data.nama',
                        },
                        {
                            data: 'email',
                            name: 'email',
                            orderable: false,
                        },
                        {
                            data: 'role.role_name',
                            name: 'role.role_name',
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
                const editModalEl = document.getElementById('editKaryawan');
                const modal = new Modal(editModalEl);

                $('#edit_karyawan_name').val('Memuat...');
                $('#edit_karyawan_alamat').val('');
                $('#edit_karyawan_telepon').val('');
                $('#edit_karyawan_role').val('');

                modal.show();

                $.get(`/dashboard/karyawan/${id}/edit`, function(data) {
                    $('#edit_karyawan_name').val(data.user_data.nama);
                    $('#edit_karyawan_alamat').val(data.user_data.alamat);
                    $('#edit_karyawan_telepon').val(data.user_data.telepon);
                    $('#edit_karyawan_role').val(data.role_id);
                    $('#editKaryawanForm').attr('action', `/dashboard/karyawan/${id}`);
                }).fail(function() {
                    alert('Gagal memuat data karyawan.');
                });
            });
        </script>

        <x-modal id="addKaryawan" title="Tambah Karyawan">
            <form action="{{ route('dashboard.master-data.karyawan.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Nama Karyawan</label>
                    <input type="text" name="nama" id="nama"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role_id" id="role_id"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                        @foreach ($roles as $role)
                            <option value="{{ $role->role_id }}">{{ $role->role_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>

                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="alamat" id="alamat"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required></textarea>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Telepon</label>
                    <input type="text" name="telepon" id="telepon"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>
                <div class="flex items-center gap-x-3 justify-end mt-3">
                    <button data-modal-hide="addKaryawan" type="button"
                        class="py-2.5 px-5  text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">Batal</button>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Tambah</button>
                </div>
            </form>
        </x-modal>

        <x-modal id="editKaryawan" title="Edit Karyawan">
            <form id="editKaryawanForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Nama Karyawan</label>
                    <input type="text" name="nama" id="edit_karyawan_name"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role_id" id="edit_karyawan_role"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @foreach ($roles as $role)
                            <option value="{{ $role->role_id }}">{{ $role->role_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="alamat" id="edit_karyawan_alamat"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required></textarea>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Telepon</label>
                    <input type="text" name="telepon" id="edit_karyawan_telepon"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
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
