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

    public function updatePembayaran(Request $request, $id)
    {
        $request->validate([
            'total_bayar' => 'required|numeric|max:1000000000',
        ], [
            'total_bayar.required' => 'Total bayar wajib diisi.',
            'total_bayar.numeric' => 'Total bayar harus berupa angka.',
            'total_bayar.max' => 'Total bayar terlalu besar.',
        ]);
        $pesanan = Pesanan::with(['pembayaran'])->findOrFail($id);
        $statusBayar = $request->input('status_bayar');
        $totalBayar = $request->input('total_bayar');

        $updatedData = [
            'payment_status' => $statusBayar === "Terima" ? "paid" : "unpaid"
        ];

        $pesanan->update($updatedData);

        $pembayaranData = [
            'status' => $statusBayar === "Terima" ? "verified" : "rejected",
            'total_bayar' => $statusBayar === "Terima" ? $totalBayar : 0,
        ];

        $pesanan->pembayaran()->update($pembayaranData);

        return redirect()->back()->with('success', 'Status pembayaran berhasil diubah');
    }

    public function updatePengiriman(Request $request, $id)
    {
        $pesanan = Pesanan::with(['pengiriman'])->findOrFail($id);
        $statusPengiriman = $request->input('status');
        $updatedData = [
            'fulfillment_status' => $statusPengiriman == 'pending' ? 'new' : $statusPengiriman
        ];
        $pesanan->update($updatedData);

        $pengirimanData = [
            'nama_penerima' => $request->input('nama_penerima'),
            'telepon' => $request->input('telepon'),
            'alamat' => $request->input('alamat'),
            'ongkir' => $request->input('ongkir'),
            'tgl_kirim' => $request->input('tgl_kirim'),
            'tgl_terima' => $request->input('tgl_terima'),
            'status' => $statusPengiriman,
        ];

        $pesanan->pengiriman()->update($pengirimanData);

        return redirect()->back()->with('success', 'Status pengiriman berhasil diubah');
    }
}
