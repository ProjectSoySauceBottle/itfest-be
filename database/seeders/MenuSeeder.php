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
        "nama_menu" => "Espresso",
        "harga" => 20000,
        "tipe" => "coffee",
        "deskripsi" => "-",
        "gambar" => "/assets/images/menu/Espresso.jpg",
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ],
      [
        "nama_menu" => "Americano",
        "harga" => 25000,
        "tipe" => "coffee",
        "deskripsi" => "-",
        "gambar" => "/assets/images/menu/Americano.jpg",
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ],
      [
        "nama_menu" => "Latte",
        "harga" => 25000,
        "tipe" => "coffee",
        "deskripsi" => "-",
        "gambar" => "/assets/images/menu/Latte.jpg",
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ],
      [
        "nama_menu" => "Flat White",
        "harga" => 25000,
        "tipe" => "coffee",
        "deskripsi" => "-",
        "gambar" => "/assets/images/menu/Flat White.jpg",
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ],
      [
        "nama_menu" => "Mocha",
        "harga" => 30000,
        "tipe" => "coffee",
        "deskripsi" => "-",
        "gambar" => "/assets/images/menu/Mocha.jpg",
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ],
      [
        "nama_menu" => "Macchiato",
        "harga" => 20000,
        "tipe" => "coffee",
        "deskripsi" => "-",
        "gambar" => "/assets/images/menu/Macchiato.jpg",
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ],

      // Non_Coffee
      [
        "nama_menu" => "Chocolate Milk",
        "harga" => 22000,
        "tipe" => "non_coffee",
        "deskripsi" => "-",
        "gambar" => "/assets/images/menu/Chocolate Milk.jpg",
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ],
      [
        "nama_menu" => "Strawberry Smoothie",
        "harga" => 28000,
        "tipe" => "non_coffee",
        "deskripsi" => "-",
        "gambar" => "/assets/images/menu/Strawberry Smoothie.jpg",
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ],
      [
        "nama_menu" => "Matcha Latte",
        "harga" => 26000,
        "tipe" => "non_coffee",
        "deskripsi" => "-",
        "gambar" => "/assets/images/menu/Matcha Latte.jpg",
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ],
      [
        "nama_menu" => "Green Tea",
        "harga" => 18000,
        "tipe" => "non_coffee",
        "deskripsi" => "-",
        "gambar" => "/assets/images/menu/Green Tea.jpg",
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ],
      [
        "nama_menu" => "Lemon Tea",
        "harga" => 20000,
        "tipe" => "non_coffee",
        "deskripsi" => "-",
        "gambar" => "/assets/images/menu/Lemon Tea.jpg",
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ],
      [
        "nama_menu" => "Chamomile Tea",
        "harga" => 22000,
        "tipe" => "non_coffee",
        "deskripsi" => "-",
        "gambar" => "/assets/images/menu/Chamomile Tea.jpg",
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ],

      // Snack
      [
        "nama_menu" => "Croissant",
        "harga" => 15000,
        "tipe" => "snack",
        "deskripsi" => "-",
        "gambar" => "/assets/images/menu/Croissant.jpg",
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ],
      [
        "nama_menu" => "Cheesecake",
        "harga" => 30000,
        "tipe" => "snack",
        "deskripsi" => "-",
        "gambar" => "/assets/images/menu/Cheesecake.jpg",
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ],
      [
        "nama_menu" => "French Fries",
        "harga" => 18000,
        "tipe" => "snack",
        "deskripsi" => "-",
        "gambar" => "/assets/images/menu/French Fries.jpg",
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ],
    ]);
  }
}
