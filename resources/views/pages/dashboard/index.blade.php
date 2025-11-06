<x-dashboard-layout :title="$title">
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
    <div class="page-title">
        <h1>{{ $title }}</h1>
        <div class="grid grid-cols-3 grid-rows-4 gap-4">
            <div class="row-span-full bg-red-400">card (pelanggan, barang, produksi, transaksi)</div>
            <div class="col-span-2 row-span-2 bg-black">graph produksi</div>
            <div class="col-span-2 row-span-2 bg-amber-300">graph transaksi</div>
        </div>
    </div>

</x-dashboard-layout>
