<x-frontend-layout :title="$title">
    <section class="pt-4">
        <div class="mb-4">
            <h1 class="font-bold text-sm"><span class="font-normal">Beranda</span> / Semua Produk</h1>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($pupuk as $item)
                <div
                    class="card bg-base-100 border border-gray-200 shadow-sm h-max hover:shadow-lg hover:scale-102 transition-all duration-300">
                    <figure>
                        <img src="{{ asset('/storage/' . $item->gambar) }}" alt="{{ $item->nama }}" />
                    </figure>
                    <div class="card-body border-t border-gray-200">
                        <h2 class="card-title">{{ $item->nama }} ({{ $item->berat }} Kg)</h2>
                        <p class="text-lg font-bold text-primary">Rp
                            {{ number_format($item->harga, 2, ',', '.') }}</p>
                        <div class="card-actions justify-end">
                            <a href="{{ route('produk.detail', $item->slug) }}" class="btn btn-primary w-full">Lihat
                                Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</x-frontend-layout>
