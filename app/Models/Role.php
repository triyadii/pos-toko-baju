<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['uuid', 'nama_role', 'status'])]
class Role extends Model
{
    //
}
