<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pupuk;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $transaksi = Pesanan::selectRaw('payment_status, COUNT(*) as total')
            ->groupBy('payment_status')
            ->get();
        return view('pages.dashboard.index', [
            'title' => 'Dashboard',
            'transaksiLabels' => $transaksi->pluck('payment_status'),
            'transaksiData' => $transaksi->pluck('total'),
        ]);
    }
}
