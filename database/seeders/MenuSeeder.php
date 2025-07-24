<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MenuSeeder extends Seeder
{
    public function run()
    {
        DB::table('menus')->insert([
            [
                'nama_menu' => 'Kopi Hitam',
                'tipe' => 'coffee',
                'deskripsi' => 'Kopi hitam tanpa campuran',
                'harga' => "12000",
                // 'gambar' => 'menu/kopi-hitam.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_menu' => 'Smoothies',
                'tipe' => 'non_coffee',
                'deskripsi' => 'Jus buah segar',
                'harga' => "15000",
                // 'gambar' => 'menu/jus buah segar',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_menu' => 'Croissan',
                'tipe' => 'snack',
                'deskripsi' => 'Cemilan enak pengganjal perut lapar',
                'harga' => "18000",
                // 'gambar' => 'menu/roti-bakar.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
