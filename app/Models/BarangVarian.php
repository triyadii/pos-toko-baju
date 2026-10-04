<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['uuid', 'barang_id', 'warna_id', 'ukuran_id', 'sku', 'barcode', 'harga_beli', 'harga_jual', 'status'])]
class BarangVarian extends Model
{
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function warna()
    {
        return $this->belongsTo(Warna::class);
    }

    public function ukuran()
    {
        return $this->belongsTo(Ukuran::class);
    }

    public function stok()
    {
        return $this->hasOne(Stok::class);
    }
}
