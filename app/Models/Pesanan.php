<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pesanan extends Model
{
    use HasFactory;

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
        return $this->hasMany(pesanandetail::class);
    }
}
