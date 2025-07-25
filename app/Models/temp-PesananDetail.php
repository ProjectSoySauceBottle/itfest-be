<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananDetail extends Model
{
    use HasFactory;
    protected $table = 'pesanandetails';
    protected $primaryKey = 'pesanandetail_id';
    protected $fillable = [
        'pesanan_id',
        'menu_id',
        'jumlah_pesanan',
        'harga_satuan',
        'total_harga'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id', 'pesanan_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }
}
