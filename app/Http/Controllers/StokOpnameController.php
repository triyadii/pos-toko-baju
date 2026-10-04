<?php

namespace App\Http\Controllers;

use App\Models\StokOpname;
use App\Models\StokOpnameDetail;
use App\Models\BarangVarian;
use App\Models\Stok;
use App\Models\StokLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StokOpnameController extends Controller
{
    public function index()
    {
        $opnames = StokOpname::with(['user', 'details.barangVarian.barang'])->orderBy('created_at', 'desc')->get();
        return view('stok_opnames.index', compact('opnames'));
    }

    public function create()
    {
        $varians = BarangVarian::with(['barang', 'warna', 'ukuran', 'stok'])->get();
        return view('stok_opnames.create', compact('varians'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:barang_varians,id',
            'items.*.fisik' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $opname = StokOpname::create([
                'uuid' => Str::uuid(),
                'nomor_opname' => 'SOP-' . date('YmdHis'),
                'tanggal_opname' => now(),
                'user_id' => Auth::id() ?? 1,
                'status' => 'selesai',
                'keterangan' => $request->keterangan,
            ]);

            foreach ($request->items as $item) {
                $varian = BarangVarian::findOrFail($item['id']);
                $stokRecord = Stok::firstOrCreate(
                    ['barang_varian_id' => $varian->id],
                    ['uuid' => Str::uuid(), 'jumlah_stok' => 0]
                );

                $stokSistem = $stokRecord->jumlah_stok;
                $stokFisik = $item['fisik'];
                $selisih = $stokFisik - $stokSistem;

                if ($selisih != 0) {
                    StokOpnameDetail::create([
                        'uuid' => Str::uuid(),
                        'stok_opname_id' => $opname->id,
                        'barang_varian_id' => $varian->id,
                        'stok_sistem' => $stokSistem,
                        'stok_fisik' => $stokFisik,
                        'selisih' => $selisih,
                    ]);

                    // Update Stock & Log
                    $stokRecord->update(['jumlah_stok' => $stokFisik]);

                    StokLog::create([
                        'uuid' => Str::uuid(),
                        'barang_varian_id' => $varian->id,
                        'tipe' => 'penyesuaian',
                        'jumlah' => abs($selisih),
                        'stok_sebelum' => $stokSistem,
                        'stok_sesudah' => $stokFisik,
                        'referensi_id' => $opname->nomor_opname,
                        'keterangan' => 'Stok Opname',
                        'user_id' => Auth::id() ?? 1,
                    ]);
                }
            }
        });

        return redirect()->route('stok-opnames.index')->with('success', 'Stok Opname berhasil disimpan.');
    }
}
