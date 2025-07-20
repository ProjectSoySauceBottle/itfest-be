<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = ['meja_id', 'total_harga', 'metode_bayar', 'status'];

    public function meja()
    {
        return $this->belongsTo(Meja::class);
    }

    public function menu()
    {
        return $this->belongsTo(menu::class);
    }

    public function details()
    {
        return $this->hasMany(pesanan_detail::class);
    }
}