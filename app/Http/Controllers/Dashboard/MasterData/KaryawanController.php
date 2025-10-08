<?php

namespace App\Http\Controllers\Dashboard\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.master-data.karyawan', [
            'title' => 'Data Karyawan'
        ]);
    }
}
