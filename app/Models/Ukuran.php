<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['uuid', 'nama_ukuran', 'urutan', 'status'])]
class Ukuran extends Model
{
    //
}
