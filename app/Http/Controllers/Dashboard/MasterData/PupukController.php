<?php

namespace App\Http\Controllers\Dashboard\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Pupuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PupukController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pupuk::with('gambar')->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('gambar', function ($row) {
                    $gambar = $row->gambar->first();

                    if ($gambar && Storage::disk('public')->exists($gambar->gambar_url)) {
                        return '<img src="' . asset('storage/' . $gambar->gambar_url) . '" class="w-14 h-14 object-cover rounded">';
                    }

                    return '<span class="text-gray-400 italic text-sm">Tidak ada</span>';
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<button data-id="' . $row->pupuk_id . '" class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm mr-2">Edit</button>';
                    $deleteForm = '
                        <form action="' . route('dashboard.master-data.pupuk.destroy', $row->pupuk_id) . '" method="POST" class="delete-form inline">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">Hapus</button>
                        </form>';
                    return $editBtn . $deleteForm;
                })
                ->rawColumns(['gambar', 'action'])
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
            'berat' => 'required|integer',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'deskripsi' => 'required|string',
            'status' => 'required',
            'unggulan' => 'nullable|boolean',
            'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pupuk = Pupuk::create([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'berat' => $request->berat,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'status' => $request->status,
            'unggulan' => $request->boolean('unggulan'),
            'slug' => Str::slug($request->nama),
            'deskripsi' => $request->deskripsi ?? null,
        ]);

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $path = $file->store('pupuk', 'public');

                $pupuk->gambar()->create([
                    'gambar_url' => $path,
                ]);
            }
        }

        return redirect()->route('dashboard.master-data.pupuk')
            ->with('success', 'Pupuk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pupuk = Pupuk::findOrFail($id);
        return response()->json($pupuk);
    }

    public function update(Request $request, $id)
    {
        $pupuk = Pupuk::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'jenis' => 'required',
            'berat' => 'required|integer',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'deskripsi' => 'required|string',
            'status' => 'required',
            'unggulan' => 'nullable|boolean',
            'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pupuk->update([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'berat' => $request->berat,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'status' => $request->status,
            'unggulan' => $request->boolean('unggulan'),
            'slug' => Str::slug($request->nama),
            'deskripsi' => $request->deskripsi ?? null,
        ]);

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $path = $file->store('pupuk', 'public');

                $pupuk->gambar()->create([
                    'gambar_url' => $path,
                ]);
            }
        }

        return redirect()->route('dashboard.master-data.pupuk')
            ->with('success', 'Pupuk berhasil diubah');
    }


    public function destroy($id)
    {
        $pupuk = Pupuk::with('gambar')->findOrFail($id);

        foreach ($pupuk->gambar as $gambar) {
            if (Storage::disk('public')->exists($gambar->gambar_url)) {
                Storage::disk('public')->delete($gambar->gambar_url);
            }
        }

        $pupuk->delete();

        return redirect()->route('dashboard.master-data.pupuk')
            ->with('success', 'Pupuk berhasil dihapus');
    }
}
