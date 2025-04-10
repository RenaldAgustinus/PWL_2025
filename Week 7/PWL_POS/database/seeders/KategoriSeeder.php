<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kategori_id' => 1, 'kategori_kode' => 'MK001', 'kategori_nama' => 'Makanan'],
            ['kategori_id' => 2, 'kategori_kode' => 'MN002', 'kategori_nama' => 'Minuman'],
            ['kategori_id' => 3, 'kategori_kode' => 'SN003', 'kategori_nama' => 'Snack'],
            ['kategori_id' => 4, 'kategori_kode' => 'PD004', 'kategori_nama' => 'Peralatan Dapur'],
            ['kategori_id' => 5, 'kategori_kode' => 'BD005', 'kategori_nama' => 'Bumbu Dapur'],
        ];

        DB::table('m_kategori')->insert($data);
    }
}
