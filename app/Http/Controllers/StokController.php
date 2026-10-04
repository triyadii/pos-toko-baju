<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use App\Models\StokLog;
use App\Models\BarangVarian;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    public function index()
    {
        $stoks = Stok::with(['barangVarian.barang', 'barangVarian.warna', 'barangVarian.ukuran'])->orderBy('id', 'desc')->get();
        $barang_varians = BarangVarian::with(['barang', 'warna', 'ukuran'])->get();
        return view('stoks.index', compact('stoks', 'barang_varians'));
    }

    public function adjust(Request $request)
    {
        $request->validate([
            'barang_varian_id' => 'required|exists:barang_varians,id',
            'tipe' => 'required|in:masuk,keluar,penyesuaian',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            $stok = Stok::firstOrCreate(
                ['barang_varian_id' => $request->barang_varian_id],
                ['uuid' => Str::uuid(), 'jumlah_stok' => 0]
            );

            $stok_sebelum = $stok->jumlah_stok;
            $jumlah = $request->jumlah;
            
            if ($request->tipe == 'keluar') {
                $stok_sesudah = $stok_sebelum - $jumlah;
            } else {
                $stok_sesudah = $stok_sebelum + $jumlah; // masuk / penyesuaian (tambah)
            }

            $stok->update(['jumlah_stok' => $stok_sesudah]);

            StokLog::create([
                'uuid' => Str::uuid(),
                'barang_varian_id' => $request->barang_varian_id,
                'tipe' => $request->tipe,
                'jumlah' => $jumlah,
                'stok_sebelum' => $stok_sebelum,
                'stok_sesudah' => $stok_sesudah,
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id() ?? 1 // Fallback to 1 if not logged in via seeder or testing
            ]);
        });

        return redirect()->route('stoks.index')->with('success', 'Stok berhasil disesuaikan');
    }
}
