<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JenisBarangController extends Controller
{
    public function index()
    {
        $jenis_barangs = JenisBarang::orderBy('id', 'desc')->get();
        return view('jenis_barangs.index', compact('jenis_barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis_barang' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        JenisBarang::create([
            'uuid' => Str::uuid(),
            'nama_jenis_barang' => $request->nama_jenis_barang,
            'status' => $request->status,
        ]);

        return redirect()->route('jenis-barangs.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jenis_barang' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $jenisBarang = JenisBarang::findOrFail($id);
        $jenisBarang->update([
            'nama_jenis_barang' => $request->nama_jenis_barang,
            'status' => $request->status,
        ]);

        return redirect()->route('jenis-barangs.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        JenisBarang::findOrFail($id)->delete();
        return redirect()->route('jenis-barangs.index')->with('success', 'Data berhasil dihapus');
    }
}
