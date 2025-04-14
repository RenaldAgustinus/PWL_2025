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
        DB::table('t_penjualan_detail')->truncate(); // Hapus semua data sebelum seeding

        DB::table('t_penjualan')->truncate(); // Hapus semua data sebelum seeding

        DB::table('t_penjualan')->insert([
            ['penjualan_id' => 1, 'user_id' => 1, 'pembeli' => 'John Doe', 'penjualan_kode' => 'PJ001', 'penjualan_tanggal' => '2025-03-01 10:00:00'],
            ['penjualan_id' => 2, 'user_id' => 2, 'pembeli' => 'Jane Smith', 'penjualan_kode' => 'PJ002', 'penjualan_tanggal' => '2025-03-02 14:30:00'],
            ['penjualan_id' => 3, 'user_id' => 1, 'pembeli' => 'Michael Lee', 'penjualan_kode' => 'PJ003', 'penjualan_tanggal' => '2025-03-03 12:15:00'],
            ['penjualan_id' => 4, 'user_id' => 3, 'pembeli' => 'Emily Johnson', 'penjualan_kode' => 'PJ004', 'penjualan_tanggal' => '2025-03-04 16:45:00'],
            ['penjualan_id' => 5, 'user_id' => 2, 'pembeli' => 'David Brown', 'penjualan_kode' => 'PJ005', 'penjualan_tanggal' => '2025-03-05 09:20:00'],
            ['penjualan_id' => 6, 'user_id' => 2, 'pembeli' => 'Bagas Putra', 'penjualan_kode' => 'PJ006', 'penjualan_tanggal' => '2025-03-05 10:20:00'],
            ['penjualan_id' => 7, 'user_id' => 3, 'pembeli' => 'Dzulfikar', 'penjualan_kode' => 'PJ007', 'penjualan_tanggal' => '2025-03-05 11:24:00'],
            ['penjualan_id' => 8, 'user_id' => 3, 'pembeli' => 'Renald', 'penjualan_kode' => 'PJ008', 'penjualan_tanggal' => '2025-03-05 11:20:00'],
            ['penjualan_id' => 9, 'user_id' => 2, 'pembeli' => 'Blacky', 'penjualan_kode' => 'PJ009', 'penjualan_tanggal' => '2025-03-05 12:24:00'],
            ['penjualan_id' => 10, 'user_id' => 1, 'pembeli' => 'Wahyu', 'penjualan_kode' => 'PJ010', 'penjualan_tanggal' => '2025-03-05 12:25:00']
        ]);
        
    }
}
