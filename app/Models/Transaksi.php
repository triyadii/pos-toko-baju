<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['uuid', 'nomor_transaksi', 'user_id', 'tanggal_transaksi', 'subtotal', 'diskon', 'total', 'bayar', 'kembalian', 'status'])]
class Transaksi extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}
