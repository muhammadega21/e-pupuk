<x-dashboard-layout :title="$title">
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.tailwindcss.min.css">
    @endpush

    <div class="page-title">
        <h1>{{ $title }}</h1>
    </div>

    <div class="mt-4 w-full">
        @if (Auth::user()->hasRole(['admin', 'karyawan']))
            <div class="flex justify-end mb-3">
                <button data-modal-target="addPesanan" data-modal-toggle="addPesanan"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm">
                    + Tambah Pesanan
                </button>
            </div>
        @endif


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
                    <th>Status Pembayaran</th>
                    <th>Status Pengiriman</th>
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
                            render: function(data, type, row) {
                                return data ? data : (row.pengiriman ? row.pengiriman.nama_penerima :
                                    'Guest User');
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
                            render: function(data) {
                                return data ? data : '-';
                            }
                        },
                        {
                            data: 'fulfillment_status',
                            name: 'fulfillment_status',
                            render: function(data) {
                                return data ? data : '-';
                            }
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

            $(document).on('submit', '.confirm-delivery', function(e) {
                e.preventDefault();

                const form = this;

                Swal.fire({
                    title: "Konfirmasi Pengiriman?",
                    text: "Anda yakin telah menerima pesanan ini?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, lanjutkan!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        </script>

        @if (Auth::user()->hasRole(['admin', 'pelanggan']))
            <script>
                function clonePupukItem(containerSelector) {
                    let newItem = $(`${containerSelector} .pupuk-item:first`).clone();
                    newItem.find('input').val('');
                    newItem.find('select').prop('selectedIndex', 0);
                    $(containerSelector).append(newItem);
                }
            </script>
            <x-pesanan.modal-add order_no="{{ $order_no }}" :barangs="$barangs" />
            <x-pesanan.modal-edit order_no="{{ $order_no }}" :barangs="$barangs" />
        @endif
        <x-alert />
    @endpush
</x-dashboard-layout>
