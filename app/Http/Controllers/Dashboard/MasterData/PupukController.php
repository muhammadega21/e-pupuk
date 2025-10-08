<?php

namespace App\Http\Controllers\Dashboard\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PupukController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.master-data.pupuk', [
            'title' => 'Data Pupuk'
        ]);
    }
}
