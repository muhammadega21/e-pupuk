<?php

namespace App\Http\Controllers\Dashboard\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\Pupuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pesanan::with(['user_data']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editBtn = '<button data-id="' . $row->pesanan_id . '" class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm mr-2">Edit</button>';
                    $detailBtn = '<button data-id="' . $row->pesanan_id . '" class="detail-btn bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-sm">Detail</button>';
                    $deleteForm = '
                        <form action="' . route('dashboard.transaksi.pesanan.destroy', $row->pesanan_id) . '" method="POST" class="delete-form inline-block ml-1">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">Hapus</button>
                        </form>';

                    return $editBtn . $detailBtn . $deleteForm;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $barangs = Pupuk::select(['barang_id', 'nama', 'harga'])->get();
        $order_no = 'ORD-' . str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        return view('pages.dashboard.transaksi.pesanan', [
            'title' => 'Data Pesanan',
            'barangs' => $barangs,
            'order_no' => $order_no
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_no' => 'required|string',
            'order_type' => 'required|in:pickup,delivery',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'exists:pupuk,barang_id',
            'total_karung' => 'required|array|min:1',
            'total_karung.*' => 'numeric|min:1',
            'total_bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,transfer',
            'bukti_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'nama_penerima' => 'required_if:order_type,delivery',
            'telepon' => 'required_if:order_type,delivery',
            'alamat' => 'required_if:order_type,delivery',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $buktiPath = null;
            if ($request->hasFile('bukti_url')) {
                $buktiPath = Storage::disk('public')->putFile('bukti_pembayaran', $request->file('bukti_url'));
            }

            // Simpan pesanan utama
            $payment_status = $validated['metode_pembayaran'] === 'cash' ? 'paid' : 'pending';
            $pesanan = Pesanan::create([
                'created_by' => Auth::user()->user_id,
                'handled_by' => Auth::user()->user_id,
                'tanggal_transaksi' => now(),
                'order_no' => $validated['order_no'],
                'channel' => 'store',
                'order_type' => $validated['order_type'],
                'payment_status' => $payment_status,
                'fulfillment_status' => 'new',
                'total_karung' => array_sum($validated['total_karung']),
                'total_bayar' => $validated['total_bayar'],
            ]);

            foreach ($validated['barang_id'] as $i => $barangId) {
                $barang = Pupuk::find($barangId);
                $qty = $validated['total_karung'][$i];
                $subtotal = $barang->harga * $qty;

                DetailPesanan::create([
                    'pesanan_id' => $pesanan->pesanan_id,
                    'barang_id' => $barangId,
                    'qty_karung' => $qty,
                    'subtotal' => $subtotal,
                ]);
            }

            $ongkir = $this->hitungOngkir($request->alamat);

            if ($validated['order_type'] === 'delivery') {
                Pengiriman::create([
                    'pesanan_id' => $pesanan->pesanan_id,
                    'nama_penerima' => $validated['nama_penerima'],
                    'telepon' => $validated['telepon'],
                    'alamat' => $validated['alamat'],
                    'ongkir' => $ongkir < 1000 ? 0 : $ongkir,
                    'tgl_kirim' => now(),
                    'status' => 'pending',
                ]);
            }

            $status = $validated['metode_pembayaran'] === 'cash' ? 'verified' : 'pending';
            Pembayaran::create([
                'pesanan_id' => $pesanan->pesanan_id,
                'tanggal' => now(),
                'metode' => $validated['metode_pembayaran'],
                'bukti_url' => $buktiPath,
                'total_bayar' => $validated['total_bayar'],
                'status' => $status,
            ]);

            foreach ($validated['barang_id'] as $i => $barangId) {
                Pupuk::where('barang_id', $barangId)->decrement('stok', $validated['total_karung'][$i]);
            }
        });

        return redirect()->route('dashboard.transaksi.pesanan')
            ->with('success', 'Pesanan berhasil dibuat.');
    }

    public function edit($id)
    {
        $pesanan = Pesanan::with(['detailPesanan.barang', 'pengiriman', 'pembayaran'])->findOrFail($id);

        return response()->json($pesanan);
    }

    public function update(Request $request, $id)
    {
        $pesanan = Pesanan::with(['detailPesanan', 'pembayaran', 'pengiriman'])->findOrFail($id);

        $validated = $request->validate([
            'order_type' => 'required|in:pickup,delivery',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'exists:pupuk,barang_id',
            'total_karung' => 'required|array|min:1',
            'total_karung.*' => 'numeric|min:1',
            'total_bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,transfer',
            'bukti_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nama_penerima' => 'required_if:order_type,delivery',
            'telepon' => 'required_if:order_type,delivery',
            'alamat' => 'required_if:order_type,delivery',
        ]);

        DB::transaction(function () use ($validated, $request, $pesanan) {
            $buktiPath = $pesanan->pembayaran->bukti_url;
            if ($request->hasFile('bukti_url')) {
                if ($buktiPath) {
                    Storage::disk('public')->delete($buktiPath);
                }
                $buktiPath = Storage::disk('public')->putFile('bukti_pembayaran', $request->file('bukti_url'));
            }

            $pesanan->update([
                'order_type' => $validated['order_type'],
                'total_karung' => array_sum($validated['total_karung']),
                'total_bayar' => $validated['total_bayar'],
            ]);

            foreach ($pesanan->detailPesanan as $detail) {
                Pupuk::where('barang_id', $detail->barang_id)->increment('stok', $detail->qty_karung);
                $detail->delete();
            }

            foreach ($validated['barang_id'] as $i => $barangId) {
                $barang = Pupuk::find($barangId);
                $qty = $validated['total_karung'][$i];
                $subtotal = $barang->harga * $qty;

                DetailPesanan::create([
                    'pesanan_id' => $pesanan->pesanan_id,
                    'barang_id' => $barangId,
                    'qty_karung' => $qty,
                    'subtotal' => $subtotal,
                ]);

                Pupuk::where('barang_id', $barangId)->decrement('stok', $qty);
            }

            if ($validated['order_type'] === 'delivery') {
                $ongkir = $this->hitungOngkir($request->alamat);
                $pesanan->pengiriman()->updateOrCreate(
                    ['pesanan_id' => $pesanan->pesanan_id],
                    [
                        'nama_penerima' => $validated['nama_penerima'],
                        'telepon' => $validated['telepon'],
                        'alamat' => $validated['alamat'],
                        'ongkir' => $ongkir < 1000 ? 0 : $ongkir,
                        'tgl_kirim' => now(),
                    ]
                );
            } else {
                if ($pesanan->pengiriman) {
                    $pesanan->pengiriman->delete();
                }
            }

            // Update pembayaran
            $status = $validated['metode_pembayaran'] === 'cash' ? 'verified' : 'pending';
            $pesanan->pembayaran->update([
                'metode' => $validated['metode_pembayaran'],
                'bukti_url' => $buktiPath,
                'total_bayar' => $validated['total_bayar'],
                'status' => $status,
            ]);
        });

        return redirect()->route('dashboard.transaksi.pesanan')
            ->with('success', 'Pesanan berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pembayaran = $pesanan->pembayaran;
        if ($pembayaran->bukti_url) {
            Storage::disk('public')->delete($pembayaran->bukti_url);
        }
        $pesanan->delete();
        return redirect()->route('dashboard.transaksi.pesanan')
            ->with('success', 'Pesanan berhasil dihapus.');
    }

    private function hitungOngkir($alamat)
    {
        $apiKey = env('OPENCAGE_API_KEY');
        $response = Http::get('https://api.opencagedata.com/geocode/v1/json', [
            'q' => $alamat,
            'key' => $apiKey,
            'language' => 'id',
            'limit' => 1,
        ]);

        $data = $response->json();

        if (empty($data['results'])) {
            return 0;
        }

        $latTujuan = $data['results'][0]['geometry']['lat'];
        $lngTujuan = $data['results'][0]['geometry']['lng'];

        $latToko = -0.913602;
        $lngToko = 100.352629;

        $jarak = $this->hitungJarak($latToko, $lngToko, $latTujuan, $lngTujuan);

        $ongkir = round($jarak * 2000);

        return $ongkir;
    }

    function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $radius = 6371; // radius bumi dalam kilometer
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $radius * $c;
    }
}
