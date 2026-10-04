<?php

namespace App\Http\Controllers;

use App\Models\BarangVarian;
use App\Models\Barang;
use App\Models\Warna;
use App\Models\Ukuran;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BarangVarianController extends Controller
{
    public function index()
    {
        $barang_varians = BarangVarian::with(['barang', 'warna', 'ukuran'])->orderBy('id', 'desc')->get();
        $barangs = Barang::all();
        $warnas = Warna::all();
        $ukurans = Ukuran::all();
        return view('barang_varians.index', compact('barang_varians', 'barangs', 'warnas', 'ukurans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'warna_id' => 'required|exists:warnas,id',
            'ukuran_id' => 'required|exists:ukurans,id',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'status' => 'required|boolean',
        ]);

        $barang = Barang::findOrFail($request->barang_id);
        $warna = Warna::findOrFail($request->warna_id);
        $ukuran = Ukuran::findOrFail($request->ukuran_id);

        $warnaStr = strtoupper(substr($warna->nama_warna, 0, 3));
        $ukuranStr = strtoupper(substr($ukuran->nama_ukuran, 0, 3));
        $sku = $barang->kode_barang . '-' . $warnaStr . '-' . $ukuranStr;
        $barcode = time() . rand(10, 99);

        BarangVarian::create([
            'uuid' => Str::uuid(),
            'barang_id' => $request->barang_id,
            'warna_id' => $request->warna_id,
            'ukuran_id' => $request->ukuran_id,
            'sku' => $sku,
            'barcode' => $barcode,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'status' => $request->status,
        ]);

        return redirect()->route('barang-varians.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'warna_id' => 'required|exists:warnas,id',
            'ukuran_id' => 'required|exists:ukurans,id',
            'sku' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'status' => 'required|boolean',
        ]);

        $barangVarian = BarangVarian::findOrFail($id);
        $barangVarian->update([
            'barang_id' => $request->barang_id,
            'warna_id' => $request->warna_id,
            'ukuran_id' => $request->ukuran_id,
            'sku' => $request->sku,
            'barcode' => $request->barcode,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'status' => $request->status,
        ]);

        return redirect()->route('barang-varians.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        BarangVarian::findOrFail($id)->delete();
        return redirect()->route('barang-varians.index')->with('success', 'Data berhasil dihapus');
    }
}
