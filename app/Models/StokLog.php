<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['uuid', 'barang_varian_id', 'tipe', 'jumlah', 'stok_sebelum', 'stok_sesudah', 'referensi_id', 'keterangan', 'user_id'])]
class StokLog extends Model
{
    public function barangVarian()
    {
        return $this->belongsTo(BarangVarian::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
