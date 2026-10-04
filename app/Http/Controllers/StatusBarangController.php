<?php

namespace App\Http\Controllers;

use App\Models\StatusBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StatusBarangController extends Controller
{
    public function index()
    {
        $status_barangs = StatusBarang::orderBy('id', 'desc')->get();
        return view('status_barangs.index', compact('status_barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_status_barang' => 'required|string|max:255',
        ]);

        StatusBarang::create([
            'uuid' => Str::uuid(),
            'nama_status_barang' => $request->nama_status_barang,
        ]);

        return redirect()->route('status-barangs.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_status_barang' => 'required|string|max:255',
        ]);

        $statusBarang = StatusBarang::findOrFail($id);
        $statusBarang->update([
            'nama_status_barang' => $request->nama_status_barang,
        ]);

        return redirect()->route('status-barangs.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        StatusBarang::findOrFail($id)->delete();
        return redirect()->route('status-barangs.index')->with('success', 'Data berhasil dihapus');
    }
}
