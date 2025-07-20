<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            ['nama' => 'Espresso', 'deskripsi' => 'Espresso single shot', 'harga' => 15000],
            ['nama' => 'Cappuccino', 'deskripsi' => 'Espresso with steamed milk and foam', 'harga' => 20000],
            ['nama' => 'Latte', 'deskripsi' => 'Espresso with steamed milk', 'harga' => 22000],
            ['nama' => 'Americano', 'deskripsi' => 'Espresso with hot water', 'harga' => 18000],
            ['nama' => 'Cold Brew', 'deskripsi' => 'Cold brewed coffee', 'harga' => 25000],
            ['nama' => 'Mocha', 'deskripsi' => 'Espresso with chocolate and milk', 'harga' => 23000],
        ];

        foreach ($menus as $menu) {
            DB::table('menus')->insert([
                ...$menu,
                'gambar' => 'menu/default.png',
                'bestseller_count' => rand(10, 100),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
