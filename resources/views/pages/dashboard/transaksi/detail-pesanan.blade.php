<x-dashboard-layout :title="$title">
    <div class="page-title mx-0 md:mx-5 mt-5">
        <h1>{{ $title }}</h1>
    </div>

    <div class="flex mt-4 mx-0 md:mx-5 flex-col md:flex-row gap-5 md:gap-20 justify-between md:justify-start">
        <div>
            <h2 class="font-bold">Informasi Pesanan</h2>
            <table class="text-left">
                <tr>
                    <th class="font-normal">Nomor Order</th>
                    <td>: {{ $pesanan->order_no }}</td>
                </tr>
                <tr>
                    <th class="font-normal">Tanggal</th>
                    <td>: {{ \Carbon\Carbon::parse($pesanan->tanggal_transaksi)->format('d, F Y') }}</td>
                </tr>
                <tr>
                    <th class="font-normal">Status Pembayaran</th>
                    <td>: {{ $pesanan->payment_status }}</td>
                </tr>
                <tr>
                    <th class="font-normal">Status Pengiriman</th>
                    <td>: {{ $pesanan->fulfillment_status ?? '-' }}</td>
                </tr>
            </table>
        </div>
        <div>
            @php
                $nama_pelanggan = $pesanan->user_data
                    ? $pesanan->user_data->nama
                    : ($pesanan->pengiriman
                        ? $pesanan->pengiriman->nama_penerima
                        : 'Guest User');
                $telepon = $pesanan->user_data
                    ? $pesanan->user_data->telepon
                    : ($pesanan->pengiriman
                        ? $pesanan->pengiriman->telepon
                        : '');
                $alamat = $pesanan->user_data
                    ? $pesanan->user_data->alamat
                    : ($pesanan->pengiriman
                        ? $pesanan->pengiriman->alamat
                        : '');
            @endphp
            <h2 class="font-bold">Informasi Pelanggan</h2>
            <table class="text-left">
                <tr>
                    <th class="font-normal">Nama</th>
                    <td>: {{ $nama_pelanggan }}
                    </td>
                </tr>
                <tr>
                    <th class="font-normal">Telepon</th>
                    <td>: {{ $telepon }}</td>
                </tr>
                <tr>
                    <th class="font-normal">Alamat</th>
                    <td>: {{ $alamat }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="mt-4 mx-0 md:mx-5 mb-5">
        <h2 class="font-bold">Detail Pesanan</h2>

        <div class="relative overflow-x-auto shadow-md rounded my-2 mb-4">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-white uppercase bg-[var(--color-primary)]">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama Pupuk
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jumlah Karung
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Harga Satuan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Subtotal
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pesanan->detailPesanan as $item)
                        <tr class="bg-white border-b border-gray-200">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $loop->iteration }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $item->barang->nama }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->qty_karung }}
                            </td>
                            <td class="px-6 py-4">
                                Rp {{ number_format($item->barang->harga, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                Rp {{ number_format($item->subtotal, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-left flex justify-end ">
            @php
                $total_bayar = ($pesanan->total_bayar ?? 0) + ($pesanan->pengiriman->ongkir ?? 0);
            @endphp
            <table>
                <tr>
                    <th class="font-normal">Subtotal</th>
                    <td>: Rp {{ number_format($pesanan->total_bayar, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <th class="font-normal">Metode Pengiriman</th>
                    <td>: {{ $pesanan->order_type }}</td>
                </tr>
                <tr>
                    <th class="font-normal">Ongkir</th>
                    <td>: Rp {{ number_format($pesanan->pengiriman->ongkir ?? 0, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <th class="font-normal">Total Pembayaran</th>
                    <td>: Rp {{ number_format($total_bayar, 2, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <th class="font-normal">Metode Pembayaran</th>
                    <td>: {{ $pesanan->pembayaran->metode }}</td>
                </tr>
            </table>
        </div>
    </div>
</x-dashboard-layout>
