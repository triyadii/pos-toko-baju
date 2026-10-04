<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['uuid', 'barang_varian_id', 'jumlah_stok'])]
class Stok extends Model
{
    public function barangVarian()
    {
        return $this->belongsTo(BarangVarian::class);
    }
}
