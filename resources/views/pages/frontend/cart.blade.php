<x-frontend-layout :title="$title">
    @if ($errors->any())
        @push('scripts')
            <script>
                const errors = `{!! '<ul>' . collect($errors->all())->map(fn($e) => "<li>{$e}</li>")->implode('') . '</ul>' !!}`;

                Swal.fire({
                    icon: "error",
                    title: "Terjadi Kesalahan!",
                    html: errors,
                    confirmButtonText: "OK"
                });
            </script>
        @endpush
    @endif
    <div class="mb-3">
        <h1 class="font-bold pt-5 text-sm "><span class="font-normal">Beranda</span> / Cart</h1>
    </div>
    <div class="grid gap-5 grid-cols-[2fr_1fr]">
        <ul class="list rounded-box shadow-md h-max">
            @forelse ($carts as $item)
                <li class="list-row">
                    <div><img class="size-10 rounded-box" src="{{ asset('storage/' . $item->barang->gambar) }}" />
                    </div>
                    <div>
                        <div>{{ $item->barang->nama }}</div>
                        <div class="text-xs uppercase font-semibold opacity-60">Rp
                            {{ number_format($item->barang->harga, 2, ',', '.') }}</div>
                    </div>
                    <div class="flex items-center border border-gray-300 rounded-md overflow-hidden">
                        <form action="{{ route('cart.updateQty') }}" method="POST">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $item->pupuk_id }}">
                            <input type="hidden" name="action" value="decrease">
                            <button type="submit"
                                class="px-3 py-2 bg-gray-100 rounded hover:bg-gray-200 cursor-pointer">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                        </form>
                        <input type="text" name="qty" id="qty" value="{{ $item->qty_karung }}"
                            min="1" max="10" class="w-10 text-center border-none focus:outline-none"
                            required>
                        <form action="{{ route('cart.updateQty') }}" method="POST">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $item->pupuk_id }}">
                            <input type="hidden" name="action" value="increase">
                            <button type="submit"
                                class="px-3 py-2 bg-gray-100 rounded hover:bg-gray-200 cursor-pointer">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </form>
                    </div>
                    <form action="{{ route('cart.destroy', [$item->pesanan_id, $item->pupuk_id]) }}" method="POST"
                        class="delete-form">
                        @method('delete')
                        @csrf
                        <button type="submit" class=" btn btn-square btn-error text-white"><i
                                class="fa-solid fa-trash"></i></button>
                    </form>
                </li>
            @empty
                <li class="p-5 text-center">Keranjang belanja kosong</li>
            @endforelse
        </ul>
        <div class=" rounded-box shadow-md p-5 flex flex-col items-start h-max">
            <h1 class="font-bold text-2xl">Total Keranjang Belanja</h1>
            <div class="mt-3">
                <table class="text-left text-lg">
                    <tr>
                        <th>Pengiriman</th>
                        <td class="pl-2">:</td>
                        <td class="pl-3">{{ Auth()->user()->user_data->alamat ?? 'Alamat Belum Diisi' }}</td>
                    </tr>
                    @php
                        $total = 0;
                        foreach ($carts as $item) {
                            $total += $item->subtotal;
                        }
                    @endphp
                    <tr>
                        <th>Total</th>
                        <td class="pl-2">:</td>
                        <td class="pl-3 font-bold text-primary">Rp {{ number_format($total, 2, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
            <a href="{{ route('checkout') }}" class="btn btn-primary mt-5 w-full">Checkout</a>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script>
            $(document).on('submit', '.delete-form', function(e) {
                e.preventDefault();

                const form = this;

                Swal.fire({
                    title: "Yakin ingin menghapus?",
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal",

                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        </script>
    @endpush
</x-frontend-layout>
