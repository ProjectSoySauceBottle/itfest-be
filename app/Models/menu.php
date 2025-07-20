<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class menu extends Model
{
    use HasFactory;
    protected $fillable = ['nama_menu', 'deskripsi', 'harga', 'gambar', 'bestseller_count'];

    public function PesananDetails()
    {
        return $this->hasMany(pesanan_detail::class);
    }

    public function UlasanMenus()
    {
        return $this->hasMany(ulasan_menu::class);
    }
}
