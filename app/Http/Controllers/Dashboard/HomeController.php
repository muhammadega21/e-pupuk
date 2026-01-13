<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Produksi;
use App\Models\Pupuk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // =============================
        // QUERY DASAR (TANPA GROUP BY)
        // =============================
        $baseQuery = Pesanan::query();

        if (! $user->hasRole(['admin', 'karyawan'])) {
            $baseQuery->where('created_by', $user->user_id);
        }

        // =============================
        // CARD (HITUNG DATA ASLI)
        // =============================
        $totalTransaksi   = (clone $baseQuery)->count();
        $unpaidTransaksi  = (clone $baseQuery)->where('payment_status', 'unpaid')->count();
        $pendingTransaksi = (clone $baseQuery)->where('payment_status', 'pending')->count();
        $paidTransaksi    = (clone $baseQuery)->where('payment_status', 'paid')->count();

        // =============================
        // GRAPH (QUERY TERPISAH)
        // =============================
        $transaksiGraph = Pesanan::query();

        if (! $user->hasRole(['admin', 'karyawan'])) {
            $transaksiGraph->where('created_by', $user->user_id);
        }

        $transaksiGraph = $transaksiGraph
            ->selectRaw('payment_status, COUNT(*) as total')
            ->groupBy('payment_status')
            ->get();


        return view('pages.dashboard.index', [
            'title' => 'Dashboard',

            // Card
            'totalTransaksi'   => $totalTransaksi,
            'unpaidTransaksi'  => $unpaidTransaksi,
            'pendingTransaksi' => $pendingTransaksi,
            'paidTransaksi'    => $paidTransaksi,

            // Graph
            'transaksiLabels' => $transaksiGraph->pluck('payment_status'),
            'transaksiData'   => $transaksiGraph->pluck('total'),

            // Card admin lainnya
            'totalUser'      => User::count(),
            'totalProduk'    => Pupuk::count(),
            'totalProduksi'  => Produksi::sum('jumlah_karung'),
            'totalOmzet'     => Pesanan::where('payment_status', 'paid')->sum('total_bayar'),
        ]);
    }
}
