<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use App\Models\Pupuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function cart()
    {
        $carts = Pesanan::where('created_by', Auth::id())
            ->where('channel', 'cart')
            ->with('detailPesanan.barang')
            ->first();

        return view('pages.frontend.cart', [
            'title' => 'Keranjang Belanja',
            'carts' => $carts->detailPesanan ?? [],
        ]);
    }

    public function cartStore(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:pupuk,barang_id',
            'qty' => 'required|integer|min:1',
        ], [
            'produk_id.required' => 'Produk harus dipilih.',
            'produk_id.exists' => 'Produk tidak ditemukan.',
            'qty.required' => 'Jumlah produk harus diisi.',
            'qty.integer' => 'Jumlah produk harus berupa angka.',
            'qty.min' => 'Jumlah produk minimal adalah 1.',
        ]);

        $user = Auth::user();
        $produk = Pupuk::findOrFail($request->produk_id);
        $qty = (int) $request->qty;
        $subtotal = $produk->harga * $qty;
        $order_no = 'ORD-' . str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        DB::transaction(function () use ($user, $produk, $qty, $subtotal, $order_no) {
            $cart = Pesanan::where('created_by', $user->user_id)
                ->where('channel', 'cart')
                ->first();

            if (!$cart) {
                $cart = Pesanan::create([
                    'data_user_id' => $user->user_id,
                    'created_by' => $user->user_id,
                    'handled_by' => $user->user_id,
                    'tanggal_transaksi' => now(),
                    'order_no' => $order_no,
                    'channel' => 'cart',
                    'order_type' => 'delivery',
                    'payment_status' => 'unpaid',
                    'fulfillment_status' => 'new',
                    'total_karung' => 0,
                    'total_bayar' => 0,
                ]);

                Pembayaran::create([
                    'pesanan_id' => $cart->pesanan_id,
                    'tanggal' => now(),
                    'metode' => 'transfer',
                    'bukti_url' => null,
                    'total_bayar' => 0,
                    'status' => 'pending',
                ]);

                Pengiriman::create([
                    'pesanan_id' => $cart->pesanan_id,
                    'nama_penerima' => Auth::user()->user_data->nama ?? null,
                    'telepon' => Auth::user()->user_data->telepon ?? null,
                    'alamat' => Auth::user()->user_data->alamat ?? null,
                    'ongkir' => 2000,
                    'tgl_kirim' => null,
                    'status' => 'pending',
                ]);
            }

            $detail = DetailPesanan::where('pesanan_id', $cart->pesanan_id)
                ->where('barang_id', $produk->barang_id)
                ->first();

            if ($detail) {
                $detail->qty_karung += $qty;
                $detail->subtotal += $subtotal;
                $detail->save();
            } else {
                DetailPesanan::create([
                    'pesanan_id' => $cart->pesanan_id,
                    'barang_id' => $produk->barang_id,
                    'qty_karung' => $qty,
                    'subtotal' => $subtotal,
                ]);
            }

            $cart->update([
                'total_karung' => DetailPesanan::where('pesanan_id', $cart->pesanan_id)->sum('qty_karung'),
                'total_bayar' => DetailPesanan::where('pesanan_id', $cart->pesanan_id)->sum('subtotal'),
            ]);

            Pembayaran::where('pesanan_id', $cart->pesanan_id)
                ->update(['total_bayar' => $cart->total_bayar]);
        });

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function updateQty(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:pupuk,barang_id',
            'action' => 'required|in:increase,decrease',
        ], [
            'produk_id.required' => 'Produk harus dipilih.',
            'produk_id.exists' => 'Produk tidak ditemukan.',
            'action.required' => 'Aksi harus ditentukan.',
            'action.in' => 'Aksi tidak valid.',
        ]);

        $pesanan = Pesanan::where('created_by', Auth::id())
            ->where('channel', 'cart')
            ->latest()
            ->first();

        if (!$pesanan) {
            return back()->with('error', 'Keranjang tidak ditemukan.');
        }

        $detail = DetailPesanan::where('pesanan_id', $pesanan->pesanan_id)
            ->where('barang_id', $request->produk_id)
            ->first();

        if (!$detail) {
            return back()->with('error', 'Produk tidak ditemukan di keranjang.');
        }

        if ($request->action === 'increase') {
            if ($detail->qty_karung < $detail->barang->stok) {
                $detail->qty_karung++;
            }
        } elseif ($request->action === 'decrease') {
            if ($detail->qty_karung > 1) {
                $detail->qty_karung--;
            }
        }

        $detail->subtotal = $detail->qty_karung * $detail->barang->harga;
        $detail->save();

        $pesanan->total_karung = DetailPesanan::where('pesanan_id', $pesanan->pesanan_id)->sum('qty_karung');
        $pesanan->total_bayar = DetailPesanan::where('pesanan_id', $pesanan->pesanan_id)->sum('subtotal');
        $pesanan->save();

        return back()->with('success', 'Jumlah produk diperbarui.');
    }



    public function cartDestroy($pesanan_id, $barang_id)
    {
        $pesanan = Pesanan::where('pesanan_id', $pesanan_id)
            ->where('created_by', Auth::id())
            ->where('channel', 'cart')
            ->firstOrFail();

        $detailPesanan = DetailPesanan::where('pesanan_id', $pesanan->pesanan_id)
            ->where('barang_id', $barang_id)
            ->first();

        if (!$detailPesanan) {
            return back()->with('error', 'Produk tidak ditemukan dalam keranjang.');
        }

        $detailPesanan->delete();

        $sisaItem = DetailPesanan::where('pesanan_id', $pesanan->pesanan_id)->count();

        if ($sisaItem === 0) {
            $pesanan->delete();
        } else {
            $pesanan->total_karung = DetailPesanan::where('pesanan_id', $pesanan->pesanan_id)->sum('qty_karung');
            $pesanan->total_bayar = DetailPesanan::where('pesanan_id', $pesanan->pesanan_id)->sum('subtotal');
            $pesanan->save();
        }

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
