<x-dashboard-layout :title="$title">
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.tailwindcss.min.css">
    @endpush

    <div class="page-title">
        <h1>{{ $title }}</h1>
    </div>

    <div class="mt-4 w-full">
        @if (Auth::user()->hasRole(['admin', 'karyawan']))
            <div class="flex justify-end mb-3 gap-x-2">
                <a href="{{ route('dashboard.transaksi.pesanan.previewPdf') }}" target="_blank"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                </a>
                <button data-modal-target="addPesanan" data-modal-toggle="addPesanan"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm">
                    + Tambah Pesanan
                </button>
            </div>
        @endif
    </div>

    <table id="pesananTable" data-dt-theme="light" class="mt-3">
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
                let table = $('#pesananTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('dashboard.transaksi.pesanan') }}",
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'order_no',
                            name: 'order_no',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'tanggal_transaksi',
                            name: 'tanggal_transaksi',
                            orderable: true,
                            searchable: false
                        },
                        {
                            data: 'user_data.nama',
                            name: 'user_data.nama',
                            orderable: false,
                            searchable: true,
                            orderable: false,
                            render: function(data, type, row) {
                                return data ? data : (row.pengiriman ? row.pengiriman.nama_penerima :
                                    'Guest User');
                            }
                        },
                        {
                            data: 'total_bayar',
                            name: 'total_bayar',
                            orderable: true,
                            searchable: false,
                            render: function(data, type, row) {
                                $total = parseInt(data);
                                $ongkir = parseInt(row.pengiriman?.ongkir) || 0;

                                $total_bayar = $total + $ongkir;
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR'
                                }).format($total_bayar);
                            }
                        },
                        {
                            data: 'order_type',
                            name: 'order_type',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'channel',
                            name: 'channel',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'payment_status',
                            name: 'payment_status',
                            orderable: true,
                            searchable: true,
                            render: function(data) {
                                return data ? data : '-';
                            }
                        },
                        {
                            data: 'fulfillment_status',
                            name: 'fulfillment_status',
                            orderable: true,
                            searchable: true,
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
            <x-pesanan.modal-add :provinsi="$provinsi" order_no="{{ $order_no }}" :barangs="$barangs" />
            <x-pesanan.modal-edit :provinsi="$provinsi" order_no="{{ $order_no }}" :barangs="$barangs" />
        @endif
        <x-alert />
    @endpush
</x-dashboard-layout>
