<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['uuid', 'nama_toko', 'alamat', 'telepon', 'email', 'slogan'])]
class ProfilToko extends Model
{
    //
}
