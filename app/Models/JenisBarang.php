<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['uuid', 'nama_jenis_barang', 'status'])]
class JenisBarang extends Model
{
    //
}
