<?php

namespace App\Http\Controllers;

use App\Models\ProfilToko;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProfilTokoController extends Controller
{
    public function index()
    {
        $profil = ProfilToko::first();
        if (!$profil) {
            $profil = ProfilToko::create([
                'uuid' => Str::uuid(),
                'nama_toko' => 'POS Toko Baju',
            ]);
        }
        return view('profil_tokos.index', compact('profil'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'slogan' => 'nullable|string|max:255',
        ]);

        $profil = ProfilToko::first();
        if ($profil) {
            $profil->update($request->all());
        } else {
            $data = $request->all();
            $data['uuid'] = Str::uuid();
            ProfilToko::create($data);
        }

        return redirect()->back()->with('success', 'Profil toko berhasil diperbarui!');
    }
}
