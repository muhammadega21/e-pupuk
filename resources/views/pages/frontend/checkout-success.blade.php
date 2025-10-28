<x-frontend-layout :title="$title">
    <div class="flex flex-col items-center justify-center min-h-[70vh] p-10 text-center">
        <div class="bg-white shadow-lg rounded-2xl p-8 md:p-12 max-w-xl">
            <div class="flex justify-center mb-6">
                <i class="fa-solid fa-circle-check text-green-500 text-6xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Pesanan Berhasil!</h1>
            <p class="text-gray-600 mb-6">
                Terima kasih telah berbelanja di <span class="font-semibold">Toko Pupuk Sriwindo</span>.
                Pesanan Anda sedang kami proses.
            </p>

            @if ($pesanan)
                <div class="text-left border-t border-gray-200 pt-4">
                    <p class="text-gray-700"><span class="font-semibold">Nomor Pesanan:</span> {{ $pesanan->order_no }}
                    </p>
                    <p class="text-gray-700"><span class="font-semibold">Total Pembayaran:</span>
                        Rp {{ number_format($pesanan->total_bayar, 2, ',', '.') }}</p>
                    <p class="text-gray-700"><span class="font-semibold">Status Pembayaran:</span>
                        {{ ucfirst($pesanan->pembayaran->status ?? 'pending') }}</p>
                    <p class="text-gray-700"><span class="font-semibold">Status Pengiriman:</span>
                        {{ ucfirst($pesanan->pengiriman->status ?? 'pending') }}</p>
                </div>

                <div class="mt-6">
                    <h2 class="font-semibold mb-2 text-gray-800">Detail Produk:</h2>
                    <ul class="space-y-2">
                        @foreach ($pesanan->detailPesanan as $item)
                            <li class="flex justify-between border-b border-gray-100 pb-1">
                                <span>{{ $item->barang->nama }} × {{ $item->qty_karung }}</span>
                                <span>Rp {{ number_format($item->subtotal, 2, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-8 flex justify-center">
                <a href="{{ route('home') }}"
                    class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                    Kembali ke Beranda
                </a>
                <a href="{{ route('dashboard.transaksi.pesanan') }}"
                    class="ml-4 bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                    Lihat Pesanan
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            Swal.fire({
                icon: "success",
                title: "Pesanan Berhasil!",
                text: "Pesanan Anda akan segera kami proses.",
                showConfirmButton: false,
                timer: 3000,
                toast: true,
                position: 'top-end',
                showCloseButton: true
            });
        </script>
    @endpush
</x-frontend-layout>
