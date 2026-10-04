<?php

namespace App\Http\Controllers;

use App\Models\Ukuran;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UkuranController extends Controller
{
    public function index()
    {
        $ukurans = Ukuran::orderBy('id', 'desc')->get();
        return view('ukurans.index', compact('ukurans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ukuran' => 'required|string|max:255',
            'urutan' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        Ukuran::create([
            'uuid' => Str::uuid(),
            'nama_ukuran' => $request->nama_ukuran,
            'urutan' => $request->urutan,
            'status' => $request->status,
        ]);

        return redirect()->route('ukurans.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_ukuran' => 'required|string|max:255',
            'urutan' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        $ukuran = Ukuran::findOrFail($id);
        $ukuran->update([
            'nama_ukuran' => $request->nama_ukuran,
            'urutan' => $request->urutan,
            'status' => $request->status,
        ]);

        return redirect()->route('ukurans.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        Ukuran::findOrFail($id)->delete();
        return redirect()->route('ukurans.index')->with('success', 'Data berhasil dihapus');
    }
}
