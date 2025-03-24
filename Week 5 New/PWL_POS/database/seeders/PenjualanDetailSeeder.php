<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    public function run()
    {
        DB::table('t_penjualan_detail')->truncate(); // Hapus semua data sebelum seeding

        for ($i = 1; $i <= 10; $i++) {
            DB::table('t_penjualan_detail')->insert([
                'barang_id' => rand(1, 10),
                'harga' => rand(50000, 100000),
                'jumlah' => rand(1, 5),
                'penjualan_id' => rand(1, 10),
            ]);
        }
    }
}