<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['user', 'details.barangVarian.barang', 'details.barangVarian.warna', 'details.barangVarian.ukuran'])
            ->orderBy('created_at', 'desc');

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }

        $transaksis = $query->get();
        $totalPendapatan = $transaksis->sum('total');

        return view('transaksis.index', compact('transaksis', 'totalPendapatan', 'start_date', 'end_date'));
    }

    public function print($id)
    {
        $trx = Transaksi::with(['user', 'details.barangVarian.barang', 'details.barangVarian.warna', 'details.barangVarian.ukuran'])->findOrFail($id);
        return view('transaksis.print', compact('trx'));
    }
}
