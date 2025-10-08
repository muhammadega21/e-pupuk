<x-dashboard-layout :title="$title">
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.tailwindcss.min.css">
    @endpush
    <div class="page-title">
        <h1>{{ $title }}</h1>
    </div>
    <div class="mt-4">
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
        <script>
            $(document).ready(function() {
                $('#roleTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('dashboard.master-data.role') }}",
                    columns: [{
                            data: 'role_id',
                            orderable: false,
                            searchable: false,
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
            })
        </script>
    @endpush
</x-dashboard-layout>
