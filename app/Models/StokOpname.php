<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['uuid', 'nomor_opname', 'tanggal_opname', 'user_id', 'status', 'keterangan'])]
class StokOpname extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(StokOpnameDetail::class);
    }
}
