<?php

namespace App\Http\Controllers\Dashboard\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProduksiController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.master-data.produksi', [
            'title' => 'Data Produksi'
        ]);
    }
}
