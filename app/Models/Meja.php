<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    protected $fillable = ['nomor_meja', 'qr_code_path'];

    public function pesanans() 
    {
        return $this->hasMany(Pesanan::class);
    }
}
