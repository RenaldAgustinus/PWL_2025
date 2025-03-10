<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_stok')->insert([
            [
                'barang_id' => 1,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now()->subDays(10),
                'stok_jumlah' => 50,

            ],
            [
                'barang_id' => 2,
                'user_id' => 2,
                'stok_tanggal' => Carbon::now()->subDays(9),
                'stok_jumlah' => 100,

            ],
            [
                'barang_id' => 3,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now()->subDays(8),
                'stok_jumlah' => 80,

            ],
            [
                'barang_id' => 4,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now()->subDays(7),
                'stok_jumlah' => 70,

            ],
            [
                'barang_id' => 5,
                'user_id' => 2,
                'stok_tanggal' => Carbon::now()->subDays(6),
                'stok_jumlah' => 90,

            ],
            [
                'barang_id' => 6,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now()->subDays(5),
                'stok_jumlah' => 60,
            ],
            [
                'barang_id' => 7,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now()->subDays(4),
                'stok_jumlah' => 20,

            ],
            [
                'barang_id' => 8,
                'user_id' => 2,
                'stok_tanggal' => Carbon::now()->subDays(3),
                'stok_jumlah' => 15,
            ],
            [
                'barang_id' => 9,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now()->subDays(2),
                'stok_jumlah' => 200,

            ],
            [
                'barang_id' => 10,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now()->subDays(1),
                'stok_jumlah' => 180,
            ],
        ]);
    }
}
