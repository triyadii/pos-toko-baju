<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\JenisBarang;
use App\Models\StatusBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with(['jenisBarang', 'statusBarang'])->orderBy('id', 'desc')->get();
        $jenis_barangs = JenisBarang::all();
        $status_barangs = StatusBarang::all();
        return view('barangs.index', compact('barangs', 'jenis_barangs', 'status_barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_barang_id' => 'required|exists:jenis_barangs,id',
            'status_barang_id' => 'required|exists:status_barangs,id',
            'nama_barang' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
        ]);

        $latestBarang = Barang::orderBy('id', 'desc')->first();
        $nextId = $latestBarang ? $latestBarang->id + 1 : 1;
        $kode_barang = 'BRG-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        Barang::create([
            'uuid' => Str::uuid(),
            'jenis_barang_id' => $request->jenis_barang_id,
            'status_barang_id' => $request->status_barang_id,
            'kode_barang' => $kode_barang,
            'nama_barang' => $request->nama_barang,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
        ]);

        return redirect()->route('barangs.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_barang_id' => 'required|exists:jenis_barangs,id',
            'status_barang_id' => 'required|exists:status_barangs,id',
            'kode_barang' => 'required|string|max:255|unique:barangs,kode_barang,'.$id,
            'nama_barang' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update([
            'jenis_barang_id' => $request->jenis_barang_id,
            'status_barang_id' => $request->status_barang_id,
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
        ]);

        return redirect()->route('barangs.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        Barang::findOrFail($id)->delete();
        return redirect()->route('barangs.index')->with('success', 'Data berhasil dihapus');
    }
}
