<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $primaryKey = 'menu_id';
    protected $fillable = ['nama_menu', 'tipe', 'deskripsi', 'harga', 'gambar', 'bestseller_count'];

    public function PesananDetails()
    {
        return $this->hasMany(PesananDetail::class);
    }

    public function UlasanMenus()
    {
        return $this->hasMany(UlasanMenu::class);
    }
}
