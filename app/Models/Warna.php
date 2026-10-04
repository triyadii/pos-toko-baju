<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['uuid', 'nama_warna', 'kode_warna', 'status'])]
class Warna extends Model
{
    //
}
