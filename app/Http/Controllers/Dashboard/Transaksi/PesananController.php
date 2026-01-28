<?php

namespace App\Http\Controllers\Dashboard\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\Province;
use App\Models\Pupuk;
use Barryvdh\DomPDF\Facade\Pdf;
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

            if (Auth::user()->hasRole('pelanggan')) {
                $data = Pesanan::where('handled_by', Auth::id())->with(['user_data', 'pengiriman'])->latest();
            } else {
                $data = Pesanan::with(['user_data', 'pengiriman'])->latest();
            }

            if ($request->has('start_date') && $request->start_date) {
                $data->whereDate('tanggal_transaksi', '>=', $request->start_date);
            }

            if ($request->has('end_date') && $request->end_date) {
                $data->whereDate('tanggal_transaksi', '<=', $request->end_date);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if ($row->fulfillment_status == 'delivered') {
                        $deliveryConfirmBtn = '<span class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded-md text-sm mr-2">Sudah Diterima</span>';
                    } else if ($row->channel == 'cart') {
                        if (Auth::user()->hasRole('pelanggan')) {
                            $deliveryConfirmBtn = '
                            <a href="' . route('checkout') . '" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md text-sm mr-2">Lanjutkan Pembayaran</a>';
                        } else {
                            $deliveryConfirmBtn = '';
                        }
                    } else {
                        $deliveryConfirmBtn = '
                            <form action="' . route('dashboard.transaksi.pesanan.confirm-delivery', $row->pesanan_id) . '" method="POST" class="confirm-delivery inline-block mr-2">
                                ' . csrf_field() . '
                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md text-sm">Konfirmasi Terima</button>
                            </form>';
                    }
                    $editBtn = '<button data-id="' . $row->pesanan_id . '" class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm mr-2">Edit</button>';
                    $detailBtn = '<a href="' . route('dashboard.transaksi.detail-pesanan', $row->pesanan_id) . '" class="detail-btn bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-sm">Detail</a>';
                    $invoiceBtn = '
                                <a href="' . route('dashboard.transaksi.pesanan.invoice', $row->pesanan_id) . '"
                                target="_blank"
                                class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded-md text-sm mr-2">
                                    <i class="fa-solid fa-receipt"></i> Invoice
                                </a>';
                    $deleteForm = '
                        <form action="' . route('dashboard.transaksi.pesanan.destroy', $row->pesanan_id) . '" method="POST" class="delete-form inline-block ml-1">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">Hapus</button>
                        </form>';
                    if (Auth::user()->hasRole('pelanggan')) {
                        return $invoiceBtn . $deliveryConfirmBtn . $detailBtn;
                    } else {
                        return $invoiceBtn . $deliveryConfirmBtn . $editBtn . $detailBtn . $deleteForm;
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $barangs = Pupuk::select(['pupuk_id', 'nama', 'harga'])->get();
        $order_no = 'ORD-' . str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $provinsi = Province::whereIn('id', ['11', '12', '13', '14', '15', '16', '17', '18', '19'])->get();

        return view('pages.dashboard.transaksi.pesanan', [
            'title' => 'Data Pesanan',
            'barangs' => $barangs,
            'order_no' => $order_no,
            'provinsi' => $provinsi
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'order_no' => 'required|string',
            'order_type' => 'required|in:pickup,delivery',
            'pupuk_id' => 'required|array|min:1',
            'pupuk_id.*' => 'exists:pupuk,pupuk_id',
            'total_karung' => 'required|array|min:1',
            'total_karung.*' => 'numeric|min:1',
            'total_bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,transfer',
            'bukti_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'nama_penerima' => 'required_if:order_type,delivery',
            'telepon' => 'required_if:order_type,delivery',
            'alamat' => 'required_if:order_type,delivery',
            'ongkir' => 'required_if:order_type,delivery',

        ]);

        DB::transaction(function () use ($validated, $request) {
            $buktiPath = null;
            if ($request->hasFile('bukti_url')) {
                $buktiPath = Storage::disk('public')->putFile('bukti_pembayaran', $request->file('bukti_url'));
            }

            // Simpan pesanan utama
            $payment_status = $validated['metode_pembayaran'] === 'cash' ? 'paid' : 'pending';
            $fillfillment_status = $validated['order_type'] === 'delivery' ? 'new' : 'shipped';
            $pesanan = Pesanan::create([
                'created_by' => Auth::user()->user_id,
                'handled_by' => Auth::user()->user_id,
                'tanggal_transaksi' => now(),
                'order_no' => $validated['order_no'],
                'channel' => 'store',
                'order_type' => $validated['order_type'],
                'payment_status' => $payment_status,
                'fulfillment_status' => $fillfillment_status,
                'total_karung' => array_sum($validated['total_karung']),
                'total_bayar' => $validated['total_bayar'],
            ]);

            foreach ($validated['pupuk_id'] as $i => $barangId) {
                $barang = Pupuk::find($barangId);
                $qty = $validated['total_karung'][$i];
                $subtotal = $barang->harga * $qty;

                DetailPesanan::create([
                    'pesanan_id' => $pesanan->pesanan_id,
                    'pupuk_id' => $barangId,
                    'qty_karung' => $qty,
                    'subtotal' => $subtotal,
                ]);
            }

            if ($validated['order_type'] === 'delivery') {
                Pengiriman::create([
                    'pesanan_id' => $pesanan->pesanan_id,
                    'nama_penerima' => $validated['nama_penerima'],
                    'telepon' => $validated['telepon'],
                    'alamat' => $validated['alamat'],
                    'tgl_kirim' => now(),
                    'status' => 'pending',
                    'ongkir' => $validated['ongkir'],
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

            foreach ($validated['pupuk_id'] as $i => $barangId) {
                Pupuk::where('pupuk_id', $barangId)->decrement('stok', $validated['total_karung'][$i]);
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

        if ($pesanan->payment_status === 'paid' && $pesanan->fulfillment_status === 'delivered') {
            return back()->with('error', 'Pesanan sudah dibayar dan dikirim, tidak dapat diedit.');
        }

        if ($pesanan->payment_status === 'paid') {
            $validated = $request->validate([
                'nama_penerima' => 'required_if:order_type,delivery',
                'telepon' => 'required_if:order_type,delivery',
                'alamat' => 'required_if:order_type,delivery',
            ]);

            if ($pesanan->fulfillment_status !== 'delivered' && $pesanan->order_type === 'delivery') {

                $pesanan->pengiriman()->updateOrCreate(
                    ['pesanan_id' => $pesanan->pesanan_id],
                    [
                        'nama_penerima' => $validated['nama_penerima'],
                        'telepon' => $validated['telepon'],
                        'alamat' => $validated['alamat'],
                        'tgl_kirim' => now(),
                    ]
                );
            }

            return redirect()->route('dashboard.transaksi.pesanan')
                ->with('success', 'Data pengiriman berhasil diperbarui.');
        }

        if ($pesanan->fulfillment_status === 'delivered') {
            $validated = $request->validate([
                'order_type' => 'required|in:pickup,delivery',
                'pupuk_id' => 'required|array|min:1',
                'pupuk_id.*' => 'exists:pupuk,pupuk_id',
                'total_karung' => 'required|array|min:1',
                'total_karung.*' => 'numeric|min:1',
                'total_bayar' => 'required|numeric|min:0',
                'metode_pembayaran' => 'required|in:cash,transfer',
                'bukti_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
                    Pupuk::where('pupuk_id', $detail->pupuk_id)->increment('stok', $detail->qty_karung);
                    $detail->delete();
                }

                foreach ($validated['pupuk_id'] as $i => $barangId) {
                    $barang = Pupuk::find($barangId);
                    $qty = $validated['total_karung'][$i];
                    $subtotal = $barang->harga * $qty;

                    DetailPesanan::create([
                        'pesanan_id' => $pesanan->pesanan_id,
                        'pupuk_id' => $barangId,
                        'qty_karung' => $qty,
                        'subtotal' => $subtotal,
                    ]);

                    Pupuk::where('pupuk_id', $barangId)->decrement('stok', $qty);
                }

                $status = $validated['metode_pembayaran'] === 'cash' ? 'verified' : 'pending';
                $pesanan->pembayaran->update([
                    'metode' => $validated['metode_pembayaran'],
                    'bukti_url' => $buktiPath,
                    'total_bayar' => $validated['total_bayar'],
                    'status' => $status,
                ]);
            });

            return redirect()->route('dashboard.transaksi.pesanan')
                ->with('success', 'Data pembayaran berhasil diperbarui.');
        }

        $validated = $request->validate([
            'order_type' => 'required|in:pickup,delivery',
            'pupuk_id' => 'required|array|min:1',
            'pupuk_id.*' => 'exists:pupuk,pupuk_id',
            'total_karung' => 'required|array|min:1',
            'total_karung.*' => 'numeric|min:1',
            'total_bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,transfer',
            'bukti_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nama_penerima' => 'required_if:order_type,delivery',
            'telepon' => 'required_if:order_type,delivery',
            'alamat' => 'required_if:order_type,delivery',
            'ongkir' => 'required_if:order_type,delivery|numeric|min:0',
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
                Pupuk::where('pupuk_id', $detail->pupuk_id)->increment('stok', $detail->qty_karung);
                $detail->delete();
            }

            foreach ($validated['pupuk_id'] as $i => $barangId) {
                $barang = Pupuk::find($barangId);
                $qty = $validated['total_karung'][$i];
                $subtotal = $barang->harga * $qty;

                DetailPesanan::create([
                    'pesanan_id' => $pesanan->pesanan_id,
                    'pupuk_id' => $barangId,
                    'qty_karung' => $qty,
                    'subtotal' => $subtotal,
                ]);

                Pupuk::where('pupuk_id', $barangId)->decrement('stok', $qty);
            }

            if ($validated['order_type'] === 'delivery') {
                $pesanan->pengiriman()->updateOrCreate(
                    ['pesanan_id' => $pesanan->pesanan_id],
                    [
                        'nama_penerima' => $validated['nama_penerima'],
                        'telepon' => $validated['telepon'],
                        'alamat' => $validated['alamat'],
                        'ongkir' => $validated['ongkir'],
                        'tgl_kirim' => now(),
                    ]
                );
            } else {
                if ($pesanan->pengiriman) {
                    $pesanan->pengiriman->delete();
                }
            }

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

    public function confirmDelivery($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        if ($pesanan->fulfillment_status === 'delivered') {
            return redirect()->route('dashboard.transaksi.pesanan')
                ->with('success', 'Pesanan sudah dikonfirmasi diterima.');
        }

        $pesanan->update([
            'fulfillment_status' => 'delivered',
        ]);

        if ($pesanan->pengiriman) {
            $pesanan->pengiriman->update([
                'status' => 'delivered',
                'tgl_terima' => now(),
            ]);
        }

        return redirect()->route('dashboard.transaksi.pesanan')
            ->with('success', 'Pesanan berhasil dikonfirmasi diterima.');
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
            return 2000;
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

    public function previewPdf()
    {
        $pesanan = Pesanan::with(['user_data', 'pembayaran', 'pengiriman'])
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        return view('pages.dashboard.transaksi.pdf', [
            'pesanan' => $pesanan,
        ]);
    }

    public function invoice($id)
    {
        $pesanan = Pesanan::with([
            'user_data',
            'detailPesanan.barang',
            'pengiriman',
            'pembayaran'
        ])->findOrFail($id);

        return view('pages.dashboard.transaksi.invoice', compact('pesanan'));
    }

    public function ajaxHitungOngkir(Request $request)
    {
        $alamat = $request->alamat; // "Kecamatan, Kota, Provinsi"
        $ongkir = $this->hitungOngkir($alamat);

        return response()->json([
            'ongkir' => $ongkir,
            'formatted' => number_format($ongkir, 2, ',', '.')
        ]);
    }
}
