<?php

namespace App\Http\Controllers\Dashboard\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class DetailPesananController extends Controller
{
    public function index($id)
    {
        $pesanan = Pesanan::with(['detailPesanan', 'pengiriman', 'pembayaran', 'user_data'])->findOrFail($id);
        return view('pages.dashboard.transaksi.detail-pesanan', [
            'title' => 'Detail Pesanan',
            'pesanan' => $pesanan
        ]);
    }
}
