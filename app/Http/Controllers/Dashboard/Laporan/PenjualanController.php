<?php

namespace App\Http\Controllers\Dashboard\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{

    public function index()
    {

        return view('pages.dashboard.laporan.penjualan', [
            'title' => 'Laporan Penjualan'
        ]);
    }
}
