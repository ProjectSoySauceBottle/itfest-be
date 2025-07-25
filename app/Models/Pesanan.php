<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;
    protected $primaryKey = 'pesanan_id';

    protected $fillable = ['meja_id', 'jumlah_pesanan', 'total_harga', 'metode_bayar', 'status_bayar'];

    public function meja()
    {
        return $this->belongsTo(Meja::class, 'meja_id', 'meja_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }

    public function pesananDetails()
    {
        return $this->hasMany(PesananDetail::class, 'pesanan_id', 'pesanan_id');
    }
}
