<x-dashboard-layout :title="$title">
    <div class="page-title">
        <h1>{{ $title }}</h1>
    </div>
    <div class="my-4 h-full">
        @if (Auth::user()->hasRole(['admin', 'karyawan']))
            <div class="flex flex-col md:flex-row gap-y-6 gap-x-4 h-full">
                <div class="w-full h-max md:w-1/2 grid grid-cols-2  gap-3">
                    {{-- Total User --}}
                    <div class="bg-white rounded-xl shadow p-4 flex items-center gap-4">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pelanggan</p>
                            <h2 class="text-2xl font-bold">{{ $totalUser }}</h2>
                        </div>
                    </div>

                    {{-- Total Omzet --}}
                    <div class="bg-white rounded-xl shadow p-4 flex items-center gap-4">
                        <div class="p-3 rounded-full bg-emerald-100 text-emerald-600">
                            <i class="fas fa-wallet text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Omzet</p>
                            <h2 class="text-xl font-bold">
                                Rp {{ number_format($totalOmzet, 0, ',', '.') }}
                            </h2>
                        </div>
                    </div>

                    {{-- Total Produk --}}
                    <div class="bg-white rounded-xl shadow p-4 flex items-center gap-4">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-seedling text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Produk</p>
                            <h2 class="text-2xl font-bold">{{ $totalProduk }}</h2>
                        </div>
                    </div>

                    {{-- Total Produksi --}}
                    <div class="bg-white rounded-xl shadow p-4 flex items-center gap-4">
                        <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                            <i class="fas fa-industry text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Produksi</p>
                            <h2 class="text-2xl font-bold">{{ number_format($totalProduksi) }} Karung</h2>
                        </div>
                    </div>

                    {{-- Total Transaksi --}}
                    <div class="bg-white rounded-xl shadow p-4 flex items-center gap-4">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <i class="fas fa-file-invoice text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Transaksi</p>
                            <h2 class="text-2xl font-bold">{{ $totalTransaksi }}</h2>
                        </div>
                    </div>

                </div>
                <div class="w-full md:w-1/2 p-4 rounded-md shadow-md">
                    <canvas id="transaksiGraph"></canvas>
                </div>
            </div>
        @else
            <div class="flex flex-col md:flex-row gap-y-6 gap-x-4 h-full">

                <div class="w-full h-max md:w-1/2 grid grid-cols-2 gap-3">
                    {{-- Total Transaksi --}}
                    <div class="bg-white rounded-xl shadow p-4 flex items-center gap-4">
                        <div class="p-3 rounded-full bg-slate-100 text-slate-600">
                            <i class="fas fa-list-check text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Transaksi</p>
                            <h2 class="text-2xl font-bold">{{ $totalTransaksi }}</h2>
                        </div>
                    </div>

                    {{-- Unpaid --}}
                    <div class="bg-white rounded-xl shadow p-4 flex items-center gap-4">
                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                            <i class="fas fa-circle-xmark text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Unpaid</p>
                            <h2 class="text-2xl font-bold">{{ $unpaidTransaksi }}</h2>
                        </div>
                    </div>

                    {{-- Pending --}}
                    <div class="bg-white rounded-xl shadow p-4 flex items-center gap-4">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pending</p>
                            <h2 class="text-2xl font-bold">{{ $pendingTransaksi }}</h2>
                        </div>
                    </div>

                    {{-- Paid --}}
                    <div class="bg-white rounded-xl shadow p-4 flex items-center gap-4">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-circle-check text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Paid</p>
                            <h2 class="text-2xl font-bold">{{ $paidTransaksi }}</h2>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/2 p-4 rounded-md shadow-md">
                    <canvas id="transaksiGraph"></canvas>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            new Chart(document.getElementById('transaksiGraph'), {
                type: 'doughnut',
                data: {
                    labels: @json($transaksiLabels),
                    datasets: [{
                        label: 'Jumlah Transaksi',
                        data: @json($transaksiData),
                        backgroundColor: [
                            '#22c55e',
                            '#facc15',
                            '#3b82f6',
                            '#ef4444',
                            '#94a3b8'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        </script>
    @endpush


    @if (session()->has('success'))
        @push('scripts')
            <script>
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    showCloseButton: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: "{{ session('success') }}"
                });
            </script>
        @endpush
    @endif
</x-dashboard-layout>
