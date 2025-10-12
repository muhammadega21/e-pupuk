<?php

namespace App\Http\Controllers\Dashboard\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Pupuk;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PupukController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pupuk::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $deleteForm = '
                    <form action="' . route('dashboard.master-data.pupuk.destroy', $row->barang_id) . '" method="POST" class="delete-form" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">Hapus</button>
                    </form>
                ';
                    $editBtn = '<button data-id="' . $row->barang_id . '" class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm mr-2">Edit</button>';

                    return  $editBtn . $deleteForm;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        return view('pages.dashboard.master-data.pupuk', [
            'title' => 'Data Pupuk',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jenis' => 'required',
            'berat' => 'required',
            'harga' => 'required',
            'stok' => 'required',
            'status' => 'required',
        ], [
            'nama.required' => 'Nama harus diisi',
            'jenis.required' => 'Jenis harus diisi',
            'berat.required' => 'Berat harus diisi',
            'harga.required' => 'Harga harus diisi',
            'stok.required' => 'Stok harus diisi',
            'status.required' => 'Status harus diisi',
        ]);

        Pupuk::create([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'berat' => $request->berat,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'status' => $request->status
        ]);

        return redirect()->route('dashboard.master-data.pupuk')->with('success', 'Pupuk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pupuk = Pupuk::findOrFail($id);
        return response()->json($pupuk);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'jenis' => 'required',
            'berat' => 'required',
            'harga' => 'required',
            'stok' => 'required',
            'status' => 'required',

        ], [
            'nama.required' => 'Nama harus diisi',
            'jenis.required' => 'Jenis harus diisi',
            'berat.required' => 'Berat harus diisi',
            'harga.required' => 'Harga harus diisi',
            'stok.required' => 'Stok harus diisi',
            'status.required' => 'Status harus diisi',
        ]);

        Pupuk::findOrFail($id)->update([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'berat' => $request->berat,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'status' => $request->status
        ]);

        return redirect()->route('dashboard.master-data.pupuk')->with('success', 'Pupuk berhasil diubah');
    }

    public function destroy($id)
    {
        Pupuk::findOrFail($id)->delete();
        return redirect()->route('dashboard.master-data.pupuk')->with('success', 'Pupuk berhasil dihapus');
    }
}
