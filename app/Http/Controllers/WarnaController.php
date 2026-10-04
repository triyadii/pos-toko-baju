<?php

namespace App\Http\Controllers;

use App\Models\Warna;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WarnaController extends Controller
{
    public function index()
    {
        $warnas = Warna::orderBy('id', 'desc')->get();
        return view('warnas.index', compact('warnas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_warna' => 'required|string|max:255',
            'kode_warna' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ]);

        Warna::create([
            'uuid' => Str::uuid(),
            'nama_warna' => $request->nama_warna,
            'kode_warna' => $request->kode_warna,
            'status' => $request->status,
        ]);

        return redirect()->route('warnas.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_warna' => 'required|string|max:255',
            'kode_warna' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ]);

        $warna = Warna::findOrFail($id);
        $warna->update([
            'nama_warna' => $request->nama_warna,
            'kode_warna' => $request->kode_warna,
            'status' => $request->status,
        ]);

        return redirect()->route('warnas.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        Warna::findOrFail($id)->delete();
        return redirect()->route('warnas.index')->with('success', 'Data berhasil dihapus');
    }
}
