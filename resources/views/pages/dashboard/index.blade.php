<x-dashboard-layout :title="$title">
    <div class="page-title">
        <h1>{{ $title }}</h1>
        <div class="grid grid-cols-3 grid-rows-4 gap-4">
            <div class="row-span-full bg-red-400">card (pelanggan, barang, produksi, transaksi)</div>
            <div class="col-span-2 row-span-2">
                <canvas id="transaksiGraph"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // ============================
            // TRANSAKSI POLAR AREA CHART
            // ============================
            new Chart(document.getElementById('transaksiGraph'), {
                type: 'pie',
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
                            position: 'right'
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
