<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Pupuk;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.frontend.index', [
            'title' => 'Beranda',
            'pupuk' => Pupuk::latest()->paginate(6),
        ]);
    }

    public function produkDetail($slug)
    {
        $pupuk = Pupuk::where('slug', $slug)->firstOrFail();
        $pupukLainnya = Pupuk::where('barang_id', '!=', $pupuk->barang_id)->inRandomOrder()->take(4)->get();

        return view('pages.frontend.detail', [
            'title' => $pupuk->nama . ' (' . $pupuk->berat . ' Kg)',
            'pupuk' => $pupuk,
            'pupukLainnya' => $pupukLainnya
        ]);
    }
}
