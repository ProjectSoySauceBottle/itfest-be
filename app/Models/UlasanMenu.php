<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UlasanMenu extends Model
{
    use HasFactory;
    protected $fillable = ['menu_id', 'rating', 'komentar'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
