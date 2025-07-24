<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pesanan extends Model
{
    use HasFactory;
    protected $primaryKey = 'pesanan_id';
    protected $fillable = ['meja_id', 'total_harga', 'metode_bayar', 'status_bayar'];

    public function meja()
    {
        return $this->belongsTo(Meja::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function pesananDetails()
    {
        return $this->hasMany(pesanandetail::class, 'pesanan_id', 'pesanan_id');
    }
}
