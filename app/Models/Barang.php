<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['uuid', 'jenis_barang_id', 'status_barang_id', 'kode_barang', 'nama_barang', 'harga_beli', 'harga_jual'])]
class Barang extends Model
{
    public function jenisBarang()
    {
        return $this->belongsTo(JenisBarang::class);
    }

    public function statusBarang()
    {
        return $this->belongsTo(StatusBarang::class);
    }
}
