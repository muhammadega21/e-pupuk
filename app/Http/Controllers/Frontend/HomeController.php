<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Pupuk;
use App\Models\Pesanan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.frontend.index', [
            'title' => 'Beranda',
            'pupuk' => Pupuk::with('gambar')->latest()->paginate(6),
        ]);
    }

    public function produkFeatured()
    {
        return view('pages.frontend.featured', [
            'title' => 'Produk Unggulan',
            'pupuk' => Pupuk::with('gambar')->where('unggulan', true)->latest()->paginate(6),
        ]);
    }

    public function about()
    {
        return view('pages.frontend.about', [
            'title' => 'Tentang Kami'
        ]);
    }

    public function produkDetail($slug)
    {
        $pupuk = Pupuk::with('gambar')->where('slug', $slug)->firstOrFail();
        $pupukLainnya = Pupuk::with('gambar')->where('pupuk_id', '!=', $pupuk->pupuk_id)->inRandomOrder()->take(4)->get();

        return view('pages.frontend.detail', [
            'title' => $pupuk->nama . ' (' . $pupuk->berat . ' Kg)',
            'pupuk' => $pupuk,
            'pupukLainnya' => $pupukLainnya
        ]);
    }
}
