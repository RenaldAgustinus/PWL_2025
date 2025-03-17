<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_barang')->insert([
            [
                'barang_id' => 1,
                'kategori_id' => 1,
                'barang_kode' => 'BRG001',
                'barang_nama' => 'Nasi Goreng',
                'harga_beli' => 12000,
                'harga_jual' => 15000
            ],
            [
                'barang_id' => 2,
                'kategori_id' => 2,
                'barang_kode' => 'BRG002',
                'barang_nama' => 'Es Teh Manis',
                'harga_beli' => 3000,
                'harga_jual' => 5000
            ],
            [
                'barang_id' => 3,
                'kategori_id' => 3,
                'barang_kode' => 'BRG003',
                'barang_nama' => 'Keripik Kentang',
                'harga_beli' => 8000,
                'harga_jual' => 10000
            ],
            [
                'barang_id' => 4,
                'kategori_id' => 1,
                'barang_kode' => 'BRG004',
                'barang_nama' => 'Mie Goreng',
                'harga_beli' => 10000,
                'harga_jual' => 13000
            ],
            [
                'barang_id' => 5,
                'kategori_id' => 2,
                'barang_kode' => 'BRG005',
                'barang_nama' => 'Jus Alpukat',
                'harga_beli' => 10000,
                'harga_jual' => 12000
            ],
            [
                'barang_id' => 6,
                'kategori_id' => 3,
                'barang_kode' => 'BRG006',
                'barang_nama' => 'Biskuit Coklat',
                'harga_beli' => 5000,
                'harga_jual' => 7000
            ],
            [
                'barang_id' => 7,
                'kategori_id' => 4,
                'barang_kode' => 'BRG007',
                'barang_nama' => 'Wajan',
                'harga_beli' => 45000,
                'harga_jual' => 50000
            ],
            [
                'barang_id' => 8,
                'kategori_id' => 4,
                'barang_kode' => 'BRG008',
                'barang_nama' => 'Panci',
                'harga_beli' => 55000,
                'harga_jual' => 60000
            ],
            [
                'barang_id' => 9,
                'kategori_id' => 5,
                'barang_kode' => 'BRG009',
                'barang_nama' => 'Garam',
                'harga_beli' => 2500,
                'harga_jual' => 3000
            ],
            [
                'barang_id' => 10,
                'kategori_id' => 5,
                'barang_kode' => 'BRG010',
                'barang_nama' => 'Gula Pasir',
                'harga_beli' => 3500,
                'harga_jual' => 4000
            ],
        ]);
    }
}
