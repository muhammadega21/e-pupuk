<x-dashboard-layout :title="$title">
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.tailwindcss.min.css">
    @endpush

    <div class="page-title">
        <h1>{{ $title }}</h1>
    </div>

    <div class="mt-4">
        <div class="flex justify-end mb-3">
            <button data-modal-target="addRole" data-modal-toggle="addRole"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm">
                + Tambah Role
            </button>
        </div>

        <table id="roleTable" data-dt-theme="light">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Role</th>
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
                $('#roleTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('dashboard.master-data.role') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'role_name',
                            name: 'role_name',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                        },
                    ]
                })

                $(document).on('click', '.edit-btn', function() {
                    let id = $(this).data('id');

                    const editModalEl = document.getElementById('editRole');
                    const modal = new Modal(editModalEl);

                    $('#edit_role_name').val('Memuat data...');

                    modal.show();

                    $.get(`/dashboard/role/${id}/edit`, function(data) {
                        $('#edit_role_name').val(data.role_name);
                        $('#editRoleForm').attr('action', `/dashboard/role/${id}`);
                    }).fail(function() {
                        $('#edit_role_name').val('');
                        alert('Gagal memuat data role.');
                    });
                });


            })
        </script>

        <x-modal id="addRole" title="Tambah Role">
            <form action="{{ route('dashboard.master-data.role.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Nama Role</label>
                    <input type="text" name="role_name" id="role_name"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>
                <div class="flex items-center gap-x-3 justify-end mt-3">
                    <button data-modal-hide="addRole" type="button"
                        class="py-2.5 px-5  text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">Batal</button>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Tambah</button>
                </div>
            </form>
        </x-modal>

        <x-modal id="editRole" title="Edit Role">
            <form id="editRoleForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Nama Role</label>
                    <input type="text" name="role_name" id="edit_role_name"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>
                <div class="flex items-center gap-x-3 justify-end mt-3">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan</button>
                </div>
            </form>
        </x-modal>

        <x-alert />
    @endpush
</x-dashboard-layout>
