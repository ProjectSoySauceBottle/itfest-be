<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MejaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('mejas')->insert([
                'nomor_meja' => $i,
                'qr_code' => 'qr/meja-' . $i . '.png', // bisa generate nanti
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
