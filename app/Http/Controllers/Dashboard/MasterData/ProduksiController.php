<?php

namespace App\Http\Controllers\Dashboard\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Produksi;
use App\Models\Pupuk;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProduksiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Produksi::with('barang')->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $deleteForm = '
                    <form action="' . route('dashboard.master-data.produksi.destroy', $row->produksi_id) . '" method="POST" class="delete-form" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">Hapus</button>
                    </form>
                ';
                    $editBtn = '<button data-id="' . $row->produksi_id . '" class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm mr-2">Edit</button>';

                    return  $editBtn . $deleteForm;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $barangs = Pupuk::select(['barang_id', 'nama'])->get();
        return view('pages.dashboard.master-data.produksi', [
            'title' => 'Data Produksi',
            'barangs' => $barangs
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required',
            'tanggal_produksi' => 'required',
            'jumlah_karung' => 'required',
        ], [
            'barang_id.required' => 'Barang harus diisi',
            'tanggal_produksi.required' => 'Tanggal Produksi harus diisi',
            'jumlah_karung.required' => 'Jumlah Karung harus diisi',
        ]);

        Produksi::create([
            'barang_id' => $request->barang_id,
            'tanggal_produksi' => $request->tanggal_produksi,
            'jumlah_karung' => $request->jumlah_karung,
            'note' => $request->note
        ]);

        Pupuk::where('barang_id', $request->barang_id)->increment('stok', $request->jumlah_karung);

        return redirect()->route('dashboard.master-data.produksi')->with('success', 'Produksi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $produksi = Produksi::with('barang')->findOrFail($id);
        return response()->json($produksi);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required',
            'tanggal_produksi' => 'required',
            'jumlah_karung' => 'required',
        ], [
            'barang_id.required' => 'Barang harus diisi',
            'tanggal_produksi.required' => 'Tanggal Produksi harus diisi',
            'jumlah_karung.required' => 'Jumlah Karung harus diisi',
        ]);

        Pupuk::where('barang_id', $request->barang_id)
            ->decrement('stok', Produksi::where('produksi_id', $id)->first()->jumlah_karung);

        Produksi::findOrFail($id)->update([
            'barang_id' => $request->barang_id,
            'tanggal_produksi' => $request->tanggal_produksi,
            'jumlah_karung' => $request->jumlah_karung,
            'note' => $request->note
        ]);

        Pupuk::where('barang_id', $request->barang_id)
            ->increment('stok', $request->jumlah_karung);

        return redirect()->route('dashboard.master-data.produksi')->with('success', 'Produksi berhasil diubah');
    }

    public function destroy($id)
    {
        $produksi = Produksi::findOrFail($id);
        Pupuk::where('barang_id', $id)
            ->decrement('stok', $produksi->jumlah_karung);
        Produksi::findOrFail($id)->delete();
        return redirect()->route('dashboard.master-data.produksi')->with('success', 'Produksi berhasil dihapus');
    }
}
