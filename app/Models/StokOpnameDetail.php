<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['uuid', 'stok_opname_id', 'barang_varian_id', 'stok_sistem', 'stok_fisik', 'selisih', 'keterangan'])]
class StokOpnameDetail extends Model
{
    public function stokOpname()
    {
        return $this->belongsTo(StokOpname::class);
    }

    public function barangVarian()
    {
        return $this->belongsTo(BarangVarian::class);
    }
}
