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
                            render: function(data) {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR'
                                }).format(data);
                            }
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
        </script>
        <x-pesanan.modal-add order_no="{{ $order_no }}" :barangs="$barangs" />
        <x-pesanan.modal-edit order_no="{{ $order_no }}" :barangs="$barangs" />
        <x-alert />
    @endpush
</x-dashboard-layout>
