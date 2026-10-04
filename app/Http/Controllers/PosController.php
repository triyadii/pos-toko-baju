<?php

namespace App\Http\Controllers;

use App\Models\BarangVarian;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Stok;
use App\Models\StokLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function index()
    {
        $varians = BarangVarian::with(['barang.jenisBarang', 'warna', 'ukuran', 'stok'])->where('status', 1)->get();
        return view('pos', compact('varians'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:barang_varians,id',
            'items.*.qty' => 'required|integer|min:1',
            'subtotal' => 'required|numeric',
            'diskon' => 'nullable|numeric',
            'total' => 'required|numeric',
            'bayar' => 'required|numeric',
            'kembalian' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $transaksi = Transaksi::create([
                'uuid' => Str::uuid(),
                'nomor_transaksi' => 'TRX-' . date('YmdHis') . rand(100, 999),
                'user_id' => Auth::id() ?? 1,
                'tanggal_transaksi' => now(),
                'subtotal' => $request->subtotal,
                'diskon' => $request->diskon ?? 0,
                'total' => $request->total,
                'bayar' => $request->bayar,
                'kembalian' => $request->kembalian,
                'status' => 'lunas',
            ]);

            foreach ($request->items as $item) {
                $varian = BarangVarian::findOrFail($item['id']);
                $stokRecord = Stok::where('barang_varian_id', $varian->id)->first();
                $stokSebelum = $stokRecord ? $stokRecord->jumlah_stok : 0;

                if ($stokSebelum < $item['qty']) {
                    throw new \Exception("Stok tidak mencukupi untuk item: " . $varian->barang->nama_barang);
                }

                $harga = $varian->harga_jual;
                $subtotal = $harga * $item['qty'];

                TransaksiDetail::create([
                    'uuid' => Str::uuid(),
                    'transaksi_id' => $transaksi->id,
                    'barang_varian_id' => $varian->id,
                    'qty' => $item['qty'],
                    'harga' => $harga,
                    'diskon' => 0, // Simplified for now
                    'subtotal' => $subtotal,
                ]);

                // Update Stock
                $stokRecord->update(['jumlah_stok' => $stokSebelum - $item['qty']]);

                // Log Stock
                StokLog::create([
                    'uuid' => Str::uuid(),
                    'barang_varian_id' => $varian->id,
                    'tipe' => 'keluar',
                    'jumlah' => $item['qty'],
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $stokSebelum - $item['qty'],
                    'referensi_id' => $transaksi->nomor_transaksi,
                    'keterangan' => 'Penjualan POS',
                    'user_id' => Auth::id() ?? 1,
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil!', 'nomor_transaksi' => $transaksi->nomor_transaksi, 'transaction_id' => $transaksi->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
