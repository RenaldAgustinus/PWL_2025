<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelController extends Controller
{
    public function index()
    {
        //DB::insert("INSERT INTO m_level (level_kode, level_nama, created_at) VALUES (?, ?, ?)", [
        //    'CUS',
        //    'Pelanggan',
        //    now() // atau '2025-03-03 13:35:47'
        //]);
        
        //return 'Insert data baru berhasil';

        // $row = DB::update('update m_level set level_nama = ? where level_kode = ?', 
        // ['customer', 'CUS']);
        // return 'Update data berhasil. Jumlah data yang diupdate: ' . $row . ' baris';

        //$row = DB::delete('delete from m_level where level_kode = ?', ['CUS']);
        //return 'Delete data berhasil. Jumlah data yang dihapus: ' . $row . ' baris';

         $data = DB::select('select * from m_level');
         return view('level', ['data' => $data]);
    }
}