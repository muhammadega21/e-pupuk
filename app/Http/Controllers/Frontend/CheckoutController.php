<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Pupuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function checkout()
    {
        $pesanan = Pesanan::where('created_by', Auth::id())
            ->where('channel', 'cart')
            ->with('detailPesanan.barang', 'detailPesanan.pesanan.user_data')
            ->first();

        $ongkir = $pesanan && Pembayaran::where('pesanan_id', $pesanan->pesanan_id)->exists()
            ? Pembayaran::where('pesanan_id', $pesanan->pesanan_id)->first()->ongkir
            : 0;
        return view('pages.frontend.checkout', [
            'title' => 'Checkout',
            'pesanan' => $pesanan,
            'ongkir' => $ongkir,
        ]);
    }

    public function checkoutStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string',
            'bukti_url' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.max' => 'Nama maksimal 100 karakter.',
            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.max' => 'Alamat maksimal 255 karakter.',
            'telepon.required' => 'Telepon wajib diisi.',
            'bukti_url.required' => 'Bukti pembayaran wajib diunggah.',
            'bukti_url.image' => 'Bukti pembayaran harus berupa gambar.',
            'bukti_url.mimes' => 'Format gambar harus jpg, jpeg, atau png.',
            'bukti_url.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $user = Auth::user();

        $cart = Pesanan::where('created_by', $user->user_id)
            ->where('channel', 'cart')
            ->with(['detailPesanan', 'pengiriman', 'pembayaran'])
            ->first();

        if (!$cart || $cart->detailPesanan->isEmpty()) {
            return back()->with('error', 'Keranjang masih kosong.');
        }

        DB::transaction(function () use ($cart, $request, $user) {
            $buktiPath = null;
            if ($request->hasFile('bukti_url')) {
                $buktiPath = $request->file('bukti_url')->store('bukti_pembayaran', 'public');
            }

            $payment_status = $request->metode_pembayaran === 'cash' ? 'paid' : 'pending';
            $pembayaran_status = $request->metode_pembayaran === 'cash' ? 'verified' : 'pending';

            $cart->update([
                'channel' => 'online',
                'order_type' => 'delivery',
                'payment_status' => $payment_status,
                'fulfillment_status' => 'new',
                'tanggal_transaksi' => now(),
            ]);

            $pembayaran = Pembayaran::where('pesanan_id', $cart->pesanan_id)->first();

            if ($pembayaran) {
                $pembayaran->update([
                    'tanggal' => now(),
                    'metode' => 'transfer',
                    'bukti_url' => $buktiPath ?? $pembayaran->bukti_url,
                    'total_bayar' => $cart->total_bayar,
                    'status' => $pembayaran_status,
                ]);
            } else {
                Pembayaran::create([
                    'pesanan_id' => $cart->pesanan_id,
                    'tanggal' => now(),
                    'metode' => 'transfer',
                    'bukti_url' => $buktiPath,
                    'total_bayar' => $cart->total_bayar,
                    'status' => $pembayaran_status,
                ]);
            }

            if ($cart->pengiriman) {
                $cart->pengiriman->update([
                    'status' => 'pending',
                    'tgl_kirim' => now(),
                ]);
            }

            foreach ($cart->detailPesanan as $detail) {
                Pupuk::where('pupuk_id', $detail->pupuk_id)
                    ->decrement('stok', $detail->qty_karung);
            }
        });

        return redirect()->route('frontend.checkout.success')
            ->with('success', 'Pesanan berhasil dibuat. Terima kasih sudah berbelanja!');
    }

    public function checkoutSuccess()
    {
        $pesanan = Pesanan::where('created_by', Auth::id())
            ->where('channel', 'store')
            ->latest()
            ->with(['pembayaran', 'pengiriman', 'detailPesanan.barang'])
            ->first();

        return view('pages.frontend.checkout-success', [
            'title' => 'Checkout Berhasil',
            'pesanan' => $pesanan,
        ]);
    }
}
