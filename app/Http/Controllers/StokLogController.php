<?php

namespace App\Http\Controllers;

use App\Models\StokLog;
use Illuminate\Http\Request;

class StokLogController extends Controller
{
    public function index()
    {
        $stok_logs = StokLog::with(['barangVarian.barang', 'barangVarian.warna', 'barangVarian.ukuran', 'user'])->orderBy('id', 'desc')->get();
        return view('stok_logs.index', compact('stok_logs'));
    }
}
