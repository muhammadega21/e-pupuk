<?php

namespace App\Http\Controllers\Dashboard\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserData;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with(['role', 'user_data'])->whereIn('role_id', [1, 2])->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $deleteForm = '
                    <form action="' . route('dashboard.master-data.karyawan.destroy', $row->user_id) . '" method="POST" class="delete-form" style="display:inline;">
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

        $roles = Role::where('role_id', '<=', '2')->select(['role_id', 'role_name'])->get();

        return view('pages.dashboard.master-data.karyawan', [
            'title' => 'Data Karyawan',
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'role_id' => 'required',
            'email'       => 'required|email|unique:user,email',
            'password'    => 'required|string|min:6',
            'alamat'      => 'required',
            'telepon'  => 'required'
        ], [
            'nama.required' => 'Nama harus diisi',
            'role_id.required' => 'Role harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'alamat.required' => 'Alamat harus diisi',
            'telepon.required' => 'Nomor Telepon harus diisi',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id
        ]);

        UserData::create([
            'user_id' => $user->user_id,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon
        ]);

        return redirect()->route('dashboard.master-data.karyawan')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $karyawan = User::with(['role', 'user_data'])->where('user_id', $id)->first();
        return response()->json($karyawan);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'role_id' => 'required',
            'alamat'      => 'required',
            'telepon'  => 'required'
        ], [
            'nama.required' => 'Nama harus diisi',
            'role_id.required' => 'Role harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'telepon.required' => 'Nomor Telepon harus diisi',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'role_id' => $request->role_id,
            'status' => $request->status
        ]);

        $user->user_data()->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon
        ]);

        return redirect()->route('dashboard.master-data.karyawan')->with('success', 'Karyawan berhasil diubah');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('dashboard.master-data.karyawan')->with('success', 'Karyawan berhasil dihapus');
    }
}
