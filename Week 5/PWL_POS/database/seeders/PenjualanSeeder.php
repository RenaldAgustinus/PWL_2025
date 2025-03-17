<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_penjualan')->insert([
            ['penjualan_id' => 1, 'user_id' => 1, 'pembeli' => 'John Doe', 'penjualan_kode' => 'PJ001', 'penjualan_tanggal' => '2025-03-01 10:00:00'],
            ['penjualan_id' => 2, 'user_id' => 2, 'pembeli' => 'Jane Smith', 'penjualan_kode' => 'PJ002', 'penjualan_tanggal' => '2025-03-02 14:30:00'],
            ['penjualan_id' => 3, 'user_id' => 1, 'pembeli' => 'Michael Lee', 'penjualan_kode' => 'PJ003', 'penjualan_tanggal' => '2025-03-03 12:15:00'],
            ['penjualan_id' => 4, 'user_id' => 3, 'pembeli' => 'Emily Johnson', 'penjualan_kode' => 'PJ004', 'penjualan_tanggal' => '2025-03-04 16:45:00'],
            ['penjualan_id' => 5, 'user_id' => 2, 'pembeli' => 'David Brown', 'penjualan_kode' => 'PJ005', 'penjualan_tanggal' => '2025-03-05 09:20:00'],
        ]);
        
    }
}
