<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['uuid', 'transaksi_id', 'barang_varian_id', 'qty', 'harga', 'diskon', 'subtotal'])]
class TransaksiDetail extends Model
{
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function barangVarian()
    {
        return $this->belongsTo(BarangVarian::class);
    }
}
