<?php

namespace App\Http\Controllers\Dashboard\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::select(['role_id', 'role_name']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editBtn = '<button data-id="' . $row->role_id . '" class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm mr-2">Edit</button>';
                    $deleteForm = '
                    <form action="' . route('dashboard.master-data.role.destroy', $row->role_id) . '" method="POST" class="delete-form" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">Hapus</button>
                    </form>
                    ';
                    return $editBtn . $deleteForm;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.dashboard.master-data.role', [
            'title' => 'Data Role'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|unique:role,role_name'
        ], [
            'role_name.required' => 'Nama role harus diisi',
            'role_name.unique' => 'Role sudah ada',
        ]);

        Role::create([
            'role_name' => $request->role_name
        ]);

        return redirect()->route('dashboard.master-data.role')->with('success', 'Role berhasil ditambahkan');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role_name' => 'required|unique:role,role_name,' . $id . ',role_id'

        ], [
            'role_name.required' => 'Nama role harus diisi',
            'role_name.unique' => 'Role sudah ada',
        ]);

        Role::findOrFail($id)->update([
            'role_name' => $request->role_name
        ]);

        return redirect()->route('dashboard.master-data.role')->with('success', 'Role berhasil diubah');
    }

    public function destroy($id)
    {
        Role::findOrFail($id)->delete();
        return redirect()->route('dashboard.master-data.role')->with('success', 'Role berhasil dihapus');
    }
}
