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
                'harga' => 12000,
                'gambar' => 'menu/kopi-hitam.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_menu' => 'Cappuccino',
                'tipe' => 'coffee',
                'deskripsi' => 'Kopi dengan campuran cappucino',
                'harga' => 20000,
                'gambar' => 'menu/cappuccino.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_menu' => 'Croissan',
                'tipe' => 'non_coffee',
                'deskripsi' => 'Cemilan enak pengganjal perut lapar',
                'harga' => 18000,
                'gambar' => 'menu/roti-bakar.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
