<?php

namespace App\Http\Controllers\Dashboard\MasterData;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserData;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('user_data')->where('role_id', 3)->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $deleteForm = '
                    <form action="' . route('dashboard.master-data.pelanggan.destroy', $row->user_id) . '" method="POST" class="delete-form" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">Hapus</button>
                    </form>
                ';
                    $editBtn = '<button data-id="' . $row->user_id . '" class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm mr-2">Edit</button>';

                    return  $editBtn . $deleteForm;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.dashboard.master-data.pelanggan', [
            'title' => 'Data Pelanggan'
        ]);
    }

    public function edit($id)
    {
        $pelanggan = User::with('user_data')->where('user_id', $id)->first();
        return response()->json($pelanggan);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'alamat'      => 'required',
            'telepon'  => 'required',
            'status'  => 'required'
        ], [
            'nama.required' => 'Nama harus diisi',
            'role_id.required' => 'Role harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'telepon.required' => 'Nomor Telepon harus diisi',
            'status.required' => 'Status harus diisi',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'status' => $request->status
        ]);

        $user->user_data()->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon
        ]);

        return redirect()->route('dashboard.master-data.pelanggan')->with('success', 'Pelanggan berhasil diubah');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('dashboard.master-data.pelanggan')->with('success', 'Pelanggan berhasil dihapus');
    }
}
