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
        <div class="flex gap-x-3 justify-end mt-4">
            @if (Auth::user()->hasRole(['admin', 'karyawan']))
                @if ($pesanan->pengiriman)
                    <button data-modal-target="updatePengiriman" data-modal-toggle="updatePengiriman"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm">Update
                        Pengiriman</button>
                @endif
                <button data-modal-target="updatePembayaran" data-modal-toggle="updatePembayaran"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm">Update
                    Pembayaran</button>
            @endif
            <a href="{{ route('dashboard.transaksi.pesanan') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm">Kembali</a>
        </div>
    </div>

    <x-modal id="updatePembayaran" title="Cek Bukti Pembayaran">
        <form action="{{ route('dashboard.transaksi.detail-pesanan.update-pembayaran', $pesanan->pesanan_id) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <img id="preview_edit_pembayaran_bukti"
                    class="w-48 h-auto mt-2 rounded-md hidden cursor-pointer hover:scale-[102%] transition-all duration-100 ease-in-out"
                    alt="Preview Bukti Pembayaran">
            </div>

            <div class="mb-3 flex justify-between">
                <table>
                    <tr>
                        <td class="font-semibold">Nomor Pesanan</td>
                        <td>: {{ $pesanan->order_no }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Total Bayar</td>
                        <td>: Rp {{ number_format($pesanan->pembayaran->total_bayar, 2, ',', '.') }}</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td class="font-semibold">Tanggal</td>
                        <td>: {{ \Carbon\Carbon::parse($pesanan->pembayaran->tanggal)->format('d, F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Status</td>
                        <td>: {{ $pesanan->pembayaran->status }}</td>
                    </tr>
                </table>
            </div>

            <div class="mb-3">
                <label class="block font-medium text-gray-700">Konfirmasi Jumlah Saldo Masuk</label>
                <input type="text" name="total_bayar" id="total_bayar"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    value="{{ $pesanan->pembayaran->total_bayar }}" required>
            </div>
            <div class="flex items-center justify-between mt-3">
                <button data-modal-hide="updatePembayaran" type="button"
                    class="py-2.5 px-5 text-sm font-medium bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                    Kembali
                </button>
                @if ($pesanan->pembayaran->status === 'verified')
                    <span class="text-green-600 font-semibold">Pembayaran sudah diterima.</span>
                @else
                    <div class="flex gap-x-3">
                        <input type="submit" name="status_bayar"
                            class="cursor-pointer text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
                            value="Tolak">
                        <input type="submit" name="status_bayar"
                            class="cursor-pointer text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
                            value="Terima">
                    </div>
                @endif
            </div>
        </form>
    </x-modal>

    @if ($pesanan->pengiriman)
        <x-modal id="updatePengiriman" title="Update Pengiriman">
            <form action="{{ route('dashboard.transaksi.detail-pesanan.update-pengiriman', $pesanan->pesanan_id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Nomor Pesanan</label>
                    <input type="text" value="{{ $pesanan->order_no }}" readonly
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 bg-gray-100 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Penerima</label>
                        <input type="text" name="nama_penerima" id="nama_penerima"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"
                            value="{{ $pesanan->pengiriman->nama_penerima }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Telepon Penerima</label>
                        <input type="text" name="telepon" id="telepon"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"
                            value="{{ $pesanan->pengiriman->telepon }}">
                    </div>
                </div>

                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700">Alamat Penerima</label>
                    <textarea name="alamat" id="alamat" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1">{{ $pesanan->pengiriman->alamat }}</textarea>
                </div>

                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700">Ongkos Kirim</label>
                    <input type="number" name="ongkir" id="ongkir"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"
                        value="{{ $pesanan->pengiriman->ongkir }}">
                </div>
                <div class="grid grid-cols-2 gap-3 mt-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Kirim</label>
                        <input type="date" name="tgl_kirim" id="tgl_kirim"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"
                            value="{{ \Carbon\Carbon::parse($pesanan->pengiriman->tgl_kirim)->format('Y-m-d') }}"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Terima</label>
                        <input type="date" name="tgl_terima" id="tgl_terima"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"
                            value="{{ $pesanan->pengiriman->tgl_terima ? \Carbon\Carbon::parse($pesanan->pengiriman->tgl_terima)->format('Y-m-d') : '' }}">
                    </div>
                </div>

                @php
                    $status_pengiriman = [
                        'pending' => 'Pending',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                    ];
                @endphp

                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700">Status Pengiriman</label>
                    <select name="status" id="status"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1">
                        @foreach ($status_pengiriman as $key => $value)
                            <option value="{{ $key }}"
                                {{ $pesanan->pengiriman->status == $key ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-x-3 items-center justify-end mt-3">
                    <button data-modal-hide="updatePengiriman" type="button"
                        class="py-2.5 px-5 text-sm font-medium bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                        Batal
                    </button>
                    @if ($pesanan->pengiriman->status === 'delivered')
                        <span class="text-green-600 font-semibold">Barang sudah diterima.</span>
                    @else
                        <button type="submit"
                            class="cursor-pointer text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Update</button>
                    @endif
                </div>
            </form>
        </x-modal>
    @endif

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

        <script>
            const buktiUrl = '{{ $pesanan->pembayaran->bukti_url ? '/storage/' . $pesanan->pembayaran->bukti_url : '' }}';
            const preview = document.getElementById('preview_edit_pembayaran_bukti');
            preview.src = buktiUrl;
            if (buktiUrl) {
                preview.classList.remove('hidden');
                preview.addEventListener('click', function() {
                    window.open(this.src, '_blank');
                });
            }
        </script>
    @endpush
    <x-alert />
</x-dashboard-layout>
